<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\Models\Invoice */

$this->title = "Invoice #{$model->pkInvoiceID}";
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="invoice-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        $eventUrl = Yii::$app->urlManager->createURL(['event/view', 'id' => $model->event->pkEventID]);
        $eventDt = Yii::$app->globals->formatSQLDate($model->event->eventDate, 'n/d/Y');
        $orgUrl = Yii::$app->urlManager->createURL(['org/view', 'id' => $model->event->fkOrgID]);
        echo "<h4><a href='$eventUrl'>{$model->event->eventTypeString}</a> @ <a href='$orgUrl'>{$model->event->organization->name}</a> on $eventDt</h4>";
    ?>
    
    <h3>Instructor Submission</h3>
    
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
            'isSchool:boolean',
            'isBike:boolean',
            'isPed:boolean',
            'presentations',
            'presentees',
            'hours',
            [
                'attribute' => 'fkRateRequested',
                'value' => function($data) {
                    $rate = $data->rateRequested;
                    return "\${$rate->rate} ({$rate->description})";
                }
            ],
            'miles',
            'submitterComments:ntext',
            [
                'attribute' => 'invoiceAmount',
                'value' => function($data) {
                    return "\${$data->invoiceAmount}";
                }
            ],
        ],
    ]) ?>

    <?php
    $updateBtn = Html::a('Update', ['update', 'id' => $model->pkInvoiceID], ['class' => 'btn btn-primary']);
    if($model->approveDate) { // If approved, show those details and Update button only
    
        echo "<h3>Approval</h3>";
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
            [
                'label' => 'Approved',
                'value' => function($data) {
                    return Yii::$app->globals->formatSQLDate($data->approveDate, 'n/d/y g:i A');
                }
            ],
            [
                'label' => 'By',
                'value' => function($data) {
                    return "{$data->approver->firstName} {$data->approver->lastName}";
                    return 'tbd';
                }
            ],
            [
                'attribute' => 'hourlyrate',
                'value' => function($data) {
                    return "\${$data->hourlyrate}";
                }
            ],
            'approverComments:ntext',
            ],
        ]);
    echo "<p>$updateBtn</p>";
    }
    else {                   // Not yet approved, show both Approval and Update buttons
        $approveBtn = Html::a('Approve', ['approve', 'id' => $model->pkInvoiceID], ['class' => 'btn btn-success']);
        echo "<p>$approveBtn $updateBtn</p>";
        echo "<p style='font-style: italic;'>Click Approve to approve without comment. Click Update if you need to comment and/or make changes.</p>";
    }
    
    ?>
    
</div>
