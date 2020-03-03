<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $title string */
/* @var $model app\models\InvoiceSubmission */
/* @var $eventDDL array (DDL structure) */

$this->title = $title;
?>

<div class='request-event'>

    <h1><?= $this->title ?></h1>

    <p style='font-weight: bold;'>Welcome, <?= $person ?>!</p>

    <?php if(count($eventDDL) == 0) { ?>
    
    <p style='color: red;'>You have no events pending invoicing. If you feel this is incorrect, please contact BCM.</p>
        
    <?php } else {
        
        $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'fkEventID')->dropDownList($eventDDL) ?>

        <p style='font-style: italic;'>If you don't see the event you are invoicing for, please contact BCM.</p>

        <div class="form-group">
            <?= Html::submitButton('Next', ['class' => 'btn btn-success']) ?>
            <a href='<?= Yii::$app->urlManager->createURL(['site/index']) ?>'>Cancel</a>
        </div>

        <?php ActiveForm::end(); 
        
    } ?>
    
</div>