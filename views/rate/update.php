<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\Models\Rate */

$this->title = 'Update Rate ID #' . $model->pkRateID;
$this->params['breadcrumbs'][] = ['label' => 'Rates', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update ID #' . $model->pkRateID;

$backLink = Yii::$app->urlManager->createURL(['rate/index']);
?>
<div class="rate-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['rate']) ?>'>Cancel</a>
    
</div>
