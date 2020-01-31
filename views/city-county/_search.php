<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CityCountySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="city-county-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'city') ?>

    <?= $form->field($model, 'county') ?>

    <?= $form->field($model, 'pacts')->checkbox() ?>

    <?= $form->field($model, 'bacts')->checkbox() ?>

    <?= $form->field($model, 'focus21')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
