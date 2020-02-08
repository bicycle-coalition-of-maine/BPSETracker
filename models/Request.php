<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\Session;

use app\models\Event;
use app\models\EventType;
use app\models\Organization;
use app\models\Person;

/**
 * The Request model is the data basis of the public request form. Unlike
 * most other models here, this one is based directly on Model, not on
 * ActiveRecord. Between request screens, data is saved in session variables,
 * not in a database field. Once the request is completed, a new Event record
 * is created and saved to the database, along with Person or Organization
 * if they are also new.
 *
 * @author John Brooking
 */

class Request extends Model {

    // Contact person details
    public $firstName, $lastName, $email, $phone, $phoneExt, $title;
    
    // Organization details
    public $orgName, $orgAddress, $orgCity, $orgZip, $orgCounty, $isAtOrgAddress;
    
    // Event location details
    public $eventAddress, $eventCity, $eventZip, $eventCounty;
    
    // Event types array
    public $eventTypes = array();
    public $otherType;
    
    // Event attributes
    public $need, $estPresentations, $estParticipants, $fkEventAgeID, $ageDesc,
           $proposedDates, $additionalInfo; //, $hasHosted;
    
    // Foreign keys
    public $fkOrgID, $fkContactID;
    
    // Other fields
    public $pkEventID;      // Primary key of the created event
    public $copyMe;         // Copy requester on notification email

    // Overriding default Model labels
    
    public function attributeLabels()
    {
        return [
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'email' => 'Email Address',
            'phone' => 'Phone',
            'phoneExt' => 'Ext.',
            'title' => 'Your position (e.g. Teacher, Nurse, Office Manager)',
            'orgName' => 'Organization / School Name',
            'orgAddress' => 'Organization Mailing Address',
            'orgCity' => 'City',
            'orgZip' => 'Zip Code',
            'orgCounty' => 'County',
            'isAtOrgAddress' => 'Will the event take place at this address?',
            'eventAddress' => 'Street Address',
            'eventCity' => 'City',
            'eventZip' => 'Zip Code',
            'eventCounty' => 'County',
            'need' => 'Define the specific need for this request',
            'estPresentations' => 'Estimated Number of Presentations',
            'estParticipants' => 'Estimated Number of Participants (total)',
            'fkEventAgeID' => 'General Age Groups / Grades',
            'ageDesc' => 'Specific Age Groups / Grades (e.g., kindergarten, grades 4-6, adults, all ages, adults, seniors)',
            'proposedDates' => 'What date(s) and time(s) are available for this event?',
            'additionalInfo' => 'Additional Information',
            'fkOrgID' => 'Organization',
            'eventTypes' => 'Type of event', // Used by formatAsHTMLTable only
            'copyMe' => 'Email me a copy of this request',
        ];
    }
    
    // Optional additional text that is displayed with some questions
    
    public function attributeNotes()
    {
        return [
            'need' => 'E.g., Students have demonstrated unsafe walking / cycling practices, there has been an accident, dangerous infrastructure exists near the school, encourage more people to ride/walk, ***Be specific when possible to show why people NEED this safety training***',
            'estPresentations' => 'Due to the interactive nature of the presentations, we recommend presenting to groups of similar age numbering no more than 45 per presentation (20-30 is optimal, like a class or a scout troop etc.). Please estimate the number of groups/classrooms of that size needing a presentation. If you are considering an assembly-type event, please provide details of your event in the "Additional Info" field below.',
            'estParticipants' => 'Due to the interactive nature of the presentations, we strongly recommend that student groups of more than 45 be broken into smaller units of similar age. If you are considering an assembly-type event, please provide details of your event in the "Additional Info" field below.',
            'ageDesc' => 'E.g., kindergarten, grades 4-6, adults, all ages, adults, seniors',
            'proposedDates' => 'Please provide info on when you need program assistance. Be as specific as you can. Note that large groups (eg entire schools) may need multiple visits by program representatives. FINAL SCHEDULING IS DEPENDENT ON INSTRUCTOR AVAILABILITY, but we will make every effort to accommodate your preferred date/time.',
            'additionalInfo' => 'Any other info you want to include or questions you need to ask. To request additional info about our helmet program. If you\'re thinking of an assembly or other atypical event, please provide details here.',
        ];
    }
    
    // Validation rules
    
    public function rules()
    {
        return [
            // Required fields
            [[  'firstName', 'lastName', 'email', 'phone', 'title',
                'orgName', 'orgAddress', 'orgCity', 'orgZip',
                'eventAddress', 'eventCity', 'eventZip',
                'need', 'estPresentations', 'estParticipants', 'proposedDates',
              ], 'required'],
            
            // integers
            [['fkOrgID', 'fkContactID', 'fkEventAgeID'], 'integer'],
            
            // String lengths
            ['phoneExt', 'string', 'max' => 4],
            [['orgZip', 'eventZip'], 'string', 'max' => 10],
            [['firstName', 'phone'], 'string', 'max' => 20],
            [['orgCounty', 'eventCounty'], 'string', 'max' => 30],
            [['lastName', 'orgAddress', 'orgCity', 'eventAddress', 'eventCity'], 'string', 'max' => 40],
            ['email', 'string', 'max' => 80],
            ['orgName', 'string', 'max' => 120],
            [['otherType', 'ageDesc'], 'string', 'max' => 200 ],
            [['estPresentations', 'estParticipants', 'proposedDates'], 'string', 'max' => 500 ],
            ['need', 'string', 'max' => 1500 ],
            ['additionalInfo', 'string', 'max' => 2500 ],
            
            // Content restrictions
            [['firstName', 'lastName'], 'match', 'pattern' => '/^[a-z\s\-]+$/i' ],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^[\d\(\)\s\+\-\.]{7,17}+$/'],
            ['phone', 'filter', 'filter' => function($value) {
                $value = preg_replace('/[^\d]/', '', $value);
                if(substr($value, 0, 1) == '1')
                    $value = substr($value, 1); // Strip any leading 1
                if(strlen($value) == 7)
                    $value = '207' . $value;    // default to Maine area code
                return $value;
            }],
            ['phoneExt', 'integer'],
            [['orgCounty', 'eventCounty'], 'match', 'pattern' => '/^[a-z]+$/i'],
            [['orgZip', 'eventZip'], 'match', 'pattern' => '/^[\d\-]{5,10}$/'],
            [['isAtOrgAddress', 'copyMe'], 'boolean']
        ];
    }
    
    // A save function that saves to session variables instead of database
    
    public function save()
    {
        $session = Yii::$app->session;
        $session->open();
        foreach ($this as $attr => $value) {
            $session->set($attr, $value);
        }
        return true;
    }
    
    // Function to reload the model from session variables
    
    public function loadFromSession()
    {
        $session = Yii::$app->session;
        $session->open();
        foreach ($this as $attr => $value) {
            $this->$attr = $session->get($attr);
        }        
    }
    
    // functions to mask personal information
    
    public function MaskedEmail($plainEmail = null)
    {
        $email = ($plainEmail ? $plainEmail : $this->email);
        return substr($email, 0, 4) . '*****' . substr($email, -7, 7);
    }
    
    public function MaskedPhone($plainPhone = null)
    {
        $phone = ($plainPhone ? $plainPhone : $this->phone);
        return '(***)***-' . substr($phone, -4, 4);
    }
}
