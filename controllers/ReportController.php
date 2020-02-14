<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

/**
 * Description of ReportController
 *
 * @author John Brooking
 */

class ReportController extends Controller {
    
    public function actionIndex()
    {
        return $this->render('index');
    }
}
