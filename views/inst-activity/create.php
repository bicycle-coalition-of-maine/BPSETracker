<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorActivity */

$this->title = 'Create Instructor Activity';
$this->params['breadcrumbs'][] = ['label' => 'Instructor Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instructor-activity-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['inst-activity']) ?>'>Cancel</a>

</div>
