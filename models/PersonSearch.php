<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Person;

/**
 * PersonSearch represents the model behind the search form of `app\models\Person`.
 */
class PersonSearch extends Person
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pkPersonID'], 'integer'],
            [['firstName', 'lastName', 'email', 'phone', 'phoneExt', 'address1', 'address2', 'city', 'state', 'zipcode'], 'safe'],
            [['isStaff', 'isContact'], 'boolean'],
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
        $query = Person::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'lastName' => SORT_ASC,
					'firstName' => SORT_ASC
				]
			]        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;

        }

        // Filter just instructors or just contacts
        if(isset($params['show']))
            switch($params['show'])
            {
                case 'C':
                    $query->andFilterWhere(['isContact' => 1 ]);
                    break;
                case 'I':
                    $query->andFilterWhere(['isStaff' => 1 ]);
                    break;
                case 'A':
                    $query->andFilterWhere(['isAdmin' => 1 ]);
                    break;
            }

        // grid filtering conditions
        $query->andFilterWhere(['pkPersonID' => $this->pkPersonID,])
            ->andFilterWhere(['like', 'lastName', $this->lastName])
            ->andFilterWhere(['like', 'firstName', $this->firstName])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'phoneExt', $this->phoneExt])
            ->andFilterWhere(['like', 'address1', $this->address1])
            ->andFilterWhere(['like', 'address2', $this->address2])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'zipcode', $this->zipcode]);

        return $dataProvider;
    }
}
