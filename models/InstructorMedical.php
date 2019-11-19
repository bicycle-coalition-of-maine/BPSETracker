<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "instructor_medical".
 *
 * @property int $pkInstructorMedical
 * @property int $sequence
 * @property string $instructorMedical
 * @property bool $isActive
 *
 * @property InstructorInfo[] $instructorInfos
 */
class InstructorMedical extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instructor_medical';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sequence'], 'integer'],
            [['instructorMedical'], 'required'],
            [['isActive'], 'boolean'],
            [['instructorMedical'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkInstructorMedical' => 'ID',
            'sequence' => 'Sequence',
            'instructorMedical' => 'Instructor Medical',
            'isActive' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructorInfos()
    {
        return $this->hasMany(InstructorInfo::className(), ['fkInstMedical' => 'pkInstructorMedical']);
    }
}
