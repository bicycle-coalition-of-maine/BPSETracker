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
    
    <p style='font-weight: bold;'><?= $person->firstName ?> <?= $person->lastName ?>, <?= $person->email ?>, <?= $person->formattedPhone('(') ?></p>
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'fkContactID')->hiddenInput(['value' => $person->pkPersonID])->label(false); ?>
    
    <p>
        <input type='radio' name='ThisIsMe' value='yes' <?= $yesSelect ?>>
        Yes, that's me. Please update my information in your database.<br>

        <input type='radio' name='ThisIsMe' value='no' <?= $noSelect ?>>
        No, that's not me. (But if you think we should already have you in
        our database anyway, please indicate that in the comments later in this form.)
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
