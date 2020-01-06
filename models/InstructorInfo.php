<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "instructor_info".
 *
 * @property int $pkInstructorInfo
 * @property int $fkPersonID
 * @property int $year
 * @property int $fkInstStatus
 * @property string $availability_other
 * @property int $fkInstLCI
 * @property int $fkInstMechanical
 * @property int $fkInstMedical
 * @property string $ridetype_other
 * @property bool $isLargeGroupOK
 * @property bool $isDirectContactOK
 * @property string $comments
 *
 * @property InstructorLci $fkInstLCI0
 * @property Person $fkPerson
 * @property InstructorStatus $fkInstStatus0
 * @property InstructorMechanical $fkInstMechanical0
 * @property InstructorMedical $fkInstMedical0
 * @property InstructorInfoActivity[] $instructorInfoActivities
 * @property InstructorActivity[] $fkInstructorActivities
 * @property InstructorInfoAges[] $instructorInfoAges
 * @property InstructorAge[] $fkInstructorAgeGroups
 * @property InstructorInfoAvailable[] $instructorInfoAvailables
 * @property InstructorAvailability[] $fkInstructorAvailables
 * @property InstructorInfoRidetypes[] $instructorInfoRidetypes
 * @property InstructorRidetype[] $fkInstructorRideTypes
 */
class InstructorInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instructor_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fkPersonID', 'year', 'fkInstMechanical', 'fkInstMedical'], 'required'],
            [['fkPersonID', 'year', 'fkInstStatus', 'fkInstLCI', 'fkInstMechanical', 'fkInstMedical'], 'integer'],
            [['isLargeGroupOK', 'isDirectContactOK'], 'boolean'],
            [['availability_other', 'ridetype_other'], 'string', 'max' => 120],
            [['comments'], 'string', 'max' => 2000],
            [['fkInstLCI'], 'exist', 'skipOnError' => true, 'targetClass' => InstructorLci::className(), 'targetAttribute' => ['fkInstLCI' => 'pkInstructorLCI']],
            [['fkPersonID'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['fkPersonID' => 'pkPersonID']],
            [['fkInstStatus'], 'exist', 'skipOnError' => true, 'targetClass' => InstructorStatus::className(), 'targetAttribute' => ['fkInstStatus' => 'pkInstructorStatus']],
            [['fkInstMechanical'], 'exist', 'skipOnError' => true, 'targetClass' => InstructorMechanical::className(), 'targetAttribute' => ['fkInstMechanical' => 'pkInstructorMechanical']],
            [['fkInstMedical'], 'exist', 'skipOnError' => true, 'targetClass' => InstructorMedical::className(), 'targetAttribute' => ['fkInstMedical' => 'pkInstructorMedical']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkInstructorInfo' => 'Instructor Info ID',
            'fkPersonID' => 'Person ID',
            'year' => 'Year',
            'fkInstStatus' => 'General Status',
            'availability_other' => 'Other',
            'fkInstLCI' => 'LCI Status',
            'fkInstMechanical' => 'Mechanical Ability',
            'fkInstMedical' => 'Medical Ability',
            'ridetype_other' => 'Other',
            'isLargeGroupOK' => 'Are Large Groups OK?',
            'isDirectContactOK' => 'Is Direct Contact OK?',
            'comments' => 'Comments',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLci()
    {
        return $this->hasOne(InstructorLci::className(), ['pkInstructorLCI' => 'fkInstLCI']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['pkPersonID' => 'fkPersonID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(InstructorStatus::className(), ['pkInstructorStatus' => 'fkInstStatus']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMechanical()
    {
        return $this->hasOne(InstructorMechanical::className(), ['pkInstructorMechanical' => 'fkInstMechanical']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedical()
    {
        return $this->hasOne(InstructorMedical::className(), ['pkInstructorMedical' => 'fkInstMedical']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getInstructorInfoActivities()
//    {
//        return $this->hasMany(InstructorInfoActivity::className(), ['fkInstructorInfo' => 'pkInstructorInfo']);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivities()
    {
        return $this->hasMany(InstructorActivity::className(), ['pkInstructorActivity' => 'fkInstructorActivity'])->viaTable('instructor_info_activity', ['fkInstructorInfo' => 'pkInstructorInfo']);
    }
    
    /**
     * @return string
     */
    public function getActivitiesString()
    {
        return implode( '; ', array_map(
                                function($row) { return $row->instructorActivity; },
                                $this->activities
                              )
                      );
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getInstructorInfoAges()
//    {
//        return $this->hasMany(InstructorInfoAges::className(), ['fkInstructorInfo' => 'pkInstructorInfo']);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgeGroups()
    {
        return $this->hasMany(InstructorAge::className(), ['pkInstructorAgeGroup' => 'fkInstructorAgeGroup'])->viaTable('instructor_info_ages', ['fkInstructorInfo' => 'pkInstructorInfo']);
    }

    /**
     * @return string
     */
    public function getAgeStrings()
    {
        return implode( '; ', array_map(
                                function($row) { return $row->instructorAgeGroup; },
                                $this->ageGroups
                              )
                      );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getInstructorInfoAvailables()
//    {
//        return $this->hasMany(InstructorInfoAvailable::className(), ['fkInstructorInfo' => 'pkInstructorInfo']);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAvailability()
    {
        return $this->hasMany(InstructorAvailability::className(), ['pkInstructorAvailability' => 'fkInstructorAvailable'])->viaTable('instructor_info_available', ['fkInstructorInfo' => 'pkInstructorInfo']);
    }

    /**
     * @return string
     */
    public function getAvailabilityString()
    {
        return implode( '; ', array_map(
                                function($row) { return $row->instructorAvailability; },
                                $this->availability
                              )
                      );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getInstructorInfoRidetypes()
//    {
//        return $this->hasMany(InstructorInfoRidetypes::className(), ['fkInstructorInfo' => 'pkInstructorInfo']);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRideTypes()
    {
        return $this->hasMany(InstructorRidetype::className(), ['pkInstructorRideType' => 'fkInstructorRideType'])->viaTable('instructor_info_ridetypes', ['fkInstructorInfo' => 'pkInstructorInfo']);
    }
    
    /**
     * @return string
     */
    public function getRideTypeString()
    {
        return implode( '; ', array_map(
                                function($row) { return $row->instructorRideType; },
                                $this->rideTypes
                              )
                      );
    }

}
