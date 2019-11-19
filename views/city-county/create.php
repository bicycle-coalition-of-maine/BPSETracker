<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\Models\CityCounty */

$this->title = 'Create City County';
$this->params['breadcrumbs'][] = ['label' => 'City Counties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-county-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
