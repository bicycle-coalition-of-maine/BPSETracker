<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Organization;
use app\models\Person;
use app\models\Contact;
use app\models\OrgSearch;

/**
 * OrgController implements the CRUD actions for Organization model.
 */
class OrgController extends Controller
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

    /**
     * Lists all Organization models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrgSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Organization model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
//		$contactList = new ActiveDataProvider([
//			'query' => (new \yii\db\Query())
//						-> select([ 'person.pkPersonID',
//						            "CONCAT( person.firstName, ' ', person.lastName ) AS name",
//									'contact.title',
//								])
//						-> from('contact')
//						-> innerJoin( 'person', 'person.pkPersonID = contact.fkPersonID' )
//						-> where( "contact.fkOrgID = $id" )
//						-> orderBy('person.lastName')
//		]);

//                $eventList = new ActiveDataProvider([
//                    'query' => (new \yii\db\Query())
//                    -> select([ 'event.*'])
//                    -> from('organization')
//                    -> leftJoin( 'event', 'event.fkOrgId = organization.pkOrgId')
//                    -> where( "organization.pkOrgId = $id" )
//                    -> orderBy( 'event.requestDateTime DESC')

//                   'query' => (new \yii\db\Query())
//                        -> select([
//                            
//                        ])
//                        -> from('event')
//                        -> leftJoin('event', 'event.pkEventID = event.fkEvent')
//                        
//                ]);

        $model = $this->findModel($id);

        Yii::$app->globals->var['fkOrgID'] = $id;
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'contacts' => new ArrayDataProvider([ 'allModels' => $model->people ]),
            'events' => new ArrayDataProvider([ 'allModels' => $model->events ]),
        ]);
    }

    /*
     * Processes new and/or changed contacts
     * @param integer $id
     * @return void
     */
    
    public function updateContacts($id)
    {
        $arrPost = Yii::$app->request->post();
        $contactNbr = 0;
        
        while(isset($arrPost['contactOrigPersonID'][$contactNbr]))
        {
            if($arrPost['contactChanged'][$contactNbr])
            {
                // set up some convenience variables
                $origPersonID = $arrPost['contactOrigPersonID'][$contactNbr];
                $newPersonID = $arrPost['contactNewPersonID'][$contactNbr];
                $title = $arrPost['contactTitle'][$contactNbr];
                
                if($newPersonID == 0) { // "changed" to 0 means delete
                    (Contact::findOne(['fkOrgID' => $id, 'fkPersonID' => $origPersonID]))->delete();
                }
                else {                  // else either create (original=0) or update
                    $contactRow = new Contact();
                    $contactRow->fkOrgID = $id;

                    if($origPersonID)    // change existing contact
                        $contactRow = Contact::find()->where(['fkOrgID' => $id, 'fkPersonID' => $origPersonID])->one();

                    $contactRow->fkPersonID = $newPersonID;
                    $contactRow->title = $title;
                    $contactRow->save();                    
                }
            }
            ++$contactNbr;
        }
    }
    
    /**
     * Creates a new Organization model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Organization();

        if ($model->load(Yii::$app->request->post()))
        {
            $transaction = (Yii::$app->db)->beginTransaction();
            
            if($model->save())
            {
                $this->updateContacts($model->pkOrgID);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->pkOrgID]);
            }
            else
                $transaction->rollback();
        }

        return $this->render('create', [
            'model' => $model,
            'people' => Person::find()
                ->select(["CONCAT(lastName, ', ', firstName)"]) // Note array form required to get quote syntax correct
                ->indexBy('pkPersonID')
                ->where('isContact = 1')
                ->orderBy('lastName, firstName')
                ->column(),        ]);
    }

    /**
     * Updates an existing Organization model.
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
            
            if($model->save())
            {
                $this->updateContacts($model->pkOrgID);
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->pkOrgID]);
            }
            else
                $transaction->rollback();
        }

        Yii::$app->globals->var['fkOrgID'] = $id;
        
        return $this->render('update', [
            'model' => $model,
            'people' => Person::find()
                ->select(["CONCAT(lastName, ', ', firstName)"]) // Note array form required to get quote syntax correct
                ->indexBy('pkPersonID')
                ->where('isContact = 1')
                ->orderBy('lastName, firstName')
                ->column(),
        ]);
    }

    /**
     * Deletes an existing Organization model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Organization model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Organization the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Organization::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
