<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mileage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mileage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= 
        $form->field($model, 'pkEffDate')
            ->label('Effective Date')
            ->widget(\yii\jui\DatePicker::class,
                    ['dateFormat' => 'M/d/yyyy']
                    );
    ?>

    <?= $form->field($model, 'rate')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
