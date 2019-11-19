<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "instructor_activity".
 *
 * @property int $pkInstructorActivity
 * @property string $instructorActivity
 * @property bool $isActive
 *
 * @property InstructorInfoActivity[] $instructorInfoActivities
 * @property InstructorInfo[] $fkInstructorInfos
 */
class InstructorActivity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instructor_activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['instructorActivity'], 'required'],
            [['isActive'], 'boolean'],
            [['instructorActivity'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkInstructorActivity' => 'ID',
            'instructorActivity' => 'Instruction Type',
            'isActive' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructorInfoActivities()
    {
        return $this->hasMany(InstructorInfoActivity::className(), ['fkInstructorActivity' => 'pkInstructorActivity']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkInstructorInfos()
    {
        return $this->hasMany(InstructorInfo::className(), ['pkInstructorInfo' => 'fkInstructorInfo'])->viaTable('instructor_info_activity', ['fkInstructorActivity' => 'pkInstructorActivity']);
    }
}
