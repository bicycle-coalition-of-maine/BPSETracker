<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorStatus */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="instructor-status-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'instructorStatus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isActive')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
