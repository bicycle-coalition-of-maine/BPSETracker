<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "instructor_status".
 *
 * @property int $pkInstructorStatus
 * @property string $instructorStatus
 * @property bool $isActive
 *
 * @property InstructorInfo[] $instructorInfos
 */
class InstructorStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instructor_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['instructorStatus'], 'required'],
            [['isActive'], 'boolean'],
            [['instructorStatus'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkInstructorStatus' => 'Pk Instructor Status',
            'instructorStatus' => 'Instructor Status',
            'isActive' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructorInfos()
    {
        return $this->hasMany(InstructorInfo::className(), ['fkInstStatus' => 'pkInstructorStatus']);
    }
}
