<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $title string */
/* @var $model app\models\InvoiceSubmission */
/* @var $displayEvent string */
/* @var $displayInvoice string */

$this->title = $title;

$this->registerCSSFile('/css/invoice-sub.css');
$this->registerJsFile('/js/InvoiceSubConfirm.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?>

<div class='request-confirm'>
    
    <h1><?= $this->title ?></h1>
    
    <?php if(Yii::$app->session->get('nf')) {
        echo "<p style='color: red;'>That email address is not correct. Please "
        . "check it and try again, or contact the Bicycle Coalition.</p>";
        Yii::$app->session->remove('nf');
    } ?>
    
    <?php if(Yii::$app->session->get('ns')) {
        echo "<p style='color: red;'>There was a problem saving your "
        . "information. Please try again, or contact the Bicycle Coalition.</p>";
        Yii::$app->session->remove('ns');
    } ?>
    
    <h2>Event Details</h2>

    <?= $displayEvent ?>
    
    <h2>Invoice Details</h2>
    
    <?= $displayInvoice ?>
    
    <p style='margin-top: 1em; font-style: italic;'>
        If you need to correct anything, use your browser's Back button to 
        return to a previous screen. Otherwise, continue below to submit.
    </p>
    
    <h2>Confirmation</h2>
    
    <?php 
        $form = ActiveForm::begin();
        echo $form->field($model, 'accurate')->checkbox();
        echo $form->field($model, 'email');
    ?>
   
    <div class="form-group">
        <?= Html::submitButton('Submit Invoice', ['class' => 'btn btn-success']) ?>
        <a href='<?= Yii::$app->urlManager->createURL(['site/index']) ?>'>Cancel</a>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>