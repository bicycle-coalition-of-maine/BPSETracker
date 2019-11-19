<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorAvailability */

$this->title = "Update Instructor Availability ID #{$model->pkInstructorAvailability}";
$this->params['breadcrumbs'][] = ['label' => 'Instructor Availability', 'url' => ['index']];
$this->params['breadcrumbs'][] = "Update ID #{$model->pkInstructorAvailability}";
?>
<div class="instructor-availability-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['inst-availability']) ?>'>Cancel</a>

</div>
