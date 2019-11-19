<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rate".
 *
 * @property int $pkRateID
 * @property string $description
 * @property string $rate
 * @property bool $isActive
 */
class Rate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['rate'], 'number', 'max' => 99.99 ],
            [['isActive'], 'boolean'],
            [['description'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkRateID' => 'Rate ID',
            'description' => 'Description',
            'rate' => 'Hourly Rate',
            'isActive' => 'Is Active?',
        ];
    }
}
