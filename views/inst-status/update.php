<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorStatus */

$this->title = "Update Instructor Status ID #{$model->pkInstructorStatus}";
$this->params['breadcrumbs'][] = ['label' => 'Instructor Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = "Update ID #{$model->pkInstructorStatus}";
?>
<div class="instructor-status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['inst-status']) ?>'>Cancel</a>


</div>
