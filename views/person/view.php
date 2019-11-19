<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\Contact;

/* @var $this yii\web\View */
/* @var $model app\models\Person */

$this->title = "{$model->firstName} {$model->lastName}";
$this->params['breadcrumbs'][] = ['label' => 'People', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="person-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Update', ['update', 'id' => $model->pkPersonID], ['class' => 'btn btn-primary']) ?></p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pkPersonID',
            'isActive:boolean',
//            [
//                'attribute' => 'isActive',
//                'value' => function($data) {
//                    return $data->isActive ? 'Yes' : 'No';
//                }
//            ],
            'firstName',
            'lastName',
            'email:email',
            [
                'label' => 'Phone',
                'value' => $model->formattedPhone('(')
            ],
            'phoneExt',
            'address1',
            'address2',
            'city',
            'state',
            'zipcode',
            'county',
            'isStaff:boolean',
            'isContact:boolean',
            'isAdmin:boolean',
        ],
    ]) ?>

    <h2>Organizations</h2>

    <?php 
        
        echo GridView::widget([
            'dataProvider' => $orgs,
            'columns' => [
                [
                    'label' => 'Name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $url = Yii::$app->urlManager->createURL(['org/view', 'id' => $data->pkOrgID ]);
                        return "<a href='$url'>{$data->name}</a>";
                    }
                ],
                [
                    'label' => 'Title',
                    'value' => function ($data) {
                        return (Contact::find()
                                ->where([
                                    'fkPersonID' => Yii::$app->globals->var['fkPersonID'],
                                    'fkOrgID' => $data->pkOrgID
                                    ])
                                ->one()
                               )->title;
                    }
                ],
                'city'
                ]
        ]); 

        if($model->isStaff) {
            
            echo "<h2>Events</h2>";

            echo GridView::widget([
                'dataProvider' => $events,
                'columns' => [ 
                    [
                        'label' => 'ID',
                        'value' => function($data) {
                            $url = Yii::$app->urlManager->createURL(['event/view', 'id' => $data->pkEventID ]);
                            return "<a href='{$url}'>{$data->pkEventID}</a>";
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
                            $url = Yii::$app->urlManager->createURL(['org/view', 'id' => $data->organization->pkOrgID ]);
                            return "<a href='{$url}'>{$data->organization->name}</a>";
                        },
                        'format' => 'raw'
                    ],
                    'city' 
                ]
            ]);
        }
     ?>

</div>
