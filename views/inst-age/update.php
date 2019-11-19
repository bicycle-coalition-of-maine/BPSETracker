<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorAge */

$this->title = 'Update Age Group #' . $model->pkInstructorAgeGroup;
$this->params['breadcrumbs'][] = ['label' => 'Age Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = "Update ID #{$model->pkInstructorAgeGroup}";
?>
<div class="instructor-age-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['inst-age']) ?>'>Cancel</a>

</div>
