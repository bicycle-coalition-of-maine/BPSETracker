<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

use app\models\Request;
use app\models\CityCounty;
use app\models\Person;
use app\models\Organization;
use app\models\Contact;
use app\models\Event;
use app\models\EventAge;
use app\models\EventType;
use app\models\EventEventType;
use app\models\Config;

/**
 * Controller for the public request form (after page 1)
 *
 * @author John Brooking
 */
class RequestController extends Controller
{

    public $title = 'Request BPSE/STRS Assistance';
    
    /*
     * Function: matchPerson()
     * 
     * Fuzzy match for person, by comparing last name, email, and phone.
     * Returns an array of two elements, an integer person ID and a boolean
     * "certainty" flag. A true flag means that we're certain that the
     * returned integer is the ID of the person providing the input, either
     * because this function found a match to an existing person on all 3
     * fields, or because no match at all was found, in which case this
     * function creates the new person in the database, and this is its ID.
     * A false flag means we're not sure, and the integer ID is our best guess.
     */
    
    private function matchPerson($model)
    {
        /*
         * Logic: Query by last name OR email OR phone.
         * 
         * 0) If no records are returned, it's definitely a new person.
         * 
         * 1) If one record is returned, manually compare each field to see how
         * many matched. If all 3, then it's a certain match, otherwise it's
         * an uncertain one.
         * 
         * N) If multiple records are returned, go through the record list and
         * count matching fields, as per (1) above. Remember only the first
         * record with the highest number of matches, return that person ID.
         */
        
        $model->newPerson = false;
        
        $matches = Person::find()
                ->where(['or', 
                        "lastName='{$model->lastName}'",
                        "email='{$model->email}'",
                        "phone='{$model->phone}'",
                        ])
                ->all();

        // No records, so must be a new person
        if(count($matches) == 0)
        {
            $person = new Person();
            $person->firstName = $model->firstName;
            $person->lastName = $model->lastName;
            $person->email = $model->email;
            $person->phone = $model->phone;
            $person->phoneExt = $model->phoneExt;
            $person->isContact = 1;
            $person->save();
            
            $model->newPerson = true;
            $model->save();
            
            return [$person->pkPersonID, 0];
        }
        
        // At least one field match, loop through them, return first with the most matches
        $savedPerson = new Person(); // just to initialize, will reset in loop
        $maxMatchCount = 0;
        foreach($matches as $person)
        {
            $thisMatchCount = 0;
            if($person->lastName == $model->lastName) ++$thisMatchCount;
            if($person->email == $model->email) ++$thisMatchCount;
            if($person->phone == $model->phone) ++$thisMatchCount;            
            if($thisMatchCount > $maxMatchCount)
            {
                $savedPerson = $person;
                $maxMatchCount = $thisMatchCount;
            }
        }
        
        // If all 3 fields matched, then update fields if necessary.
        // Do it here because person panel is skipped in this case.
        if($maxMatchCount == 3) 
        {
            $existing = Person::findOne($savedPerson->pkPersonID);
            $existing->firstName = $model->firstName;
            $existing->phoneExt = $model->phoneExt;
            $existing->save();
        }
        
        return [$savedPerson->pkPersonID, $maxMatchCount];
    }
    
    public function actionIndex()
    {
        $model = new Request();
        
        if($model->load(Yii::$app->request->post()) 
                && $model->validate(['firstName', 'lastName', 'email', 'phone']) 
                && $model->save())
        {
            
            /* Here we branch either directly to the org screen if we're sure
             * we know the person's identity, or to the person confirmation
             * screen if not. The matching logic is concentrated in the
             * matchPerson() method, which returns both a person ID, and an
             * integer indicating the number of matching fields, 0-3. If no
             * fields matched, we can be certain it is a new person. If all 3
             * fields matched, so we can be certain it's this existing person.
             * 1 or 2 matching fileds mean we're not sure and will have to ask. 
             * In the case of a certain new person (0), the new DB record is
             * created within the match function, in order to return the valid
             * primary key, which is assigned to this model's fkContactID
             * attribute. If we're not sure, the new person DB record is
             * created after the person confirmation screen.
             */
            
            $matchResults = $this->matchPerson($model);
            if($matchResults[1] == 0 || $matchResults[1] == 3)
            {
                $model->fkContactID = $matchResults[0];
                $model->save();
//                return $this->render('org', [
//                    'title' => $this->title,
//                    'model' => $model,
//                ]);
                return $this->redirect(['org']);
            }
            else
            {
                return $this->redirect(['person',
                    'id' => $matchResults[0],
                    'm' => $matchResults[1],
                ]);
            }
        }
        
        return $this->render('index', [
            'title' => $this->title,
            'model' => $model,
            'countyDDList' => CityCounty::getCountyDropDownItems(),
        ]);
    }
    
