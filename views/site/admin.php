<?php

/* @var $this yii\web\View */

?>

<div class="site-index">

    <a href='<?= Yii::$app->urlManager->createURL('person') ?>' class='btn btn-primary'>People</a>
    <a href='<?= Yii::$app->urlManager->createURL('org') ?>' class='btn btn-primary'>Organizations</a>
    <a href='<?= Yii::$app->urlManager->createURL('event') ?>' class='btn btn-primary'>Events</a>
    <a href='<?= Yii::$app->urlManager->createURL('invoice') ?>' class='btn btn-primary'>Invoices</a>
    
    <div class="jumbotron" style='padding-top: 15px; padding-bottom: 0;'>
        <img src="/images/Banner.png" class="img-rounded img-responsive">
        
        <h1 style="font-weight: bold;">Bicycle/Pedestrian Safety Education Event Tracker</h1>
    </div>

    <div class="body-content">

        <h2>Welcome to the BPSE Event Tracker!</h2>
        
        <ul>
            <li>To edit primary data, use the buttons above or the <b>Edit</b> menu.</li>
            <li>To edit codes and master data, use the <b>Edit</b> menu.</li>
            <li>To assign instructors, approve invoices, and run reports, use the <b>Actions</b> menu.</li>
            <li>For technical support, contact <a href='mailto:johnbrooking4@gmail.com'>John Brooking</a>.</li>
        </ul>

    </div>
</div>
