<?php

namespace app\controllers;

use Yii;
use app\models\Person;
use app\models\PersonSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

/**
 * PersonController implements the CRUD actions for Person model.
 */
class PersonController extends Controller
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
     * Lists all Person models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Person model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        // Note this person as a global variable to look up in the view
        Yii::$app->globals->var['fkPersonID'] = $id;

        return $this->render('view', [
            'model' => $model,
            'orgs' => new ArrayDataProvider([ 'allModels' => $model->organizations ]),
            'events' => new ArrayDataProvider([ 'allModels' => $model->events ]),
        ]);
    }

    /**
     * Creates a new Person model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Person();
        $model->isActive = 1;

        if ($model->load(Yii::$app->request->post())) {
            
            // Store phone numbers as all digits, strip everything else
            if(strlen($model->phone) > 0)
                $model->phone = preg_replace('/[^\d]+/', '', $model->phone);
            
            // Add 0's to front of zip code if necessary
            if (strlen($model->zipcode) > 0 && strlen($model->zipcode) < 5 )
                $model->zipcode = str_pad($model->zipcode, 5, '0', STR_PAD_LEFT);
            
            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->pkPersonID]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Person model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            
            // Store phone numbers as all digits, strip everything else
            if(strlen($model->phone) > 0)
                $model->phone = preg_replace('/[^\d]+/', '', $model->phone);
            
            // Add 0's to front of zip code if necessary
            if (strlen($model->zipcode) > 0 && strlen($model->zipcode) < 5 )
                $model->zipcode = str_pad($model->zipcode, 5, '0', STR_PAD_LEFT);
            
            // if password, encrypt it
            if($model->password)
                $model->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
            
            if($model->save()) {
                return $this->redirect(['view', 'id' => $model->pkPersonID]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Person model.
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
     * Finds the Person model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Person the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Person::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
