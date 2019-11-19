<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "staffing".
 *
 * @property int $pkStaffingID
 * @property int $fkEventID
 * @property int $fkPersonID
 *
 * @property Person $fkPerson
 * @property Event $fkEvent
 */
class Staffing extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staffing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fkEventID', 'fkPersonID'], 'required'],
            [['fkEventID', 'fkPersonID'], 'integer'],
            [['fkPersonID'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['fkPersonID' => 'pkPersonID']],
            [['fkEventID'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['fkEventID' => 'pkEventID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkStaffingID' => 'Staffing ID',
            'fkEventID' => 'Event ID',
            'fkPersonID' => 'Person ID',
        ];
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
    public function getEvent()
    {
        return $this->hasOne(Event::className(), ['pkEventID' => 'fkEventID']);
    }
}
