<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\instructorLci */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="instructor-lci-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'instructorLCI')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isActive')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
