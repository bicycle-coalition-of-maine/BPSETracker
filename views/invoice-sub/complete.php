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

?>

<div class='request-complete'>
    
    <h1><?= $this->title ?></h1>
    
    <p>Your invoice has been submitted, and someone will respond soon.
        You should receive an email confirmation in a few minutes.</p>
    
    <p>You may now close this window, or <a href='<?= Yii::$app->urlManager->createURL(['invoice-sub/index']) ?>'>submit another invoice</a>.</p>
    
    <h2>Event Details</h2>

    <?= $displayEvent ?>
    
    <h2>Invoice Details</h2>
    
    <?= $displayInvoice ?>
        
</div>