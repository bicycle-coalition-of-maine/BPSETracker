<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CityCounty;

/* @var $this yii\web\View */
/* @var $model app\models\Person */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <div class='col-sm-2'>
            <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-sm-3'>
            <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-sm-4'>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-sm-2'>
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class='col-sm-1'>
            <?= $form->field($model, 'phoneExt')->textInput(['maxlength' => true])->label('Ext.') ?>
        </div>
    </div> <!-- row -->

    <?= $form->field($model, 'address1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address2')->textInput(['maxlength' => true]) ?>

    <div class='row'>
        <div class='col-sm-3'>
            <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
        </div>

        <div class='col-sm-1'>
            <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>
        </div>

        <div class='col-sm-2'>
            <?= $form->field($model, 'zipcode')->textInput(['maxlength' => true]) ?>
        </div>
    
        <div class='col-sm-4'>
            <?= $form->field($model, 'county')->dropDownList(CityCounty::getCountyDropDownItems(), ['prompt' => '(Unknown)']) ?>
        </div>
        <div class='col-sm-2'>
            <br><br><a href='<?= Yii::$app->urlManager->createURL(['city-county']) ?>' target='_blank'>City/County List</a>
        </div>
    </div> <!-- row -->

    <div class='row'>
        <div class='col-sm-3'><?= $form->field($model, 'isStaff')->checkbox() ?></div>
        <div class='col-sm-3'><?= $form->field($model, 'isContact')->checkbox() ?></div>
        <div class='col-sm-3'><?= $form->field($model, 'isActive')->checkbox() ?></div>   
    </div> <!-- row -->

    <hr>
    
    <?= $form->field($model, 'isAdmin')->checkbox() ?>
    <div class='row'>
        <div class='col-sm-2'>
            <?= $form->field($model, 'password')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
