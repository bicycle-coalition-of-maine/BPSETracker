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

?>

<div class='request-index'>

    <h1><?= $this->title ?></h1>
    
    <p><?php 
        $fmtPhone = Yii::$app->globals->formatPhone($model->phone, '(');
        echo "<b>Contact</b>: {$model->firstName} {$model->lastName}, {$model->email}, $fmtPhone"; 
    ?></p>
    
    <h2>Organization</h2>
    
    <?php $form = ActiveForm::begin(); ?>
        
    <p>
        <?= ( Yii::$app->request->get('all') == '1')
            ? 'Please choose from the following list of organizations.'
            : "Please choose from the following list of organizations in {$model->eventCounty} county."
        ?>
        If this is your first request, please choose "New organization".
    </p>

    <?= $form->field($model, 'fkOrgID')->listBox($orgs, ['size' => '10', 'prompt' => '-- New organization --'])->label(false) ?>
    
    <?php if( Yii::$app->request->get('all') != '1') { ?>
    <p>
        If your organization is not listed, but you think we already have it in our database,
        please <a href='<?= Url::toRoute(['org', 'all' => '1']) ?>'>click here</a> to choose from the complete list of all organizations.
    </p>
    <?php } ?>
    
    <p>Please enter or confirm your organization's details:</p>
    
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
    
    <?= $form->field($model, 'isAtOrgAddress')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Next', ['class' => 'btn btn-success']) ?>
        <a href='<?= Yii::$app->urlManager->createURL(['site/index']) ?>'>Cancel</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
