<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $title string */
/* @var $model app\models\InvoiceSubmission */

$this->title = $title;

$this->registerJsFile('/js/InvoiceSubDetail.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCSSFile('/css/invoice-sub.css');

?>

<div class='request-detail'>

    <h1><?= $this->title ?></h1>
        
    <h2>Event Details</h2>

    <?= $display ?>
    <p style='margin-top: 1em; font-style: italic;'>
        If any of these details are incorrect, please note the corrections in the comments below.
    </p>
    
    <h2>Your Details</h2>
    
    <?php $form = ActiveForm::begin(); ?>

    <div class='row'>
        <?php
            echo Yii::$app->globals->wrapInColumn(5, $form->field($model, 'presentations'));
            echo Yii::$app->globals->wrapInColumn(5, $form->field($model, 'participants'));
        ?>        
    </div>
    
    <?= $form->field($model, 'topics')->radioList([
        'B' => 'Bicycle Safety Training ONLY',
        'P' => 'Pedestrian Safety Training ONLY',
        'BP' => 'Bicycle + Pedestrian Safety Training',
    ], ['separator' => '&nbsp;&nbsp;&nbsp;&nbsp;']) ?>
    
    <?= $form->field($model, 'atSchool')->radioList([
        '1' => 'Yes', '0' => 'No'
        ], ['separator' => '&nbsp;']) ?>
    
    <input type='hidden' id='mileage' value='<?= $mileage ?>'/>
        
    <table>
        <tr>
            <td><?= $form->field($model, 'hours') ?></td>
            <td class='pad'>*</td>
            <td><?= $form->field($model, 'fkRateID')->dropDownList($rates) ?></td>
            <td class='pad'>=</td>
            <td style='font-weight: bold;'><input id='compensation' size='4' class='calc' readonly/></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'miles') ?></td>
            <td class='pad'>*</td>
            <td><b>Mileage Rate:</b> $<?= $mileage ?>/mile</td>
            <td class='pad'>=</td>
            <td style='font-weight: bold;'><input id='mileage-due' size='4' class='calc' readonly/></td>
        </tr>
        <tr>
            <td class='calc' colspan='4' style='text-align: right;'>Total Invoiced:</td>
            <td style='font-weight: bold;'><input id='invoice-total' size='4' class='calc' readonly/></td>
        </tr>
    </table>
    
    <?= $form->field($model, 'comments')->textarea() ?>
    
    <div class="form-group">
        <?= Html::submitButton('Next', ['class' => 'btn btn-success']) ?>
        <a href='<?= Yii::$app->urlManager->createURL(['site/index']) ?>'>Cancel</a>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>