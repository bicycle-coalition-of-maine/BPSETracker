<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Bicycle/Pedestrian Safety Education Event Tracker';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    
    <div class='row'>
        <div class='col-sm-2'>
            <img src='images/BCMLogo.png'>
        </div>
        <div class='col-sm-10'>
            <h1 style='text-align: center;'><?= Html::encode($this->title) ?></h1>
            <h2 style='text-align: center;'>GitHub Release 
                <a href='https://github.com/bicycle-coalition-of-maine/BPSETracker/releases/tag/v1.2.2' target='_blank'>v1.2.2</a>
            </h2>
        </div>
    </div>
    
    <p style='margin-top: 30px;'>
        
        The BPSE Event Tracker was written in 2019-2020 by 
        <a href='mailto:johnbrooking4@gmail.com'>John Brooking</a>, to assist
        the Bicycle Coalition of Maine with tracking, invoicing, and reporting
        on BPSE events. The source code is owned by the Bicycle Coalition of
        Maine, and is stored in 
        <a target='_blank' href='https://github.com/bicycle-coalition-of-maine/BPSETracker'>this 
        GitHub repository</a>. The repository also includes the current 
        <a target='_blank' href='https://github.com/bicycle-coalition-of-maine/BPSETracker/issues'>Issues 
        List</a>, and a 
        <a target='_blank' href='https://github.com/bicycle-coalition-of-maine/BPSETracker/wiki'>Wiki</a> 
        of mostly technical information.
        
    </p>
    
    <p><?= $adminMsg->strValue ?></p>
    
    <p><a href='/phpinfo.php'>Technical information</a></p>
    
</div>
