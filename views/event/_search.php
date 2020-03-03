<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EventSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pkEventID') ?>

    <?= $form->field($model, 'requestDateTime') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'fkOrgID') ?>

    <?= $form->field($model, 'isAtOrgAddress')->checkbox() ?>

    <?php // echo $form->field($model, 'address1') ?>

    <?php // echo $form->field($model, 'address2') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'zipcode') ?>

    <?php // echo $form->field($model, 'county') ?>

    <?php // echo $form->field($model, 'fkPersonID') ?>

    <?php // echo $form->field($model, 'otherType') ?>

    <?php // echo $form->field($model, 'fkEventAgeID') ?>

    <?php // echo $form->field($model, 'ageDescription') ?>

    <?php // echo $form->field($model, 'need') ?>

    <?php // echo $form->field($model, 'participation') ?>

    <?php // echo $form->field($model, 'datetimes') ?>

    <?php // echo $form->field($model, 'presentations') ?>

    <?php // echo $form->field($model, 'fkPastInstructor') ?>

    <?php // echo $form->field($model, 'comments') ?>

    <?php // echo $form->field($model, 'eventDate') ?>

    <?php // echo $form->field($model, 'startTime') ?>

    <?php // echo $form->field($model, 'endTime') ?>

    <?php // echo $form->field($model, 'isBike')->checkbox() ?>

    <?php // echo $form->field($model, 'isPed')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
