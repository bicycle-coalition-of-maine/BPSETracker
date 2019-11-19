<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EventSetting */

$this->title = 'Update Event Setting ID #' . $model->pkEventSettingID;
$this->params['breadcrumbs'][] = ['label' => 'Event Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update ID #' . $model->pkEventSettingID;
?>
<div class="event-setting-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['event-setting']) ?>'>Cancel</a>

</div>
