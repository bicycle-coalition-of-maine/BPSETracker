<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Config */

$this->title = 'Update Configuration: ' . $model->pkOptionName;
$this->params['breadcrumbs'][] = ['label' => 'Configuration', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update ' . $model->pkOptionName;
?>
<div class="config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['config']) ?>'>Cancel</a>

</div>
