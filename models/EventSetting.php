<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event_setting".
 *
 * @property int $pkEventSettingID
 * @property int $sequence
 * @property string $eventSetting
 */
class EventSetting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['sequence', 'eventSetting'], 'required'],  // Not sure if needed. JB 10/5/19
            [['sequence'], 'integer'],
            [['eventSetting'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkEventSettingID' => 'Pk Event Setting ID',
            'sequence' => 'Sequence',
            'eventSetting' => 'Event Setting',
        ];
    }
}
