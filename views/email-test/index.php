<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EmailTest */
?>

<div class='email-test-index'>
    
    <h1>Email Test</h1>
    
    <?php 
        $form = ActiveForm::begin();
        echo $form->field($model, 'to');
        echo $form->field($model, 'msg');
    ?>

    <div class="form-group">
        <?= Html::submitButton('Next', ['class' => 'btn btn-success']) ?>
        <a href='<?= Yii::$app->urlManager->createURL(['site/index']) ?>'>Cancel</a>
    </div>

    <?php ActiveForm::end(); ?>