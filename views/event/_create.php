<?php

use yii\helpers\ArrayHelper;
use app\models\Person;
use app\models\EventEventType;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */

?>

<?= $form->field($model, 'fkOrgID')->dropDownList($orgs) ?>
        
<?= $form->field($model, 'need')->textarea() ?>

<div class='row'>
    <div class='col-sm-3'>
        <?= $form->field($model, 'ageDescription')->textInput(['maxlength' => true]) ?>
    </div>
    <div class='col-sm-2'>
        <?= $form->field($model, 'presentations')->textInput(['maxlength' => true])->label('# Presentations') ?>
    </div>
    <div class='col-sm-2'>
        <?= $form->field($model, 'participation')->textInput(['maxlength' => true]) ?>
    </div>
    <div class='col-sm-4'>
        <?= $form->field($model, 'datetimes')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<?= $form->field($model, 'comments')->textarea() ?>