    public function actionPerson($id, $m)
    {
        $model = new Request();
        $model->loadFromSession();

        if($model->load(Yii::$app->request->post()) 
                && $model->validate(['firstName', 'lastName', 'email', 'phone']) 
                && $model->save())
        {
            if(Yii::$app->request->post('ThisIsMe') == 'yes')   // Update existing
            {
                $person = Person::findOne($model->fkContactID);
                $person->firstName = $model->firstName;
                $person->lastName = $model->lastName;
                $person->email = $model->email;
                $person->phone = $model->phone;
                $person->phoneExt = $model->phoneExt;
                $person->save();
                $model->newPerson = false;
            }
            else // Create new
            {  
                $person = new Person();
                $person->firstName = $model->firstName;
                $person->lastName = $model->lastName;
                $person->email = $model->email;
                $person->phone = $model->phone;
                $person->phoneExt = $model->phoneExt;
                $person->isContact = 1;
                $person->save();
                $model->newPerson = true;
                
                // Save the new person ID
                $model->fkContactID = $person->pkPersonID;
            }
            $model->save();

            return $this->redirect(['org']);
        }
        
        return $this->render('person', [
            'title' => $this->title,
            'model' => $model,
            'person' => Person::findOne($id),
            'matchLevel' => $m,
        ]);
    }
    
    public function actionOrg($all = '0')
    {
        $model = new Request();
        $model->loadFromSession();  
        
        if($model->load(Yii::$app->request->post()) 
                && $model->validate(['orgName', 'orgAddress', 'orgCity', 'orgZip']) 
                && $model->save())
        {            
            if(!$model->fkOrgID) // New organization
            {
                $org = new Organization();
                $org->name = $model->orgName;
                $org->address1 = $model->orgAddress;
                $org->city = $model->orgCity;
                $org->zipcode = $model->orgZip;
                $org->county = CityCounty::getCounty($model->orgCity);
                $org->save();
                
                $model->fkOrgID = $org->pkOrgID;
                $model->newOrg = true;
            }
            else // If existing, update if changes
            {
                $org = Organization::findOne($model->fkOrgID);
                $org->name = $model->orgName;
                $org->address1 = $model->orgAddress;
                $org->city = $model->orgCity;
                $org->zipcode = $model->orgZip;
                $org->county = CityCounty::getCounty($model->orgCity);
                if(count($org->dirtyAttributes))
                    $org->save();
                $model->newOrg = false;
            }

            // Add or update this person as contact
            $contact = Contact::findOne([
                'fkPersonID' => $model->fkContactID,
                'fkOrgID' => $model->fkOrgID
            ]);
            if($contact) // Already recorded as org contact, updated if changes
            {
                $contact->title = $model->title;
                if(count($contact->dirtyAttributes))
                    $contact->save();
            }
            else
            {
                $contact = new Contact();
                $contact->fkPersonID = $model->fkContactID;
                $contact->fkOrgID = $model->fkOrgID;
                $contact->title = $model->title;
                $contact->save();
            }
            
            // If event is at same address, pre-populate the event location details
            if($model->isAtOrgAddress) 
            {
                $model->eventAddress = $model->orgAddress;
                $model->eventCity = $model->orgCity;
                $model->eventZip = $model->orgZip;
                // County goes the other way, because they specified the event county on the first panel
                $model->orgCounty = $model->eventCounty;
            }
            
            $model->save();     // Save any changes we may have made
            return $this->redirect(['event']);
        }

        $model->fkOrgID = '';
        $model->newOrg = 1;
        $model->isAtOrgAddress = 1;
        
        $orgQuery = Organization::find()
                    ->select(["CONCAT(name, '; ', address1, '; ', city, '; ', zipcode)"])
                    ->indexBy('pkOrgID')
                    ->orderBy('name');
        if($all != '1') 
            $orgQuery = $orgQuery->where(['county' => $model->eventCounty]);
        $orgDDList = $orgQuery->column();
        
        return $this->render('org', [
            'title' => $this->title,
            'model' => $model,
            'orgs' => $orgDDList,
        ]);
    }
    
