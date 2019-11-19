<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mileage".
 *
 * @property string $pkEffDate
 * @property string $rate
 */
class Mileage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mileage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pkEffDate', 'rate'], 'required'],
            [['pkEffDate'], 'safe'],
            [['rate'], 'number'],
            [['pkEffDate'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pkEffDate' => 'Effective Date',
            'rate' => 'Rate',
        ];
    }
    
    /**
     * Override find method to use max effective date
     */
    public static function findOne($date)
    {
        return Mileage::find()
            ->where("pkEffDate = (SELECT MAX(pkEffDate) FROM mileage WHERE pkEffDate <= '$date')")
            ->one();
    }
}
