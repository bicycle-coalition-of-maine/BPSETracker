<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Event;

/**
 * EventSearch represents the model behind the search form of `app\models\Event`.
 */
class EventSearch extends Event
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pkEventID', 'fkOrgID', 'fkPersonID', 'fkEventAgeID', 'fkPastInstructor'], 'integer'],
            [['requestDateTime', 'name', 'address1', 'address2', 'city', 'state', 'zipcode', 'county', 'otherType', 'ageDescription', 'need', 'participation', 'datetimes', 'presentations', 'comments', 'eventDate', 'startTime', 'endTime'], 'safe'],
            [['isAtOrgAddress', 'hasHosted', 'isBike', 'isPed'], 'boolean'],
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
        $query = Event::find();

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
            'pkEventID' => $this->pkEventID,
            'requestDateTime' => $this->requestDateTime,
            'fkOrgID' => $this->fkOrgID,
            'isAtOrgAddress' => $this->isAtOrgAddress,
            'fkPersonID' => $this->fkPersonID,
            'fkEventAgeID' => $this->fkEventAgeID,
            'hasHosted' => $this->hasHosted,
            'fkPastInstructor' => $this->fkPastInstructor,
            'eventDate' => $this->eventDate,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'isBike' => $this->isBike,
            'isPed' => $this->isPed,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address1', $this->address1])
            ->andFilterWhere(['like', 'address2', $this->address2])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'zipcode', $this->zipcode])
            ->andFilterWhere(['like', 'county', $this->county])
            ->andFilterWhere(['like', 'otherType', $this->otherType])
            ->andFilterWhere(['like', 'ageDescription', $this->ageDescription])
            ->andFilterWhere(['like', 'need', $this->need])
            ->andFilterWhere(['like', 'participation', $this->participation])
            ->andFilterWhere(['like', 'datetimes', $this->datetimes])
            ->andFilterWhere(['like', 'presentations', $this->presentations])
            ->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
    }
}
