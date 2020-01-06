<?php

namespace app\models;

use Yii;

use \yii\db\ActiveRecord;
use \yii\web\IdentityInterface;

/**
 * This is the model class for table "person".
 *
 * @property int $pkPersonID
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $phone
 * @property string $phoneExt
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $zipcode
 * @property bool $isStaff
 * @property bool $isContact
 *
 * @property Contact[] $contacts
 * @property Organization[] $fkOrgs
 * @property Event[] $events
 * @property InstructorInfo[] $instructorInfos
 * @property Invoice[] $invoices
 * @property Rate[] $rates
 * @property Staffing[] $staffings
 * @property Event[] $fkEvents
 */

class Person extends ActiveRecord //implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstName', 'lastName'], 'required'],
            [['isStaff', 'isContact', 'isActive', 'isAdmin'], 'boolean'],
            [['firstName', 'phone'], 'string', 'max' => 20],
            [['lastName', 'address1', 'address2', 'city'], 'string', 'max' => 40],
            [['email'], 'string', 'max' => 80],
            [['phoneExt'], 'string', 'max' => 4],
            [['state'], 'string', 'max' => 2],
            [['zipcode'], 'string', 'max' => 10],
            [['county'], 'string', 'max' => 30],
            [['password'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkPersonID' => 'Person ID',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'phoneExt' => 'Phone Ext',
            'address1' => 'Address Line 1',
            'address2' => 'Address Line 2',
            'city' => 'City',
            'state' => 'State',
            'zipcode' => 'Zipcode',
            'county' => 'County',
            'isStaff' => 'Is Instructor?',
            'isContact' => 'Is Contact?',
            'isActive' => 'Is Active?',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * Returns organization records associated with person record through contact
     */
    public function getOrganizations()
    {
        return $this->hasMany(Organization::className(), ['pkOrgID' => 'fkOrgID'])
                ->viaTable('contact', ['fkPersonID' => 'pkPersonID']);
    }

    /**
     * Get contact's title for given organization
     * @return string
     */
    public function getContactTitle($orgID)
    {
            $rec = Contact::find()
                            ->where([ 'fkPersonID' => $this->pkPersonID, 'fkOrgID' => $orgID ])
                            ->select( 'title' )
                            ->scalar();
            return $rec;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['pkEventID' => 'fkEventID'])
                ->viaTable('staffing', ['fkPersonID' => 'pkPersonID'])
                ->orderBy('eventDate DESC, requestDateTime DESC');

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructorInfos()
    {
        return $this->hasMany(InstructorInfo::className(), ['fkPersonID' => 'pkPersonID'])
                ->orderBy('`year` DESC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['fkPersonID' => 'pkPersonID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRates()
    {
        return $this->hasMany(Rate::className(), ['fkPersonID' => 'pkPersonID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaffings()
    {
        return $this->hasMany(Staffing::className(), ['fkPersonID' => 'pkPersonID']);
    }

    /**
     * @return string
     */
     public function formattedPhone( $fmt )
     {
        // Assume phone is string of exactly 10 digits. Ensure this at input time.
        // $fmt = '(' for (xxx)-xxx-xxxx format, otherwise xxx-xxx-xxxx format.
        return ( $fmt == '(' ? '(' . substr( $this->phone, 0, 3 ) . ')'
                                   . substr( $this->phone, 3, 3 ) . '-'
                                   . substr( $this->phone, 6, 4 )
                             :       substr( $this->phone, 0, 3 ) . '-'
                                   . substr( $this->phone, 3, 3 ) . '-'
                                   . substr( $this->phone, 6, 4 )
                          );
     }

}
