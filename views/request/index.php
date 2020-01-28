<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $title string */
/* @var $model app\models\Request */
/* @var $countyDDList */

$this->title = $title;
?>

<div class='request-index'>

    <h1><?= $this->title ?></h1>
    
    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        <?= Yii::$app->globals->wrapInColumn(3, $form->field($model, 'firstName')->textInput()->label('Your first name')) ?>
        <?= Yii::$app->globals->wrapInColumn(3, $form->field($model, 'lastName')->textInput()) ?>        
    </div>
    <div class="row">
        <?= Yii::$app->globals->wrapInColumn(3, $form->field($model, 'email')->textInput()) ?>
        <?= Yii::$app->globals->wrapInColumn(2, $form->field($model, 'phone')->textInput()) ?>
        <?= Yii::$app->globals->wrapInColumn(1, $form->field($model, 'phoneExt')->textInput()) ?>
    </div>
    <div class="row">
        <?= Yii::$app->globals->wrapInColumn(6, $form->field($model, 'eventCounty')->dropDownList($countyDDList)->label('County in which event will take place: ')) ?>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton('Next', ['class' => 'btn btn-success']) ?>
        <a href='<?= Yii::$app->urlManager->createURL(['site/index']) ?>'>Cancel</a>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>
