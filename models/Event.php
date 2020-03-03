<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property int $pkEventID
 * @property string $requestDateTime
 * @property int $fkOrgID
 * @property bool $isAtOrgAddress
 * @property bool $isSchool
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $zipcode
 * @property string $county
 * @property int $fkPersonID
 * @property string $otherType
 * @property int $fkEventAgeID
 * @property string $ageDescription
 * @property string $need
 * @property string $participation
 * @property string $datetimes
 * @property string $presentations
 * @property int $fkPastInstructor
 * @property string $comments
 * @property string $notes
 * @property string $eventDate
 * @property string $startTime
 * @property string $endTime
 * @property bool $isBike
 * @property bool $isPed
 * @property int $fkInstructor 
 * @property int $participation_actual
 * @property int $presentations_actual
 *
 * @property Person $fkInstructor0 
 * @property EventAge $fkEventAge
 * @property Organization $fkOrg
 * @property Person $fkPerson
 * @property EventEventType[] $eventEventTypes
 * @property EventType[] $fkEventTypes
 * @property Invoice[] $invoices
 * @property Staffing[] $staffings
 * @property Person[] $fkPeople
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            // Required fields
            
            [['requestDateTime', 'fkOrgID', 'address1', 'city', 'state', 'zipcode', 'county', 'fkPersonID'], 'required'],

            // Basic type validations
            
            [['fkOrgID', 'fkPersonID', 'fkEventAgeID', 'fkPastInstructor', 'participation_actual', 'presentations_actual'], 'integer'],
            [['isAtOrgAddress', 'isBike', 'isPed', 'isSchool'],             'boolean'],
            
            [['address1', 'address2', 'city'],                              'string', 'max' => 40],
            [['state'],                                                     'string', 'max' => 2],
            [['zipcode'],                                                   'string', 'max' => 10],
            [['county'],                                                    'string', 'max' => 30],
            [['otherType', 'ageDescription'],                               'string', 'max' => 200],
            [['need'],                                                      'string', 'max' => 1500],
            [['participation', 'datetimes', 'presentations'],               'string', 'max' => 500],
            [['notes'],                                                     'string'],
            [['comments'],                                                  'string', 'max' => 2500],

            // Date/Time rules
            
            [['eventDate'], 'filter', 'filter' => function($value) { return $value ? date('Y-m-d', strtotime($value)) : null; }],
            [['eventDate'], 'date', 'format' => 'yyyy-MM-dd'],
            
            [['startTime'], 'filter', 'filter' => function($value) { return $value ? date('H:i:00', strtotime($value)) : null; }],
            [['startTime'], 'time', 'format' => 'H:i:s'],

            [['endTime'], 'filter', 'filter' => function($value) { return $value ? date('H:i:00', strtotime($value)) : null; }],
            [['endTime'],   'time', 'format' => 'H:i:s'],        
            
            [['fkEventAgeID'], 'exist', 'skipOnError' => true, 'targetClass' => EventAge::className(), 'targetAttribute' => ['fkEventAgeID' => 'pkEventAgeID']],
            [['fkOrgID'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['fkOrgID' => 'pkOrgID']],
            [['fkPersonID'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['fkPersonID' => 'pkPersonID']],
        
	];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkEventID' => 'Event ID',
            'requestDateTime' => 'Request Date Time',
            'fkOrgID' => 'Organization',
            'address1' => 'Address Line 1',
            'address2' => 'Address Line 2',
            'city' => 'City',
            'state' => 'State',
            'zipcode' => 'Zipcode',
            'county' => 'County',
            'isAtOrgAddress' => 'On premises?',
            'fkPersonID' => 'Event Contact',
            'otherType' => 'Other Type',
            'fkEventAgeID' => 'Event Age Group',
            'ageDescription' => 'Age Description',
            'need' => 'Need',
            'participation' => 'Estimated Participation',
            'datetimes' => 'Dates/Times',
            'presentations' => 'Estimated Presentations',
            'fkPastInstructor' => 'Past Instructor',
            'fkInstructorId' => 'Instructor',
            'comments' => 'Requester Comments',
            'notes' => 'Administrative Notes',
            'eventDate' => 'Event Date',
            'startTime' => 'Start Time',
            'endTime' => 'End Time',
            'isBike' => 'Is Bike',
            'isPed' => 'Is Ped',
            'isSchool' => 'At a School?',
            'presentations_actual' => 'Presentations',
            'participation_actual' => 'Participants',
        ];
    }	
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventAge()
    {
        return $this->hasOne(EventAge::className(), ['pkEventAgeID' => 'fkEventAgeID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['pkOrgID' => 'fkOrgID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Person::className(), ['pkPersonID' => 'fkPersonID']);
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	 public function getPastInstructor()
	{
		return $this->hasOne(Person::className(), ['pkPersonID' => 'fkPastInstructor']);
	}
	 
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventEventTypes()
    {
        return $this->hasMany(EventEventType::className(), ['fkEventID' => 'pkEventID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventTypes()
    {
        return $this->hasMany(EventType::className(), ['pkEventTypeID' => 'fkEventTypeID'])->viaTable('event_event_type', ['fkEventID' => 'pkEventID']);
    }

    /**
     * @return string
     */
    public function getEventTypeString()
    {
        return implode( ' + ', array_map(
                                function($row) { return $row->eventType; },
                                $this->eventTypes
                              )
                      );        
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['fkEventID' => 'pkEventID']);
    }

    /**
     * @return string
     */
    public function getInvoiceString()
    {
        $returnStrings = array();
        foreach($this->invoices as $invoice)
        {
            $urlInv = Yii::$app->urlManager->createURL(['invoice/view', 'id' => $invoice->pkInvoiceID]);
            $personName = $invoice->instructor->firstName . ' ' . $invoice->instructor->lastName;
            $urlInst = Yii::$app->urlManager->createURL(['person/view', 'id' => $invoice->instructor->pkPersonID]);
            $returnStrings[] = "#<a href='$urlInv'>{$invoice->pkInvoiceID}</a> (<a href='$urlInst'>{$personName}</a>)";
        }
        return implode(', ', $returnStrings);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffing()
    {
        return $this->hasMany(Staffing::className(), ['fkEventID' => 'pkEventID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffPeople()
    {
        return $this->hasMany(Person::className(), ['pkPersonID' => 'fkPersonID'])->viaTable('staffing', ['fkEventID' => 'pkEventID']);
    }
    
    public function getStaffingString()
    {
        $returnStrings = array();
        foreach($this->staffPeople as $staffing)
        {
            $url = Yii::$app->urlManager->createURL(['person/view', 'id' => $staffing->pkPersonID]);
            $returnStrings[] = "<a href='$url'>{$staffing->firstName} {$staffing->lastName}</a>";
        }
        return implode(', ', $returnStrings);        
    }
    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getFkPeople()
//    {
//        return $this->hasMany(Person::className(), ['pkPersonID' => 'fkPersonID'])->viaTable('staffing', ['fkEventID' => 'pkEventID']);
//    }
}
