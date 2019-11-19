<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EventSetting */

$this->title = 'Create Event Setting';
$this->params['breadcrumbs'][] = ['label' => 'Event Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-setting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['event-setting']) ?>'>Cancel</a>

</div>
