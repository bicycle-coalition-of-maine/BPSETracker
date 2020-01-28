<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

use app\models\Request;

/* @var $this yii\web\View */
/* @var $title string */
/* @var $model app\models\Request */
/* @var $types app\models\EventType */
/* @var $ageDDList */

$this->title = $title;

$this->registerCss("p.notes { font-size: smaller; margin-bottom: 2em; margin-top: -1em; font-style: italic; }")
?>

<div class='request-index'>

    <h1><?= $this->title ?></h1>

    <p><?php 
        $fmtPhone = Yii::$app->globals->formatPhone($model->phone, '(');
        echo "<b>Contact</b>: {$model->firstName} {$model->lastName}, {$model->email}, $fmtPhone"; 
    ?></p>
    <p><b>Organization</b>: <?= $model->orgName ?></p>
    
    <?php $form = ActiveForm::begin(); ?>
    
    <h2>Event Information</h2>
    
    <div class='row'>
        <?php
            echo Yii::$app->globals->wrapInColumn(5, $form->field($model, 'eventAddress')->textInput());
            echo Yii::$app->globals->wrapInColumn(4, $form->field($model, 'eventCity')->textInput());
            echo Yii::$app->globals->wrapInColumn(2, $form->field($model, 'eventZip')->textInput());
        ?>
    </div>
    
    <hr>
    
    <label>Event Type(s):</label>
    <p style='font-size: smaller;'>Check all that apply</p>

    <?php
        foreach( $types as $evType) {
            $val = $evType->pkEventTypeID;
            ?>
                <p>
                    <b><input type='checkbox' name='eventType[<?= $val ?>]' value='<?= $val ?>'> <?= $evType->eventType ?></b><br>
                    <span style='font-size: smaller; font-style: italic;'><?= $evType->description ?></span>
                </p>
    <?php } ?>
    <p>
        <b><input type='checkbox' name='eventType[-1]' value='-1'> Other</b><br>
        <span style='font-size: smaller; font-style: italic;'>If something else, please describe: </span>
        <?= $form->field($model, 'otherType')->textInput()->label(false); ?>
    </p>

    <hr>
                
    <?= $form->field($model, 'need')->textarea(); ?>
    <p class='notes'><?= ($model->attributeNotes())['need'] ?></p>

    <div class='row'>
        <div class='col-sm-4'>
            <?= $form->field($model, 'estPresentations')->textInput(); ?>
            <p class='notes'><?= ($model->attributeNotes())['estPresentations'] ?></p>
        </div>
        <div class='col-sm-4'>
            <?= $form->field($model, 'estParticipants')->textInput(); ?>
            <p class='notes'><?= ($model->attributeNotes())['estParticipants'] ?></p>
        </div>
    </div>
        
    </div>

    <hr>
    
    <div class='row'>
        <div class='col-sm-3'>
            <?= $form->field($model, 'fkEventAgeID')->dropDownList($ageDDList) ?>
        </div>
        <div class='col-sm-7'>
            <?= $form->field($model, 'ageDesc')->textInput() ?>
        </div>  
    </div>
    
    <?= $form->field($model, 'proposedDates')->textInput(); ?>
    <p class='notes'><?= ($model->attributeNotes())['proposedDates'] ?></p>
    
    <?= $form->field($model, 'additionalInfo')->textarea(); ?>
    <p class='notes'><?= ($model->attributeNotes())['additionalInfo'] ?></p>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        <a href='<?= Yii::$app->urlManager->createURL(['site/index']) ?>'>Cancel</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
