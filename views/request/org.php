<?php

use yii\web\Session;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use app\models\Request;

/* @var $this yii\web\View */
/* @var $title string */
/* @var $model app\models\Request */
/* @var $orgs */

$this->title = $title;

$this->registerJsFile('js/RequestOrg.js');

$this->registerCss("p.step { font-size: larger; }")

?>

<div class='request-index'>

    <h1><?= $this->title ?></h1>
    
    <p>
        <b>Contact</b>: <?= $model->firstName ?> <?= $model->lastName ?>,
        <?= $model->MaskedEmail() ?>,
        <?= $model->MaskedPhone() ?>
    </p>
    
    <h2>Organization</h2>
    
    <?php $form = ActiveForm::begin(); ?>
        
    <p class="step">
        <b>Step 1.</b> If your organization has requested assistance from us before, please 
        select it from the list below. If your organization is not listed,
        choose "This is our first request" below the list.
    </p>
    
    <p>You may search by clicking on the list and typing the name of your organization.
        
        <?php
        if(!Yii::$app->request->get('all'))
        {
            ?>Click <a href='<?= Url::toRoute(['org', 'all' => '1']) ?>'>here</a> to expand the list to organizations beyond <?= $model->eventCounty ?> county.
        <?php } ?>
    </p>

    <?= $form->field($model, 'fkOrgID')->listBox($orgs, ['size' => '10'])->label(false) ?>
    
    <?= $form->field($model, 'newOrg')->radioList(['1' => 'Yes', '0' => 'No']) ?>
    
    <p class="step"><b>Step 2.</b> Please enter or confirm your organization's details. These fields will 
        auto-complete if you chose an existing organization from the list above.
    </p>
    
    <div class='row'>
        <?php
            echo Yii::$app->globals->wrapInColumn(7, $form->field($model, 'orgName')->textInput());
            echo Yii::$app->globals->wrapInColumn(4, $form->field($model, 'title')->textInput());
        ?>        
    </div>
    
    <div class='row'>
        <?php
            echo Yii::$app->globals->wrapInColumn(5, $form->field($model, 'orgAddress')->textInput());
            echo Yii::$app->globals->wrapInColumn(4, $form->field($model, 'orgCity')->textInput());
            echo Yii::$app->globals->wrapInColumn(2, $form->field($model, 'orgZip')->textInput());
        ?>
    </div>
    
    <?= $form->field($model, 'isAtOrgAddress')->radioList(['1' => 'Yes', '0' => 'No']) ?>

    <div class="form-group">
        <?= Html::submitButton('Next', ['class' => 'btn btn-success']) ?>
        <a href='<?= Yii::$app->urlManager->createURL(['site/index']) ?>'>Cancel</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
