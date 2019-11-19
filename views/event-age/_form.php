<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EventAge */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-age-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sequence')->textInput() ?>

    <?= $form->field($model, 'eventAge')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
