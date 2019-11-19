<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EventType */

$this->title = 'Create Event Type';
$this->params['breadcrumbs'][] = ['label' => 'Event Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['event-type']) ?>'>Cancel</a>

</div>
