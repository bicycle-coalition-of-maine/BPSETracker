<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorMechanical */

$this->title = 'Update Mechanical Knowledge ID #' . $model->pkInstructorMechanical;
$this->params['breadcrumbs'][] = ['label' => 'Instructor Mechanical', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update ID #' . $model->pkInstructorMechanical;
?>
<div class="instructor-mechanical-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['inst-mechanical']) ?>'>Cancel</a>

</div>
