<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property int $pkInvoiceID
 * @property int $fkEventID
 * @property int $fkPersonID
 * @property string $invoiceDate
 * @property int $hours
 * @property string $hourlyrate
 * @property int $presentations
 * @property int $presentees
 * @property int $miles
 * @property string $milesPurpose
 * @property bool $isBike
 * @property bool $isPed
 * @property bool $isSchool
 * @property string $invoiceAmount
 * @property string $approveDate
 * @property int $fkApproverID
 * @property int $fkRateRequested
 * @property string $submitterComments
 * @property string $approverComments
 *
 * @property Event $fkEvent
 * @property Person $fkPerson
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
 	    [['fkEventID', 'fkPersonID', 'invoiceDate', 'hours', 'presentations', 'presentees', 'miles', 'invoiceAmount'], 'required'],
            [['fkEventID', 'fkPersonID', 'hours', 'presentations', 'presentees', 'miles', 'fkApproverID', 'fkRateRequested'], 'integer'],
            [['invoiceDate', 'approveDate'], 'safe'],
            [['hourlyrate', 'invoiceAmount'], 'number'],
            [['isBike', 'isPed', 'isSchool'], 'boolean'],
            [['submitterComments', 'approverComments'], 'string'],
            [['milesPurpose'], 'string', 'max' => 100],
            [['fkEventID'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['fkEventID' => 'pkEventID']],
            [['fkPersonID'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['fkPersonID' => 'pkPersonID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkInvoiceID' => 'Invoice ID',
            'fkEventID' => 'Event',
            'fkPersonID' => 'Instructor',
            'invoiceDate' => 'Invoice Received',
            'hours' => 'Hours',
            'hourlyrate' => 'Appr. Hourly Rate',
            'presentations' => 'Presentations',
            'presentees' => 'Presentees',
            'miles' => 'Miles',
            'milesPurpose' => 'Miles Purpose',
            'isBike' => 'Is Bike',
            'isPed' => 'Is Ped',
            'isSchool' => 'At a School?',
            'fkApproverID' => 'Approved By',
            'invoiceAmount' => 'Invoice Amount',
            'approveDate' => 'Approved On',
            'fkRateRequested' => 'Requested Hourly Rate',
            'submitterComments' => 'Submitter Comments',
            'approverComments' => 'Approver Comments',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['pkEventID' => 'fkEventID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateRequested()
    {
        return $this->hasOne(Rate::className(), ['pkRateID' => 'fkRateRequested']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructor()
    {
        return $this->hasOne(Person::className(), ['pkPersonID' => 'fkPersonID']);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprover()
    {
        return $this->hasOne(User::className(), ['pkPersonID' => 'fkApproverID']);
    }
    
    public function getMileageRate()
    {
        return Yii::$app->db->createCommand(
            "SELECT rate
             FROM mileage
             WHERE pkEffDate = 
                (SELECT MAX(pkEffDate) FROM mileage WHERE pkEffDate <= :asOf)"
            )
            ->bindValue(':asOf', $this->invoiceDate)
            ->queryScalar();
    }
    
    public function getPayBase()
    {
        return $this->hours * $this->rateRequested->rate;
    }

    public function getPayMileage()
    {
        return $this->miles * $this->mileageRate;
    }

    public function getPayTotal()
    {
        return $this->payBase + $this->payMileage;
    }
 }
