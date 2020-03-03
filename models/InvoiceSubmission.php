<?php

namespace app\models;

use Yii;
use yii\base\Model;

use app\models\Person;
use app\models\Event;

/**
 * The InvoiceSubmission model is the data basis of the contractor's invoicing
 * form. Unlike most other models here, this one is based directly on Model, 
 * not on ActiveRecord. Between request screens, data is saved in session 
 * variables, not in a database field. Once the request is completed, the
 * information is matched to an existing event and its fields are updated in
 * the database. An email notification is also sent.
 *
 * @author John Brooking
 */

class InvoiceSubmission extends Model {
    
    public $pin, $lastName, $comments, $email, $accurate;
    
    public $atSchool, $topics, $presentations, $participants, $hours, $miles;
    
    public $fkPersonID, $fkEventID, $fkRateID;
    
    // Validation rules
     
    public function rules()
    {
        return [
            // Required fields
            [['pin', 'lastName', 'atSchool', 'topics', 'presentations',
              'participants', 'hours', 'miles', 'accurate', 'email'], 'required'],
            
            // String lengths
            ['pin', 'string', 'max' => 4],
            ['lastName', 'string', 'max' => 40],
            ['topics', 'string', 'max' => 2],
            ['comments', 'string', 'max' => 2000],
            
            // Non-string types
            [['pin', 'presentations', 'participants',
                'fkPersonID', 'fkEventID', 'fkRateID'], 'integer'],
            [['hours', 'miles'], 'double'],
            [['atSchool', 'accurate'], 'boolean'],
            ['email', 'email'],
            
            // Content restrictions
            ['lastName', 'match', 'pattern' => '/^[a-z\s\-\.]+$/i'],
        ];
    }
    
    // Overriding default Model labels
    public function attributeLabels()
    {
        return [
            'pin' => 'PIN',
            'fkEventID' => 'Please choose the event you are invoicing for:',
            'presentations' => 'Total Number of Presentations Made:',
            'participants' => 'Total Number Presented To:',
            'topics' => 'Topics covered:',
            'atSchool' => 'Was this event at a school?',
            'hours' => 'Total Hours Presenting',
            'miles' => 'Miles Traveled',
            'fkRateID' => 'Rate Requested',
            'accurate' => 'The information submitted above is accurate.',
            'email' => 'Email address (as e-signature)',
        ];
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
}
