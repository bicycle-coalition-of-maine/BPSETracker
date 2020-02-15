<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

use app\models\EmailTest;

class EmailTestController extends Controller {
    
    public function actionIndex() {
        $model = new EmailTest();
        
        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            $now = (new \DateTime())->format('Y-m-d H:i:s');
            Yii::$app->mailer->compose()
                    ->setFrom('donotreply@bikemaine.org')
                    ->setTo($model->to)
                    ->setSubject("BPSE Event Tracker Test Email @ $now")
                    ->setTextBody("This is a test.\n$now\n\n{$model->msg}\n")
                    ->send();
            return $this->redirect(['site/admin']);
        }
        
        return $this->render('index', ['model' => $model]);
    }
}
