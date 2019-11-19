<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Event', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'pkEventID',
                'value' => function($data) {
                    $url = Yii::$app->urlManager->createURL(['event/view', 'id' => $data->pkEventID ]);
                    return "<a href='$url'>{$data->pkEventID}</a>";
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'requestDateTime',
                'label' => 'Request Date',
                'format' => ['date', 'M/d/Y']
            ],
            [
                'attribute' => 'eventDate',
                'format' => ['date', 'M/d/Y']
            ],
            [
                'label' => 'Event Type(s)',
                'value' => function($data) {
                    return $data->eventTypeString;
                }
            ],                                        
            [
                'label' => 'Organization',
                'value' => function($data) { 
                    return "<a href='index.php?r=org/view&id={$data->fkOrgID}'>{$data->organization->name}</a>"; 
                },
                'format' => 'raw'
            ],
            'city',
            [
                'attribute' => 'fkPersonID',
                'value' => function($data) {
                    $url = Yii::$app->urlManager->createURL(['person/view', 'id' => $data->contact->pkPersonID ]);
                    return "<a href='$url'>{$data->contact->firstName} {$data->contact->lastName}</a>";
                },
                'format' => 'raw'
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
