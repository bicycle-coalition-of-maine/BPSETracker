<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event_type".
 *
 * @property int $pkEventTypeID
 * @property int $sequence
 * @property string $eventType
 * @property string $description
 *
 * @property EventEventType[] $eventEventTypes
 * @property Event[] $fkEvents
 */
class EventType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sequence', 'eventType', 'description'], 'required'],
            [['sequence'], 'integer'],
            [['isPublic'], 'boolean'],
            [['eventType'], 'string', 'max' => 40],
            [['description'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkEventTypeID' => 'ID',
            'sequence' => 'Sequence',
            'eventType' => 'Event Type',
            'description' => 'Description',
            'isPublic' => 'Public?'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventEventTypes()
    {
        return $this->hasMany(EventEventType::className(), ['fkEventTypeID' => 'pkEventTypeID']);
    }
}
