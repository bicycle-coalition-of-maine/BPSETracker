<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CityCounty;

/**
 * CityCountySearch represents the model behind the search form of `app\Models\CityCounty`.
 */
class CityCountySearch extends CityCounty
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city', 'county'], 'safe'],
            [['pacts', 'bacts', 'focus21'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CityCounty::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'pacts' => $this->pacts,
            'bacts' => $this->bacts,
            'focus21' => $this->focus21,
        ]);

        $query->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'county', $this->county]);

        return $dataProvider;
    }
}
