<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;

$thisURL = Yii::$app->urlManager->createURL(['invoice/index']);
$checkAll = (Yii::$app->request->get('show') == 'N' ? '' : ' checked');
$checkNew = (Yii::$app->request->get('show') == 'N' ? ' checked' : '');

?>
<div class="invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <label class="radio-inline">
            <input type="radio" name="show" value="A" <?= $checkAll ?>
                   onchange="javascript:window.location.href='<?= $thisURL ?>';">All
        </label>
        <label class="radio-inline">
            <input type="radio" name="show" value="N" <?= $checkNew ?>
                   onchange="javascript:window.location.href='<?= $thisURL ?>&show=N';">New
        </label>    </p>
    <p>
        <?= Html::a('Create Invoice', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //'pkInvoiceID',
            [
                'attribute' => 'pkInvoiceID',
                'format' => 'raw',
                'value' => function($data) {
                    $url = Yii::$app->urlManager->createURL(['invoice/view', 'id' => $data->pkInvoiceID]);
                    return "<a href='$url'>{$data->pkInvoiceID}</a>";
                }
            ],
            [
                'label' => 'Event Date',
                'value' => function($data) {
                    return Yii::$app->globals->formatSQLDate($data->event->eventDate, 'n/d/Y');
                }
            ],
            [
                'label' => 'At',
                'format' => 'raw',
                'value' => function($data) {
                    $url = Yii::$app->urlManager->createURL(['org/view', 'id' => $data->event->fkOrgID]);
                    return "<a href='$url'>{$data->event->organization->name}</a>";
                }
            ],
            [
                'label' => 'Instructor',
                'format' => 'raw',
                'value' => function($data) {
                    $url = Yii::$app->urlManager->createURL(['person/view', 'id' => $data->instructor->pkPersonID]);
                    return "<a href='$url'>{$data->instructor->firstName} {$data->instructor->lastName}</a>";
                }
            ],
            [
                'attribute' => 'invoiceDate',
                'value' => function($data) {
                    return Yii::$app->globals->formatSQLDate($data->invoiceDate, 'n/d/Y');
                }
            ],
            [
                'attribute' => 'invoiceAmount',
                'value' => function($data) {
                    return "\${$data->invoiceAmount}";
                    /* Would have been better to configure Yii to use "$" 
                     * so we could just say 'attribute:currency', but couldn't
                     * quickly figure out how. 9/2/19
                     */
                }
            ],
            [
                'label' => 'Approved?',
                'value' => function($data) {
                    return $data->approveDate ? 'Yes' : 'No';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {approve} {update}',
                'buttons' => [
                    'approve' => function($url, $model, $key) {
                        return $model->approveDate ? '' : Html::a('<span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span>', $url);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
