<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\Models\CityCounty */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="city-county-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
    <?php
        echo Yii::$app->globals->wrapInColumn(6, $form->field($model, 'city')->textInput(['maxlength' => true]));
        echo Yii::$app->globals->wrapInColumn(6, $form->field($model, 'county')->textInput(['maxlength' => true]));
    ?>
    </div>

    <div class="row">
    <?php
        echo Yii::$app->globals->wrapInColumn(4, $form->field($model, 'pacts')->checkbox());
        echo Yii::$app->globals->wrapInColumn(4, $form->field($model, 'bacts')->checkbox());
        echo Yii::$app->globals->wrapInColumn(4, $form->field($model, 'focus21')->checkbox());
    ?>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
