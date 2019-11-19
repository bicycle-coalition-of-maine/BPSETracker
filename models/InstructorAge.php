<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "instructor_age".
 *
 * @property int $pkInstructorAgeGroup
 * @property int $sequence
 * @property string $instructorAgeGroup
 * @property bool $isActive
 *
 * @property InstructorInfoAges[] $instructorInfoAges
 * @property InstructorInfo[] $fkInstructorInfos
 */
class InstructorAge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instructor_age';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sequence'], 'integer'],
            [['instructorAgeGroup'], 'required'],
            [['isActive'], 'boolean'],
            [['instructorAgeGroup'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkInstructorAgeGroup' => 'ID',
            'sequence' => 'Sequence',
            'instructorAgeGroup' => 'Age Group',
            'isActive' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructorInfoAges()
    {
        return $this->hasMany(InstructorInfoAges::className(), ['fkInstructorAgeGroup' => 'pkInstructorAgeGroup']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkInstructorInfos()
    {
        return $this->hasMany(InstructorInfo::className(), ['pkInstructorInfo' => 'fkInstructorInfo'])->viaTable('instructor_info_ages', ['fkInstructorAgeGroup' => 'pkInstructorAgeGroup']);
    }
}
