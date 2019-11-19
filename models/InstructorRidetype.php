<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "instructor_ridetype".
 *
 * @property int $pkInstructorRideType
 * @property string $instructorRideType
 * @property bool $isActive
 *
 * @property InstructorInfoRidetypes[] $instructorInfoRidetypes
 * @property InstructorInfo[] $fkInstructorInfos
 */
class InstructorRidetype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instructor_ridetype';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['instructorRideType'], 'required'],
            [['isActive'], 'boolean'],
            [['instructorRideType'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkInstructorRideType' => 'ID',
            'instructorRideType' => 'Riding Discipline',
            'isActive' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructorInfoRidetypes()
    {
        return $this->hasMany(InstructorInfoRidetypes::className(), ['fkInstructorRideType' => 'pkInstructorRideType']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkInstructorInfos()
    {
        return $this->hasMany(InstructorInfo::className(), ['pkInstructorInfo' => 'fkInstructorInfo'])->viaTable('instructor_info_ridetypes', ['fkInstructorRideType' => 'pkInstructorRideType']);
    }
}
