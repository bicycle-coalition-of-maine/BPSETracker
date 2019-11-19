<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorRideType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="instructor-ride-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'instructorRideType')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isActive')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
