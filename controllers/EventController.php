<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

use app\models\Event;
use app\models\EventSearch;
use app\models\EventType;
use app\models\EventEventType;
use app\models\EventAge;
use app\models\Organization;
use app\models\Person;
use app\models\Staffing;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    private function instructorList($county) {
        $q = new Query();
        return new ActiveDataProvider([
            'query' => $q->from('person')
                         ->leftJoin( 'city_county CC', 'CC.city = person.city' )
                         ->where( "isStaff = b'1'" )
                         ->andWhere( "isActive = b'1'" )
                         ->select( [ 'pkPersonID',
                                     'CC.county',
                                     'person.city',
                                     'lastName',
                                     'firstName',
                                     'email',
                                     'phone',
                                     //"IF( CC.county = '{$county}', 0, IF( IFNULL( CC.county, '' ) = '', 2, 1 )) AS isSameCounty",
                                     "IF( CC.county = '{$county}', '***', '') AS isSameCounty",
                                   ]
                                 )
                        ->orderBy( 'lastName, firstName, city' ),
            'pagination' => false,
        ]);
    }
    
    /**
     * Lists all Event models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Event model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'staffing' => new ArrayDataProvider([ 'allModels' => $model->staffPeople ]) // Really needed?
        ]);
    }

    /*
     * Processes the series of event type checkboxes, turning them into
     * records in the event_event_type table for this $EventID.
     * @param integer $EventID
     * @return void
     */
    
    public function updateEventTypes($id)
    {
        $givenIDs = Yii::$app->request->post('eventType');
        if($givenIDs) {
            if(!is_array($givenIDs)) {      // If only one ID, make it into
                $givenIDs = [$givenIDs];    // an array anyway for processing
            }
            
//            $eventTypeIDs = array_map( 
//                function($eventType) {
//                    return $eventType->pkEventTypeID;
//                },
//                EventType::find()->select('pkEventTypeID')->all()
//            );
// Needed?

            EventEventType::deleteAll(['fkEventID' => $id]);

            foreach ($givenIDs as $givenID)
            {
                $joinRec = new EventEventType();
                $joinRec->fkEventID = $id;
                $joinRec->fkEventTypeID = $givenID;
                $joinRec->save();
            }
        }
    }
    
    /*
     * Process any staffing parameters
     * @param integer $id
     */
    
    public function updateStaffing($id)
    {
        $givenIDs = Yii::$app->request->post('staffing');
        if($givenIDs) {
            if(!is_array($givenIDs)) {      // If only one ID, make it into
                $givenIDs = [$givenIDs];    // an array anyway for processing
            }
         
            foreach(array_keys($givenIDs) as $pkStaffingID) {
                
                if($pkStaffingID) { // existing staffing record changed
                    $staffRec = Staffing::findOne($pkStaffingID);
                    
                    if($givenIDs[$pkStaffingID] == "0") {
                        $staffRec->delete();
                    }
                    elseif($givenIDs[$pkStaffingID] != $staffRec->fkPersonID) {
                        $staffRec->fkPersonID = $givenIDs[$pkStaffingID];
                        $staffRec->save();
                    }
                }
                
                elseif($pkStaffingID == "0" && $givenIDs[$pkStaffingID] != "0") {   // new staffing added
                    $staffRec = new Staffing();
                    $staffRec->fkEventID = $id;
                    $staffRec->fkPersonID = $givenIDs[$pkStaffingID];
                    $staffRec->save();
                }
            } // for each staffing param
        } // if any staffing params
    }
    
    /**
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Event();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = (Yii::$app->db)->beginTransaction();

            $model->requestDateTime = date('Y-m-d H:i:s');

            if($model->save()) {
                
                $this->updateEventTypes($model->pkEventID);

                if($model->save()) { // really necesssary? This model hasn't changed since last call.
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->pkEventID]);
                }
                else {
                    $transaction->rollback();
                }
            }
            else {
                $transaction->rollback();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'instructors' => $this->instructorList($model->county),
            'eventTypes' => EventType::find()
                ->orderBy('sequence')
                ->all(),
            'eventContact' => Person::find()
                ->select(["CONCAT(lastName, ', ', firstName)"]) // Note array form required to get quote syntax correct
                ->indexBy('pkPersonID')
                ->where('isContact = 1')
                ->orderBy('lastName, firstName')
                ->column(),
            'eventAgeGroups' => EventAge::find()  // Technique from https://www.yiiframework.com/doc/guide/2.0/en/input-forms
                ->select('eventAge')
                ->indexBy('pkEventAgeID')
                ->orderBy('sequence')
                ->column(), 
            'pastInstructor' => Person::find()
                ->select(["CONCAT(lastName, ', ', firstName)"])
                ->indexBy('pkPersonID')
                ->where('isStaff = 1')
                ->orderBy('lastName, firstName')
                ->column(),
            'orgs' => Organization::find()
                ->select('name')
                ->indexBy('pkOrgID')
                ->orderBy('name')
                ->column(),
        ]);
    }

    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
        {
            $transaction = (Yii::$app->db)->beginTransaction();

            if($model->save()) {
                
                $this->updateEventTypes($model->pkEventID);
                $this->updateStaffing($model->pkEventID);

                if($model->save()) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->pkEventID]);
                }
                else {
                    $transaction->rollback();
                }
            }
            else {
                $transaction->rollback();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'instructors' => $this->instructorList($model->county),
            'eventTypes' => EventType::find()
                ->orderBy('sequence')
                ->all(),
            'eventContact' => Person::find()
                ->select(["CONCAT(lastName, ', ', firstName)"]) // Note array form required to get quote syntax correct
                ->indexBy('pkPersonID')
                ->where('isContact = 1')
                ->orderBy('lastName, firstName')
                ->column(),
            'eventAgeGroups' => EventAge::find()  // Technique from https://www.yiiframework.com/doc/guide/2.0/en/input-forms
                ->select('eventAge')
                ->indexBy('pkEventAgeID')
                ->orderBy('sequence')
                ->column(), 
            'pastInstructor' => Person::find()
                ->select(["CONCAT(lastName, ', ', firstName)"])
                ->indexBy('pkPersonID')
                ->where('isStaff = 1')
                ->orderBy('lastName, firstName')
                ->column(),
        ]);
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $transaction = (Yii::$app->db)->beginTransaction();
        try {
            EventEventType::deleteAll(['fkEventID' => $id]);
            Staffing::deleteAll(['fkEventID' => $id]);
            $this->findModel($id)->delete();
            $transaction->commit();
            return $this->redirect(['index']);            
        } catch (Exception $ex) {
            $transaction->rollback();
            return $this->redirect(['view', 'id' => $id]);
        }
    }

    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	/**
	 * Present list of unassigned requests
	 * @return mixed
	 */
	public function actionRequests()
	{
            //$where = 'YEAR(requestDateTime) >= 2018'; // development only
            if(Yii::$app->request->get('showAll') != '1') 
                $where = ' NOT EXISTS ( SELECT pkStaffingID FROM staffing WHERE fkEventID = event.pkEventID )';
                //$where .= ' AND NOT EXISTS ( SELECT pkStaffingID FROM staffing WHERE fkEventID = event.pkEventID )';
            
            $requests = new ActiveDataProvider([
                'query' => Event::find()
                            ->where($where)
                            ->orderBy('requestDateTime DESC')
            ]);
		
            return $this->render('requests', [
                'requests' => $requests,
            ]);
	}
	 
	/**
	 * Set up the request assignment data.
	 * @param integer $id
	 * @return mixed
	 */
	 public function actionAssign($id)
	 {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()))
            {
                $transaction = (Yii::$app->db)->beginTransaction();
                $this->updateStaffing($model->pkEventID);
                if($model->save()) {
                    $transaction->commit();
                    return $this->redirect(['requests']);
                }
                else {
                    $transaction->rollback();
                }
            }
            
            return $this->render( 'assign', [
                'model' => $model,
                'instructors' => $this->instructorList($model->county),
           ]);
	 }
	 
}
