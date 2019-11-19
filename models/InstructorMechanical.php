<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "instructor_mechanical".
 *
 * @property int $pkInstructorMechanical
 * @property int $sequence
 * @property string $instructorMechanical
 * @property bool $isActive
 *
 * @property InstructorInfo[] $instructorInfos
 */
class InstructorMechanical extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instructor_mechanical';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sequence'], 'integer'],
            [['instructorMechanical'], 'required'],
            [['isActive'], 'boolean'],
            [['instructorMechanical'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkInstructorMechanical' => 'ID',
            'sequence' => 'Sequence',
            'instructorMechanical' => 'Mechanical Knowledge',
            'isActive' => 'Is Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstructorInfos()
    {
        return $this->hasMany(InstructorInfo::className(), ['fkInstMechanical' => 'pkInstructorMechanical']);
    }
}