    public function actionEvent()
    {
        $model = new Request();
        $model->loadFromSession();

        // Handle post, if it is one
        
        if($model->load(Yii::$app->request->post()) 
                && $model->validate(['need', 'estPresentations', 'estParticipants', 'proposedDates']) 
                && $model->save())
        {
            // Create basic event and save it to database
            $event = new Event();
            $event->requestDateTime = (new \DateTime())->format('Y-m-d H:i:s');
            $event->fkOrgID = $model->fkOrgID;
            $event->isAtOrgAddress = $model->isAtOrgAddress;
            $event->address1 = $model->eventAddress;
            $event->city = $model->eventCity;
            $event->state = 'ME';
            $event->zipcode = $model->eventZip;
            $event->county = CityCounty::getCounty($model->eventCity);
            $event->fkPersonID = $model->fkContactID;
            if($model->otherType) $event->otherType = $model->otherType;
            $event->fkEventAgeID = $model->fkEventAgeID;
            if($model->ageDesc) $event->ageDescription = $model->ageDesc;
            $event->need = $model->need;
            $event->participation = $model->estParticipants;
            $event->presentations = $model->estPresentations;
            $event->datetimes = $model->proposedDates;
            if($model->additionalInfo) $event->comments = $model->additionalInfo;
            $event->save();
            
            // Update the request with the new Event ID
            $model->pkEventID = $event->pkEventID;
            $model->save();
            
            // Create event type junction table records
            $givenIDs = Yii::$app->request->post('eventType');
            if($givenIDs) 
            {
                if(!is_array($givenIDs)) {      // If only one ID, make it into
                    $givenIDs = [$givenIDs];    // an array anyway for processing
                }
            }
            $model->eventTypes = array();       // Reinitialize in case re-using
            foreach ($givenIDs as $givenID)
            {
                // Save to session request
                $model->eventTypes[] = $givenID;
                // Create junction table record
                $joinRec = new EventEventType();
                $joinRec->fkEventID = $event->pkEventID;
                $joinRec->fkEventTypeID = $givenID;
                $joinRec->save();
            }
            
            // Send email notification

            // Fill in To/CC from config
            $to = array();
            $cc = array();
            (Config::findOne('ReqEmailRecips'))->assignEmailRecips($to, $cc);

            // CC requester if desired
            if($model->copyMe)
                $cc[$model->email] = "{$model->firstName} {$model->lastName}";
            
            // Set subject from config
            $subject = (Config::findOne('ReqEmailSubject'))
                    ->substituteParams(
                            "{$event->eventTypeString} at {$model->orgName}"
                            );

            $emailBody = $this->formatAsHTMLTable($model, $event);
            $emailBody .= "<p>copyMe = '{$model->copyMe}'</p>";

            $message = Yii::$app->mailer->compose()
                ->setFrom('donotreply@bikemaine.org')
                ->setTo($to)
                ->setSubject($subject)
                ->setHtmlBody($emailBody);

            if(count($cc))
                $message->setCc($model->email);
            
            $message->send();
            
            // Redirect to the confirmation screen
            return $this->redirect(['confirm', 'id' => $event->pkEventID]);
            
        }
        
        // If not post, render view screen
        
        $eventTypes = EventType::find()
                ->where("isPublic = b'1'")
                ->orderBy('sequence')
                ->all();
        
        $ageDDList = EventAge::find()
                ->select('eventAge')
                ->indexBy('pkEventAgeID')
                ->orderBy('sequence')
                ->column();
        
        $model->copyMe = true;
        
        return $this->render('event', [
            'title' => $this->title,
            'model' => $model,
            'types' => $eventTypes,
            'ageDDList' => $ageDDList,
        ]);
    }
    
    public function actionConfirm($id)
    {
        $model = new Request();
        $model->loadFromSession();
        $msg = Config::findOne('RequestThankYou');
        $event = Event::findOne($model->pkEventID);
        
        return $this->render('confirm', [
            'title' => $this->title,
            'model' => $model,
            'table' => $this->formatAsHTMLTable($model, $event),
            'msg' => $msg->strValue,
        ]);
    }
    
    public function formatAsHTMLTable($model, $event)
    {
        return Yii::$app->globals->formatAsHTMLTable(
            [
            'firstName' => $model->firstName,
            'lastName' => $model->lastName . ($model->newPerson ? ' <b>* NEW *</b>' : ''),
            'email' => $model->email,
            'phone' => Yii::$app->globals->formatPhone($model->phone),
            'phoneExt' => $model->phoneExt,
            'title' => $model->title,
            'orgName' => $model->orgName . ($model->newOrg ? ' <b>* NEW *</b>' : ''),
            'isAtOrgAddress' => $model->isAtOrgAddress ? 'Yes' : 'No',
            'eventTypes' => $event->eventTypeString,
            'otherType' => $event->otherType,
            'eventAddress' => $event->address1,
            'eventCity' => $event->city,
            'eventZip' => $event->zipcode,
            'eventCounty' => $event->county,
            'need' => $event->need,
            'estPresentations' => $event->presentations,
            'estParticipants' => $event->participation,
            'fkEventAgeID' => 'General Age Groups / Grades',
            'ageDesc' => $event->ageDescription,
            'proposedDates' => $event->datetimes,
            'additionalInfo' => $event->comments,
            ],
            $model->attributeLabels()
        );        
    }
}
