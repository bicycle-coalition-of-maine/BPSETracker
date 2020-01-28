<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "city_county".
 *
 * @property string $city
 * @property string $county
 */
class CityCounty extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city_county';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city', 'county'], 'required'],
            [['city'], 'string', 'max' => 40],
            [['county'], 'string', 'max' => 20],
            [['city'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'city' => 'City',
            'county' => 'County',
        ];
    }
    
    static public function getCounty($city)
    {
        return (CityCounty::findOne(['city' => $city]))->county;
    }

    static public function getCountyDropDownItems()
    {
        return CityCounty::find()
                ->select('county')
                ->indexBy('county')
                ->orderBy('county')
                ->distinct()
                ->column();
    }

}
