<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "instructor_availability".
 *
 * @property int $pkInstructorAvailability
 * @property int $sequence
 * @property string $instructorAvailability
 * @property bool $isActive
 *
 * @property InstructorInfoAvailable[] $instructorInfoAvailables
 * @property InstructorInfo[] $fkInstructorInfos
 */
class InstructorAvailability extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instructor_availability';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sequence'], 'integer'],
            [['instructorAvailability'], 'required'],
            [['isActive'], 'boolean'],
            [['instructorAvailability'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkInstructorAvailability' => 'ID',
            'sequence' => 'Sequence',
            'instructorAvailability' => 'Instructor Availability',
            'isActive' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructorInfoAvailables()
    {
        return $this->hasMany(InstructorInfoAvailable::className(), ['fkInstructorAvailable' => 'pkInstructorAvailability']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkInstructorInfos()
    {
        return $this->hasMany(InstructorInfo::className(), ['pkInstructorInfo' => 'fkInstructorInfo'])->viaTable('instructor_info_available', ['fkInstructorAvailable' => 'pkInstructorAvailability']);
    }
}
