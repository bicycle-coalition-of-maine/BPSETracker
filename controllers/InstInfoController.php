<?php

namespace app\controllers;

use Yii;
use app\models\InstructorInfo;
use app\models\Person;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InstInfoController implements the CRUD actions for InstructorInfo model.
 */
class InstInfoController extends Controller
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

    // Update one of the several many-to-many relationships
    private function updateM2M($tableName, $fkFieldName, $pkInstID)
    {
        if(Yii::$app->request->post("{$tableName}_CHANGED")) {

            // First, delete all related to this InstructorInfo row
            Yii::$app->db->createCommand()
                    ->delete($tableName, ['fkInstructorInfo' => $pkInstID])
                    ->execute();

            // Now add back in the ones that are checked
            // Note this will cause a crash if all were unchecked, but
            // really, user shouldn't do that anyway, so serves them right. ;-)
            foreach(array_keys(Yii::$app->request->post($tableName)) as $fkey)
            {
                Yii::$app->db->createCommand()
                        ->insert($tableName, 
                                ['fkInstructorInfo' => $pkInstID,
                                 "$fkFieldName" => $fkey,
                                ])
                        ->execute();
            }
        } // if options changed
    }
    
    /**
     * Lists all InstructorInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => InstructorInfo::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InstructorInfo model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new InstructorInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new InstructorInfo();
        $model->fkPersonID = $id;
        $model->year = date('Y');

        if ($model->load(Yii::$app->request->post()))
        {
            $db = Yii::$app->db; // for convenience
            $transaction = $db->beginTransaction();
            
            try 
            {
                // Save the primary instructor_info record
                $model->save();

                // Update the M2M relationships, if changed
                $this->updateM2M('instructor_info_ages', 'fkInstructorAgeGroup', $model->pkInstructorInfo);
                $this->updateM2M('instructor_info_activity', 'fkInstructorActivity', $model->pkInstructorInfo);
                $this->updateM2M('instructor_info_ridetypes', 'fkInstructorRideType', $model->pkInstructorInfo);
                $this->updateM2M('instructor_info_available', 'fkInstructorAvailable', $model->pkInstructorInfo);
                
                // Everything worked, commit the changes!
                $transaction->commit();
                
                // And return to the person's view page
                return $this->redirect(['person/view', 'id' => $model->fkPersonID]);
            } // try
            
            catch(\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }            
        }

        return $this->render('create', [
            'model' => $model,
            'person' => Person::findOne($id),
        ]);
    }

    /**
     * Updates an existing InstructorInfo model.
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
            
            $db = Yii::$app->db; // for convenience
            $transaction = $db->beginTransaction();
            
            try 
            {
                // Save the primary instructor_info record
                $model->save();

                // Update the M2M relationships, if changed
                $this->updateM2M('instructor_info_ages', 'fkInstructorAgeGroup', $model->pkInstructorInfo);
                $this->updateM2M('instructor_info_activity', 'fkInstructorActivity', $model->pkInstructorInfo);
                $this->updateM2M('instructor_info_ridetypes', 'fkInstructorRideType', $model->pkInstructorInfo);
                $this->updateM2M('instructor_info_available', 'fkInstructorAvailable', $model->pkInstructorInfo);
                
                // Everything worked, commit the changes!
                $transaction->commit();
                
                // And return to the person's view page
                return $this->redirect(['person/view', 'id' => $model->fkPersonID]);
            } // try
            
            catch(\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }                
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing InstructorInfo model.
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
     * Finds the InstructorInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InstructorInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InstructorInfo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
