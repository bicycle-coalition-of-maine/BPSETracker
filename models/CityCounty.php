<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "city_county".
 *
 * @property string $city
 * @property string $county
 * @property bool $pacts 
 * @property bool $bacts 
 * @property bool $focus21 
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
            [['pacts', 'bacts', 'focus21'], 'boolean'], 
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
            'pacts' => 'PACTS', 
            'bacts' => 'BACTS', 
            'focus21' => 'Focus 21', 
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
