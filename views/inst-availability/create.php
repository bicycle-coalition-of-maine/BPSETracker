<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorAvailability */

$this->title = 'Create Instructor Availability';
$this->params['breadcrumbs'][] = ['label' => 'Instructor Availabilities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instructor-availability-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['inst-status']) ?>'>Cancel</a>

</div>
