<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\instructorLci */

$this->title = 'Update LCI Status ID #' . $model->pkInstructorLCI;
$this->params['breadcrumbs'][] = ['label' => 'LCI Status', 'url' => ['index']];
$this->params['breadcrumbs'][] = "Update ID #{$model->pkInstructorLCI}";
?>
<div class="instructor-lci-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['inst-lci']) ?>'>Cancel</a>

</div>
