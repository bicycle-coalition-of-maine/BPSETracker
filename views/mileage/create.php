<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mileage */

$this->title = 'Create Mileage Rates';
$this->params['breadcrumbs'][] = ['label' => 'Mileage Rate', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mileage-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['mileage']) ?>'>Cancel</a>

</div>
