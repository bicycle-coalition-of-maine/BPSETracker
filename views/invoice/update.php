<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\Models\Invoice */

$this->title = 'Update Invoice #' . $model->pkInvoiceID;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => "Invoice #{$model->pkInvoiceID}", 'url' => ['view', 'id' => $model->pkInvoiceID]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="invoice-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        $eventUrl = Yii::$app->urlManager->createURL(['event/view', 'id' => $model->event->pkEventID]);
        $eventDt = Yii::$app->globals->formatSQLDate($model->event->eventDate, 'n/d/Y');
        $orgUrl = Yii::$app->urlManager->createURL(['org/view', 'id' => $model->event->fkOrgID]);
        $invURL = Yii::$app->urlManager->createURL(['invoice/view', 'id' => $model->pkInvoiceID]);
        echo "<h4><a href='$eventUrl'>{$model->event->eventTypeString}</a> @ <a href='$orgUrl'>{$model->event->organization->name}</a> on $eventDt</h4>";
    ?>

    <h3>Submitter Request</h3>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'Instructor',
                'format' => 'raw',
                'value' => function($data) {
                    $url = Yii::$app->urlManager->createURL(['person/view', 'id' => $data->event->fkPersonID]);
                    return "<a href='$url'>{$data->instructor->firstName} {$data->instructor->lastName}</a>";
                }
            ],
            [
                'attribute' => 'invoiceDate',
                'value' => function($data) {
                    return Yii::$app->globals->formatSQLDate($data->invoiceDate, 'n/d/y g:i A');
                }
            ],
            [
                'attribute' => 'fkRateRequested',
                'value' => function($data) {
                    $rate = $data->rateRequested;
                    return "\${$rate->rate} ({$rate->description})";
                }
            ],
            'miles',
            'submitterComments:ntext',
        ]
    ]) ?>
    
    <p><span style='color: blue; font-weight: bold;'>Initial Calculated Total</span>: 
        <b>$<?= sprintf('%1.2f', $model->payTotal) ?></b>
        (Base pay for <?= $model->hours ?> hours 
            @ $<?= sprintf('%1.2f', $model->rateRequested->rate) ?>/hour
            = $<?= sprintf('%1.2f', $model->payBase) ?>,
        mileage reimbursement for <?= $model->miles ?> miles 
        @ $<?= sprintf('%1.2f', $model->mileageRate) ?>/mile 
        = $<?= sprintf('%1.2f', $model->payMileage) ?>)
    </p>
    
    <h3>Approver Adjustments</h3>
    
    <div class="invoice-form">

        <p style='font-size: smaller; font-style: italic;'>Please note reasons for all changes in comments below.</p>

        <?php $form = ActiveForm::begin(); ?>

        <?= $this->render('_formUpdate', [
            'model' => $model,
            'form' => $form,
        ]) ?>
        <a href='<?= $invURL ?>'>Cancel</a>

        <?php ActiveForm::end(); ?>

    </div>

</div>
