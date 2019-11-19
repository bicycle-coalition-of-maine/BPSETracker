<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorRideType */

$this->title = 'Create Riding Discipline';
$this->params['breadcrumbs'][] = ['label' => 'Riding Discipline', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instructor-ride-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['inst-ride-type']) ?>'>Cancel</a>

</div>
