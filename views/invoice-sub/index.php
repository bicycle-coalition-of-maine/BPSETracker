<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $title string */
/* @var $model app\models\InvoiceSubmission */

$this->title = $title;
?>

<div class='request-index'>

    <h1><?= $this->title ?></h1>
    
    <?php if(Yii::$app->session->get('nf')) {
        echo "<p style='color: red;'>No instructor was found matching that "
        . "information. Please check your information and try again or contact "
        . "the Bicycle Coalition.</p>";
        Yii::$app->session->remove('nf');
    } ?>
    
    <p>
        This screen is for certified instructors with the Bicycle Coalition of 
        Maine's 
        <a href='https://www.bikemaine.org/education/youth-education/' target='_blank'>
            Bicycle/Pedestrian Safety Education</a> program only. If you are
        not a BPSE instructor but are interested in becoming one, please read
        more about the program
        <a href='https://www.bikemaine.org/education/youth-education/' target='_blank'>
        here</a>.
    </p>
    
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?= Yii::$app->globals->wrapInColumn(3, $form->field($model, 'lastName')->textInput()) ?>
        <?= Yii::$app->globals->wrapInColumn(2, $form->field($model, 'pin')->textInput()) ?>        
    </div>

    <div class="form-group">
        <?= Html::submitButton('Next', ['class' => 'btn btn-success']) ?>
        <a href='<?= Yii::$app->urlManager->createURL(['site/index']) ?>'>Cancel</a>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>