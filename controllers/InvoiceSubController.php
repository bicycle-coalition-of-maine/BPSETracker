<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;

use app\models\Config;
use app\models\InvoiceSubmission;
use app\models\Person;
use app\models\Event;
use app\models\Rate;
use app\models\Mileage;
use app\models\Invoice;

/**
 * Controller for the contractor invoicing form
 *
 * @author John Brooking
 */
class InvoiceSubController extends Controller {
    
    public $title = 'Contractor Invoice';
    
    // Return non-zero pkPersonID on match, or 0 on no match
    private function matchPerson($model)
    {
        $matches = Person::find()
                ->where("lastName = '{$model->lastName}' AND phone LIKE '%{$model->pin}' AND isStaff = b'1'")
                ->select('pkPersonID')
                ->all();
        return (count($matches) == 1 ? $matches[0]['pkPersonID'] : 0);
    }
    
    public function actionIndex()
    {
        $model = new InvoiceSubmission();
        
        if($model->load(Yii::$app->request->post()) 
            && $model->validate(['pin', 'lastName']) 
            && $model->save())
        {
            $model->fkPersonID = $this->matchPerson($model);
            if($model->fkPersonID)  // Matched person, save and go on
            {
                $model->save();
                return $this->redirect(['event']);
            }
            else                    // No match, set flag and fall back to index
                Yii::$app->session->set('nf', '1');
        }
    
        return $this->render('index', [
            'title' => $this->title,
            'model' => $model,
        ]);
    }
    
    public function actionEvent()
    {
        $model = new InvoiceSubmission();
        $model->loadFromSession();

        if($model->load(Yii::$app->request->post()) 
            && $model->validate('fkEventID') 
            && $model->save())
        {
            return $this->redirect(['detail']);
        }
        
        // Get the name from the person record
        $person = Person::findOne($model->fkPersonID);
        
        // Find uninvoiced past events to which this person is assigned
        // Need for use of GROUP_CONCAT makes this challenging to do with
        // higher level database objects, at least for me. :-)

        $eventSQL =             
            "SELECT E.pkEventID, CONCAT(E.eventDate, '  ', GROUP_CONCAT(ET.eventType ORDER BY ET.eventType SEPARATOR ' + '), ' @ ', O.name, ', ', O.city) AS Label"
            . " FROM event E"
            . " INNER JOIN organization O ON O.pkOrgID = E.fkOrgID"
            . " INNER JOIN staffing S ON S.fkEventID = E.pkEventID"
            . " INNER JOIN person P ON P.pkPersonID = S.fkPersonID"
            . " INNER JOIN event_event_type EET ON EET.fkEventID = E.pkEventID"
            . " INNER JOIN event_type ET ON ET.pkEventTypeID = EET.fkEventTypeID"
            . " LEFT JOIN invoice I ON I.fkEventID = E.pkEventID"
            . " WHERE I.pkInvoiceID IS NULL AND E.eventDate IS NOT NULL AND E.eventDate < NOW()"
            . "   AND P.pkPersonID = {$model->fkPersonID}"
            . " GROUP BY  E.pkEventID, E.eventDate, O.name, O.city"
            . " ORDER BY E.eventDate";        
        $events = Yii::$app->db->createCommand($eventSQL)->queryAll();
                
        // Remap from sequential array of associative arrays to single associative array
        $eventDDL = array();
        foreach($events as $event) {
            $eventDDL[$event['pkEventID']] = $event['Label'];
        }
        
        return $this->render('event', [
            'title' => $this->title,
            'model' => $model,
            'person' => "{$person->firstName} {$person->lastName}",
            //'sql' => $eventSQL, // for debugging
            'eventDDL' => $eventDDL,
        ]); 
    }

    public function actionDetail()
    {    
        $model = new InvoiceSubmission();
        $model->loadFromSession();

        if($model->load(Yii::$app->request->post()) 
            && $model->validate('atSchool', 'topics', 'presentations',
                    'participants', 'hours', 'miles') 
            && $model->save())
        {
            return $this->redirect(['confirm']);
        }
        
        $event = Event::findOne($model->fkEventID);
        $model->miles = 0;
                    
        $rates = Rate::find()
                ->select(["CONCAT('$', rate, '/hr  ', description)"]) // 
                ->indexBy('pkRateID')
                ->where('isActive <> 0')
                ->column();
        
        // Get mileage in effect on event date
        $mileage = (Mileage::findOne($event->eventDate))->rate;
        
        return $this->render('detail', [
            'title' => $this->title,
            'model' => $model,
            'display' => $this->displayEventDetails($event),
            'rates' => $rates,
            'mileage' => $mileage,
        ]);
    }
    
