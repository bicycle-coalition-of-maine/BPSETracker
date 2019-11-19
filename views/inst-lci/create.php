<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\instructorLci */

$this->title = 'Create LCI Status';
$this->params['breadcrumbs'][] = ['label' => 'LCI Status', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instructor-lci-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['inst-lci']) ?>'>Cancel</a>

</div>
