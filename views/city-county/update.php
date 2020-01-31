<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\Models\CityCounty */

$this->title = 'Update City: ' . $model->city;
$this->params['breadcrumbs'][] = ['label' => 'City Attributes', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->city, 'url' => ['view', 'id' => $model->city]];
$this->params['breadcrumbs'][] = "Update {$model->city}";
?>
<div class="city-county-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['city-county']) ?>'>Cancel</a>

</div>
