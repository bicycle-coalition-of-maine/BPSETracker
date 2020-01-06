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

    <?php if($info->totalCount) { ?>
    
        <h2>Instructor Details</h2>

        <p><a class="btn btn-success" href="/index.php?r=inst-info%2Fcreate&id=<?= $model->pkPersonID ?>">New  Instructor Details</a>    </p>
        <?=
            GridView::widget([
                'dataProvider' => $info,
                'columns' => [
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'controller' => 'inst-info',
                        'template' => '{update}',
                        'urlCreator' => function($action, $model, $key, $index) {
                            return Yii::$app->urlManager->createURL(['inst-info/update', 'id' => $model->pkInstructorInfo]);
                            //return Url::to(['update', 'id' => $model->pkIntructorInfo]);
                        },
//                        'buttons' => ['update' => function($url, $model, $key) {
//                                                    return 
//                                                  }
//                                     ]
                    ],
                    'year',
                    [
                        'attribute' => 'fkInstStatus',
                        'value' => function($data) { return $data->status->instructorStatus; }
                    ],
                    [
                        'label' => 'Activities',
                        'value' => function($data) { return $data->activitiesString; }
                    ],
                    [
                        'label' => 'Ages',
                        'value' => function($data) { return $data->ageStrings; }
                    ],
                    [
                        'attribute' => 'isLargeGroupOK',
                        'format' => 'boolean',
                        'label' => 'Large Group?',
                    ],
                    [
                        'label' => 'Ride Types',
                        'value' => function($data) { return $data->rideTypeString; }
                    ],
                    'ridetype_other',
                    [
                        'label' => 'Availability',
                        'value' => function($data) { return $data->availabilityString; }
                    ],
                    'availability_other',
                    [
                        'attribute' => 'fkInstLCI',
                        'value' => function($data) { return $data->lci->instructorLCI; }
                    ],
                    [
                        'attribute' => 'fkInstMechanical',
                        'value' => function($data) { return $data->mechanical->instructorMechanical; }
                    ],
                    [
                        'attribute' => 'fkInstMedical',
                        'value' => function($data) { return $data->medical->instructorMedical; }
                    ],
                    [
                        'attribute' => 'isDirectContactOK',
                        'format' => 'boolean',
                        'label' => 'Direct Contact?',
                    ],
                    'comments',
                ]
            ])
        ?>
    
    <?php } // if any info records to show ?>
        
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
