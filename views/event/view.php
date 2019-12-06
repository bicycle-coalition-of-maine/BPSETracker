<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = "{$model->eventTypeString} @ {$model->organization->name}";
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->pkEventID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->pkEventID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>        
    </p>

    <h2>Request Details</h2>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pkEventID',
            [
                'attribute' => 'requestDateTime',
                'value' => function($data) {
                    return Yii::$app->globals->formatSQLDate($data->requestDateTime, 'n/d/Y g:i A');
                }
            ],
            [
                'label' => 'Event Type(s)',
                'value' => function($data) {
                    return $data->eventTypeString;
                }
            ],                                        
            [
                'attribute' => 'Organization',
                'value' => function($data) {
                    $url = Yii::$app->urlManager->createURL(['org/view', 'id' => $data->fkOrgID ]);
                    return "<a href='$url'>{$data->organization->name}</a>";
                },
                'format' => 'raw'
            ],
            'address1',
            'address2',
            'city',
            'state',
            [
                'attribute' => 'zipcode',
                'value' => function($data) {
                    return str_pad($data->zipcode, 5, '0', STR_PAD_LEFT);
                }
            ],
            'county',
            'isAtOrgAddress:boolean',
            [
                'attribute' => 'fkPersonID',
                'value' => function($data) {
                    $url = Yii::$app->urlManager->createURL(['person/view', 'id' => $data->contact->pkPersonID ]);
                    return "<a href='$url'>{$data->contact->firstName} {$data->contact->lastName}</a>";
                },
                'format' => 'raw'
            ],
            'otherType',
            [
                'attribute' => 'fkEventAgeID',
                'value' => ($model->eventAge ? $model->eventAge->eventAge : '')
            ],
            'ageDescription',
            'need',
            'participation',
            'datetimes',
            'presentations',
            'hasHosted:boolean',
            [
                'attribute' => 'fkPastInstructor',
                'value' => function($data) {
                    if($data->pastInstructor) {
                        $url = Yii::$app->urlManager->createURL(['person/view', 'id' => $data->pastInstructor->pkPersonID ]);
                        return "<a href='$url'>{$data->pastInstructor->firstName} {$data->pastInstructor->lastName}</a>";                        
                    }
                    else {
                        return "(not set)";
                    }
                },
                'format' => 'raw'
            ],
            'comments',
        ],
    ]) ?>

    <h2>Confirmed Details</h2>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'eventDate',
                'value' => function($data) {
                    return Yii::$app->globals->formatSQLDate($data->eventDate, 'n/d/Y');
                }
            ],
            [
                'attribute' => 'startTime',
                'value' => function($data) {
                    return Yii::$app->globals->formatSQLDate($data->startTime, 'g:i A');
                }
            ],
            [
                'attribute' => 'endTime',
                'value' => function($data) {
                    return Yii::$app->globals->formatSQLDate($data->endTime, 'g:i A');
                }
            ],
            'isBike:boolean',
            'isPed:boolean',
            [
                'label' => 'Instructor(s)',
                'format' => 'raw',
                'value' => function($data) {
                    $returnList = array();
                    foreach($data->staffPeople as $staffPerson) {
                        $url = Yii::$app->urlManager->createURL(['person/view', 'id' => $staffPerson->pkPersonID ]);
                        $returnList[] = "<a href='$url'>{$staffPerson->firstName} {$staffPerson->lastName}</a>";
                    }                        
                    return implode(', ', $returnList);
                }
            ],
            'notes:ntext',
        ]
    ]) ?>
    
</div>
