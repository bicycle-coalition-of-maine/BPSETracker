<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "instructor_lci".
 *
 * @property int $pkInstructorLCI
 * @property string $instructorLCI
 * @property bool $isActive
 *
 * @property InstructorInfo[] $instructorInfos
 */
class InstructorLci extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instructor_lci';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['instructorLCI'], 'required'],
            [['isActive'], 'boolean'],
            [['instructorLCI'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkInstructorLCI' => 'ID',
            'instructorLCI' => 'LCI Status',
            'isActive' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructorInfos()
    {
        return $this->hasMany(InstructorInfo::className(), ['fkInstLCI' => 'pkInstructorLCI']);
    }
}
