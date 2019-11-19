<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorRideType */

$this->title = "Update Riding Discipline ID #{$model->pkInstructorRideType}";
$this->params['breadcrumbs'][] = ['label' => 'Instructor Ride Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = "Update ID #{$model->pkInstructorRideType}";
?>
<div class="instructor-ride-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['inst-ride-type']) ?>'>Cancel</a>

</div>
