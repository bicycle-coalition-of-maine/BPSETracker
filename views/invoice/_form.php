<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\Models\Invoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fkEventID')->dropDownList($eventDDL) ?>

    <div class='row'>
        <div class='col-sm-3'><?= $form->field($model, 'isSchool')->checkbox() ?></div>
        <div class='col-sm-3'><?= $form->field($model, 'isBike')->checkbox() ?></div>
        <div class='col-sm-3'><?= $form->field($model, 'isPed')->checkbox() ?></div>
    </div>

    <?= $form->field($model, 'fkPersonID')->dropDownList($instructorDDL) ?>

    <div class="row">
        <div class="col-sm-2">
            <?= 
                $form->field($model, 'invoiceDate')
                    ->widget(\yii\jui\DatePicker::class,
                            //['dateFormat' => 'M/d/yyyy']
                            ['dateFormat' => 'php:n/d/Y H:i:s']
                            );
            ?>            
        </div>
        <div class="col-sm-1">
            <?= $form->field($model, 'hours')->textInput() ?>
        </div>
        <div class="col-sm-5">
            <?= $form->field($model, 'fkRateRequested')->dropDownList($rateDDL)?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'presentations')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'presentees')->textInput() ?>
        </div>
    </div>
    
    <div class='row'>
        <div class="col-sm-1">
            <?= $form->field($model, 'miles')->textInput() ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'invoiceAmount')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= $form->field($model, 'submitterComments')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
