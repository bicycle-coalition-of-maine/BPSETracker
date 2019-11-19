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
            [['firstName', 'lastName', 'email', 'phone'], 'required'],
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
        //return $this->hasMany(Event::className(), ['fkPersonID' => 'pkPersonID']);
        return $this->hasMany(Event::className(), ['pkEventID' => 'fkEventID'])->viaTable('staffing', ['fkPersonID' => 'pkPersonID']);

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructorInfos()
    {
        return $this->hasMany(InstructorInfo::className(), ['fkPersonID' => 'pkPersonID']);
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
     * @return \yii\db\ActiveQuery
     */
//    public static function getInstructorsCounties( $county = '' )
//    {
//        $q = new ActiveQuery()
//        return $this->leftJoin( 'city_county CC', 'CC.city = person.city' )
//                    ->where( "isStaff = b'1'" )
//                    ->select( [ 'CC.county',
//                                'person.city',
//                                'lastName',
//                                'firstName',
//                                "IF( C.county = '', 0, IF( IFNULL( C.county, '' ) = '', 2, 1 )) AS isSameCounty",
//                              ]
//                            )
//                    ->orderBy( '5, 1, 2, 3, 4' );
//    }
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
	 
     
     // ===============================================================
     // IdentityInterface methods
     // ===============================================================
     
    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
//    public static function findIdentity($id)
//    {
//        return Person::find()->where("pkPersonID = $id AND isAdmin = b'1'")->one();
//    }
//    
//    /**
//     * Finds an identity by the given token.
//     *
//     * @param string $token the token to be looked for
//     * @return IdentityInterface|null the identity object that matches the given token.
//     */
//    public static function findIdentityByAccessToken($token, $type = null)
//    {
//        // Not needed in this implementation.
//    }
//    
//    /**
//     * @return int|string current user ID
//     */
//    public function getId()
//    {
//        return $this->id;
//    }
//    
//    /**
//     * @return string current user auth key
//     */
//    public function getAuthKey()
//    {
//        // Not needed in this implementation.
//    }
//
//    /**
//     * @param string $authKey
//     * @return bool if auth key is valid for current user
//     */
//    public function validateAuthKey($authKey)
//    {
//        // Not needed in this implementation.
//    }
//    
//    /**
//     * Validates password
//     *
//     * @param string $password password to validate
//     * @return bool if password provided is valid for current user
//     * 
//     * This is not part of the IdentityInterface interface, but related
//     * 
//     */
//    public function validatePassword($password)
//    {
//        return $this->password === $password;
//    }
}
