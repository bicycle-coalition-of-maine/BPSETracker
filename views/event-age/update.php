<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EventAge */

$this->title = 'Update Event Age Group ID #' . $model->pkEventAgeID;
$this->params['breadcrumbs'][] = ['label' => 'Event Age Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update ID #' . $model->pkEventAgeID;
?>
<div class="event-age-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['event-age']) ?>'>Cancel</a>
    
</div>
