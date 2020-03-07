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
    // More attributes for related search fields
    public $orgName;
    public $contactName;
    public $instructorName;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pkEventID'], 'integer'],
            [['requestDateTime', 'eventDate', 'orgName', 'city', 'contactName', 'instructorName'], 'safe'],
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
        $query = Event::find()->alias('E');
        
        /*
         * A basic one to many join from events to organizations. Can use
         * shorthand syntax because the base Event model defines the join
         * in the getOrganization property method.
         */
        $query->joinWith('organization O');
        
        /*
         * The one to many join from events to event contact must use a more
         * fully qualified join because we didn't name field and properties
         * as conveniently in these models. Note that event.fkPersonID refers
         * to the event contact.
         */
        $query->innerJoin('person C', 'C.pkPersonID = E.fkPersonID');

        /*
         * This is the many-to-many join between events and instructors
         * (person table), through the staffing junction table. This is
         * defined in the base Event in property/method getStaffPeople, so
         * the shorthand syntax can be used.
         */
        $query->joinWith('staffPeople I');
        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['requestDateTime' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Sorting on related attributes
        $dataProvider->sort->attributes['orgName'] = [
            'asc' => ['O.name' => SORT_ASC ],
            'desc' => ['O.name' => SORT_DESC ],
        ];
        $dataProvider->sort->attributes['contactName'] = [
            'asc' => ['C.lastName' => SORT_ASC, 'C.firstName' => SORT_ASC ],
            'desc' => ['C.lastName' => SORT_DESC, 'C.firstName' => SORT_DESC ],
        ];
        $dataProvider->sort->attributes['instructorName'] = [
            'asc' => ['I.lastName' => SORT_ASC, 'I.firstName' => SORT_ASC ],
            'desc' => ['I.lastName' => SORT_DESC, 'I.firstName' => SORT_DESC ],
        ];
        
        // date filtering by year
        if($this->requestDateTime) {
            
            // Match on 4-digit year
            if(preg_match('/^\d{4}$/', $this->requestDateTime)) {
                $query->andFilterWhere(['between', 'requestDateTime', "{$this->requestDateTime}-01-01", "{$this->requestDateTime}-12-31"]);
            }
            
            // Match on M/Y
            if(preg_match_all('/^(\d{1,2})\/(\d{2}|\d{4})$/', $this->requestDateTime, $matches)) {
                $mon = $matches[1][0];
                $yr = $matches[2][0];
                if($yr < 2000) $yr += 2000; // Y2.1K problem!! ;-)
                $lastDay = date("Y-m-t", strtotime("$yr-$mon-01"));
                $query->andFilterWhere(['between', 'requestDateTime', "$yr-$mon-01", $lastDay]);
            }
            
            // Single date, [0]m/[0]d/yy or [0]m/[0]d/yyyy
            if(preg_match_all('/^(\d{1,2})\/(\d{1,2})\/(\d{2}|\d{4})$/', $this->requestDateTime, $matches)) {
                $mon = $matches[1][0];
                $day = $matches[2][0];
                $yr = $matches[3][0];
                if($yr < 2000) $yr += 2000; // Y2.1K problem!! ;-)
                $query->andFilterWhere(['requestDateTime' => "$yr-$mon-$day"]);
            }        
        }
        
        if($this->eventDate) {

            // Match on 4-digit year
            if(preg_match('/^\d{4}$/', $this->eventDate)) {
                $query->andFilterWhere(['between', 'eventDate', "{$this->eventDate}-01-01", "{$this->eventDate}-12-31"]);
            }
            
            // Match on M/Y
            if(preg_match_all('/^(\d{1,2})\/(\d{2}|\d{4})$/', $this->eventDate, $matches)) {
                $mon = $matches[1][0];
                $yr = $matches[2][0];
                if($yr < 2000) $yr += 2000; // Y2.1K problem!! ;-)
                $lastDay = date("Y-m-t", strtotime("$yr-$mon-01"));
                $query->andFilterWhere(['between', 'eventDate', "$yr-$mon-01", $lastDay]);
            }
            
            // Single date, [0]m/[0]d/yy or [0]m/[0]d/yyyy
            if(preg_match_all('/^(\d{1,2})\/(\d{1,2})\/(\d{2}|\d{4})$/', $this->eventDate, $matches)) {
                $mon = $matches[1][0];
                $day = $matches[2][0];
                $yr = $matches[3][0];
                if($yr < 2000) $yr += 2000; // Y2.1K problem!! ;-)
                $query->andFilterWhere(['eventDate' => "$yr-$mon-$day"]);
            }
        }
        
        // grid filtering conditions
        $query->andFilterWhere([
            'pkEventID' => $this->pkEventID,
            'fkOrgID' => $this->fkOrgID,
            'isAtOrgAddress' => $this->isAtOrgAddress,
            'fkPersonID' => $this->fkPersonID,
            'fkEventAgeID' => $this->fkEventAgeID,
            'fkPastInstructor' => $this->fkPastInstructor,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'isBike' => $this->isBike,
            'isPed' => $this->isPed,
        ]);

        $query->andFilterWhere(['like', 'O.name', $this->orgName])
            ->andFilterWhere(['like', 'E.city', $this->city])
            ->andFilterWhere(['like', 'county', $this->county])
            ->andFilterWhere(['or', ['like', 'C.firstName', $this->contactName], ['like', 'C.lastName', $this->contactName]])
            ->andFilterWhere(['or', ['like', 'I.firstName', $this->instructorName], ['like', 'I.lastName', $this->instructorName]]);

        return $dataProvider;
    }
}
