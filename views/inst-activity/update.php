<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorActivity */

$this->title = "Update Types of Instruction ID #{$model->pkInstructorActivity}";
$this->params['breadcrumbs'][] = ['label' => 'Instructor Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = "Update ID #{$model->pkInstructorActivity}";
?>
<div class="instructor-activity-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['inst-activity']) ?>'>Cancel</a>

</div>
