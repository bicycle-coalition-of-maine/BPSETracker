<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EventAge */

$this->title = 'Create Event Age Group';
$this->params['breadcrumbs'][] = ['label' => 'Event Age Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-age-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['event-age']) ?>'>Cancel</a>

</div>
