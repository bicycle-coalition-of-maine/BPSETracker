<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

?>

<div class="site-index">

    <p style="text-align: center;">
        <img src="/images/HeadsUpLogo.png" height="100">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <img src="/images/BCMLogoText.png" height="100">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <img src="/images/MeDOTLogo.png" height="100">
        <h1 style="text-align: center; font-weight: bold; margin: 1em;">
            BPSE / SRTS Request Form
        </h1>
    </p>
    
<!--    <div class="jumbotron" style='padding-top: 15px; padding-bottom: 0;'>
        <img src="/images/RequestBanner.jpg" class="img-rounded img-responsive">        
        <h1 style="font-weight: bold;">Bicycle/Pedestrian Safety Education Request Form</h1>
    </div>-->
    
    <p><?= $intro ?></p>
    
    <hr>
    
    <p style="text-align: center; font-weight: bold;">
        Click here to start request<br>
        <?= Html::a('REQUEST ASSISTANCE', ['request/index'], ['class' => 'btn btn-warning']) ?>
    </p>
    
</div>