    public function actionConfirm()
    {
        $model = new InvoiceSubmission();
        $model->loadFromSession();
        
        if($model->load(Yii::$app->request->post()) 
            && $model->validate('accurate', 'email') 
            && $model->save())
        {
            // Check accurate email address
            $person = Person::findOne($model->fkPersonID);
            if($person->email == $model->email) {

                $event = Event::findOne($model->fkEventID);
                $rate = Rate::findOne($model->fkRateID);
                $mileage = (Mileage::findOne($event->eventDate))->rate;
                
                // Set and save the invoice record

                $invoice = new Invoice();
                $invoice->invoiceDate = (new \DateTime())->format('Y-m-d H:i:s');
                $invoice->fkPersonID = $model->fkPersonID;
                $invoice->fkEventID = $model->fkEventID;
                $invoice->isSchool = $model->atSchool;
                $invoice->isBike = ($model->topics == 'B' || $model->topics == 'BP');
                $invoice->isPed = ($model->topics == 'P' || $model->topics == 'BP');
                $invoice->presentations = $model->presentations;
                $invoice->presentees = $model->participants;
                $invoice->hours = $model->hours;
                $invoice->fkRateRequested = $model->fkRateID;
                $invoice->miles = $model->miles;
                $invoice->invoiceAmount = ($model->hours * $rate->rate + $model->miles * $mileage);
                $invoice->submitterComments = $model->comments;
                
                if($invoice->save()) {

                    // Time to send the email
                    
                    // Fill in To/CC from config
                    $to = array();
                    $cc = array();
                    (Config::findOne('InvSubEmailRecips'))->assignEmailRecips($to, $cc);

                    // Also CC submitting instructor
                    $instName = "{$invoice->instructor->firstName} {$invoice->instructor->lastName}";
                    $cc[$model->email] = $instName;

                    // Set subject from config
                    $subject = (Config::findOne('InvSubEmailSubject'))
                            ->substituteParams(
                                    $instName,
                                    "{$event->eventTypeString} at {$event->organization->name}"
                                    );
                    
                    // Email body is basically just the event and invoice grids
                    $body = "<p>A new invoice has been submitted from $instName.</p>"
                        . $this->displayEventDetails($event)
                        . $this->displayInvoiceDetails($model, $event);

                    // Create and send the message
                                    
                    $message = Yii::$app->mailer->compose()
                        ->setFrom('donotreply@bikemaine.org')
                        ->setTo($to)
                        ->setCC($cc)
                        ->setSubject($subject)
                        ->setHtmlBody($body)
                        ->send();
                    
                    return $this->redirect(['complete']);
                }
                else
                    Yii::$app->session->set('ns', '1');
            }
            else // email does not match, set flag and fall back to confirmation
                Yii::$app->session->set('nf', '1');
        }
        
        $event = Event::findOne($model->fkEventID);
        
        return $this->render('confirm', [
            'title' => $this->title,
            'model' => $model,
            'displayEvent' => $this->displayEventDetails($event),
            'displayInvoice' => $this->displayInvoiceDetails($model, $event),
        ]);
    }
    
    public function actionComplete()
    {
        $model = new InvoiceSubmission();
        $model->loadFromSession();
        $event = Event::findOne($model->fkEventID);

        return $this->render('complete', [
            'title' => $this->title,
            'model' => $model,
            'displayEvent' => $this->displayEventDetails($event),
            'displayInvoice' => $this->displayInvoiceDetails($model, $event),
        ]);
    }
    
    private function displayEventDetails($event)
    {
        // TO DO: Get title of event contact person from org contact table
        $table = Yii::$app->globals->formatAsHTMLTable(
            [
                'eventTypes' => $event->eventTypeString,
                'eventDate' => $event->eventDate,
                'orgName' => $event->organization->name,
                'eventAddress' => "{$event->address1} {$event->address2}",
                'eventCityStateZip' => "{$event->city}, {$event->state}  {$event->zipcode}",
                'eventCounty' => $event->county,
                'contactName' => "{$event->contact->firstName} {$event->contact->lastName}",
                'contactEmail' => $event->contact->email,
                'contactPhone' => $event->contact->phone,
                'ages' => $event->eventAge->eventAge,
                'otherType' => $event->otherType,
                'presentations' => $event->presentations,
                'participation' => $event->participation,
            ],
            [
                'eventTypes' => 'Event Type(s)',
                'eventDate' => 'Event Date',
                'orgName' => 'Organization',
                'eventAddress' => 'Street Address of Event',
                'eventCityStateZip' => 'City, State, Zip',
                'eventCounty' => 'County',
                'contactName' => 'Contact Person',
                'contactEmail' => 'Contact Email',
                'contactPhone' => 'Contact Phone',
                'ages' => 'Age Group(s)',
                'otherType' => 'Other event type',
                'presentations' => 'Estimated presentations',
                'participation' => 'Estimated participants',
            ],
            'invoice-sub'   // table class name
        );
        return "<div class='padded'>$table</div>";
    }
    
    private function displayInvoiceDetails($model, $event)
    {
        $baseRate = (Rate::findOne($model->fkRateID))->rate;
        $mileage = (Mileage::findOne($event->eventDate))->rate;
        $table = Yii::$app->globals->formatAsHTMLTable(
            [
                'presentations' => $model->presentations,
                'participants' => $model->participants,
                'topics' => ($model->topics == 'B' 
                        ? 'Bike only' 
                        : ($model->topics == 'P' 
                            ? 'Pedestrian Only' 
                            : 'Both Bike/Ped')),
                'atSchool' => $model->atSchool ? 'Yes' : 'No',
                'hours' => $model->hours,
                'rate' => sprintf('$%4.2f', $baseRate), // 30
                'compensation' => sprintf('$%4.2f', $model->hours * $baseRate),
                'miles' => $model->miles,
                'mileage_comp' => sprintf('$%4.2f', $model->miles * $mileage),
                'total' => sprintf('$%4.2f', $model->hours * $baseRate + $model->miles * $mileage),
                'comments' => $model->comments,
            ],
            [
                'presentations' => 'Number of Presentations',
                'participants' => 'Number Presented To',
                'topics' => 'Topics covered',
                'atSchool' => 'Event at a school?',
                'hours' => 'Hours Presenting',
                'rate' => 'Rate Requested',                
                'compensation' => 'Compensation (hours * rate)',
                'miles' => 'Miles Traveled',
                'mileage_comp' => "Mileage Compensation (@ \$$mileage/mi)",
                'total' => 'Total Amount Invoiced',
                'comments' => 'Comments',
            ],
            'invoice-sub'   // table class name
        );
        return "<div class='padded'>$table</div>";
    }
}
