<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\Models\CityCounty */

$this->title = 'Update City County: ' . $model->city;
$this->params['breadcrumbs'][] = ['label' => 'City Counties', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->city, 'url' => ['view', 'id' => $model->city]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="city-county-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
