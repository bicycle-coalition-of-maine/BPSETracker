<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\Models\Invoice */

$this->title = 'Create Invoice';
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'eventDDL' => $eventDDL,
        'instructorDDL' => $instructorDDL,
        'rateDDL' => $rateDDL,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['invoice']) ?>'>Cancel</a>
</div>
