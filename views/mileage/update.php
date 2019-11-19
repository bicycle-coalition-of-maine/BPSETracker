<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mileage */

$effDt = Yii::$app->globals->formatSQLDate($model->pkEffDate, 'n/j/Y');
$this->title = "Update Mileage Effective $effDt";
$this->params['breadcrumbs'][] = ['label' => 'Mileage Rates', 'url' => ['index']];
$this->params['breadcrumbs'][] = "Update Effective $effDt";
?>
<div class="mileage-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['mileage']) ?>'>Cancel</a>

</div>
