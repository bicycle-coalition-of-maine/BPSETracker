<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organization".
 *
 * @property int $pkOrgID
 * @property string $name
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $zipcode
 * @property string $county
 *
 * @property Contact[] $contacts
 * @property Person[] $fkPeople
 * @property Event[] $events
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organization';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 120],
            [['address1', 'address2', 'city'], 'string', 'max' => 40],
            [['state'], 'string', 'max' => 2],
            [['zipcode'], 'string', 'max' => 10],
            [['county'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkOrgID' => 'Organization ID',
            'name' => 'Name',
            'address1' => 'Address Line 1',
            'address2' => 'Address Line 2',
            'city' => 'City',
            'state' => 'State',
            'zipcode' => 'Zipcode',
            'county' => 'County',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getContacts()
//    {
//        return $this->hasMany(Contact::className(), ['fkOrgID' => 'pkOrgID']);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeople()
    {
        return $this->hasMany(Person::className(), ['pkPersonID' => 'fkPersonID'])->viaTable('contact', ['fkOrgID' => 'pkOrgID'])->orderBy('lastName, firstName');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['fkOrgID' => 'pkOrgID'])
                ->orderBy('eventDate DESC, requestDateTime DESC');
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventTypes()
    {
        return $this->hasMany(EventType::className(), ['fkOrgID' => 'pkOrgID'])
                ->viaTable('event_event_type', ['fkEventTypeID' => 'pkEventType']);
    }
	
    public function zipCodeKeepZeros()
    {
            return $this->zipcode;
    }
}
