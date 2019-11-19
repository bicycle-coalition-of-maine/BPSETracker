<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Organization */

$this->title = 'Update ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Organizations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->pkOrgID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="organization-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'people' => $people,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['org/view', 'id' => $model->pkOrgID]) ?>'>Cancel</a>

</div>
