<?php

use yii\web\Session;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Request;

/* @var $this yii\web\View */
/* @var $title string */
/* @var $model app\models\Request */
/* @var $person app\models\Person */
/* @var $matchLevel integer */

$this->title = $title;

$yesSelect = ($matchLevel == 2 ? 'checked' : '');
$noSelect = ($matchLevel == 1 ? 'checked' : '');
?>

<div class='request-index'>

    <h1><?= $this->title ?></h1>
    
    <h2>Personal confirmation</h2>
    
    <p>The personal information you entered partially matches this person:</p>
    
    <p style='font-weight: bold;'><?= $person->firstName ?> <?= $person->lastName ?>,
        <?= $model->MaskedEmail($person->email) ?>, 
        <?= $model->MaskedPhone($person->phone) ?>
    </p>
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'fkContactID')->hiddenInput(['value' => $person->pkPersonID])->label(false); ?>
    
    <p>
        <input type='radio' name='ThisIsMe' value='yes' <?= $yesSelect ?>>
        Yes, that's me. Please update my information to what I have entered below.<br>

        <input type='radio' name='ThisIsMe' value='no' <?= $noSelect ?>>
        No, that's not me. (You will be entered as a new person in our database.)
    </p>
    
    <p>Please confirm your information again:</p>

    <div class="row">
        <?= Yii::$app->globals->wrapInColumn(3, $form->field($model, 'firstName')->textInput()->label('Your first name')) ?>
        <?= Yii::$app->globals->wrapInColumn(3, $form->field($model, 'lastName')->textInput()) ?>        
    </div>
    <div class="row">
        <?= Yii::$app->globals->wrapInColumn(3, $form->field($model, 'email')->textInput()) ?>
        <?= Yii::$app->globals->wrapInColumn(2, $form->field($model, 'phone')->textInput()) ?>
        <?= Yii::$app->globals->wrapInColumn(1, $form->field($model, 'phoneExt')->textInput()) ?>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton('Next', ['class' => 'btn btn-success']) ?>
        <a href='<?= Yii::$app->urlManager->createURL(['site/index']) ?>'>Cancel</a>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>
