<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event_event_type".
 *
 * @property int $fkEventID
 * @property int $fkEventTypeID
 *
 * @property Event $fkEvent
 * @property EventType $fkEventType
 */
class EventEventType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_event_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fkEventID', 'fkEventTypeID'], 'required'],
            [['fkEventID', 'fkEventTypeID'], 'integer'],
            [['fkEventID', 'fkEventTypeID'], 'unique', 'targetAttribute' => ['fkEventID', 'fkEventTypeID']],
            [['fkEventID'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['fkEventID' => 'pkEventID']],
            [['fkEventTypeID'], 'exist', 'skipOnError' => true, 'targetClass' => EventType::className(), 'targetAttribute' => ['fkEventTypeID' => 'pkEventTypeID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fkEventID' => 'Fk Event ID',
            'fkEventTypeID' => 'Fk Event Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkEvent()
    {
        return $this->hasOne(Event::className(), ['pkEventID' => 'fkEventID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkEventType()
    {
        return $this->hasOne(EventType::className(), ['pkEventTypeID' => 'fkEventTypeID']);
    }
}
