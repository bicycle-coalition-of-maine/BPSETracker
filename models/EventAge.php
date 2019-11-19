<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event_age".
 *
 * @property int $pkEventAgeID
 * @property int $sequence
 * @property string $eventAge
 *
 * @property Event[] $events
 */
class EventAge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_age';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sequence', 'eventAge'], 'required'],
            [['sequence'], 'integer'],
            [['eventAge'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkEventAgeID' => 'ID',
            'sequence' => 'Sequence',
            'eventAge' => 'Age Group',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['fkEventAgeID' => 'pkEventAgeID']);
    }
}
