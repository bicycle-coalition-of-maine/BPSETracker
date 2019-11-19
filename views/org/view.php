<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\Contact;

/* @var $this yii\web\View */
/* @var $model app\models\Organization */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Organizations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="organization-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Update', ['update', 'id' => $model->pkOrgID], ['class' => 'btn btn-primary']) ?></p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pkOrgID',
            'name',
            'address1',
            'address2',
            'city',
            'state',
            'zipcode',
            'county',
        ],
    ]) ?>

        <h2>Contacts</h2>

	<?= GridView::widget([
		'dataProvider' => $contacts,
		'columns' => [
                    [
                        'label' => 'Name',
                        'value' => function ($data) {
                            $url = Yii::$app->urlManager->createURL(['person/view', 'id' => $data->pkPersonID ]);
                            return "<a href='{$url}'>{$data->firstName} {$data->lastName}</a>";
                        },
                        'format' => 'raw',
                    ],
                    [
                        'label' => 'Title',
                        'value' => function ($data) {
                            return (Contact::find()
                                    ->where([
                                        'fkOrgID' => Yii::$app->globals->var['fkOrgID'],
                                        'fkPersonID' => $data->pkPersonID
                                        ])
                                    ->one()
                                   )->title;
                        }
                    ],
                    [
                        'label' => 'Phone',
                        'value' => function($data) {
                            return $data->formattedPhone('(');
                        },
                    ],
                    [
                        'label' => 'Email',
                        'value' => function($data) {
                            return "<a href='mailto:{$data->email}'>{$data->email}</a>";
                        },
                        'format' => 'raw'
                    ],
                ],
	]); ?>
		
	<h2>Events</h2>
	
	<?= GridView::widget([
		'dataProvider' => $events,
		'columns' => [ 
//                    [
//                        'label' => 'ID',
//                        'value' => function($data) {
//                            $url = Yii::$app->urlManager->createURL(['event/view', 'id' => $data->pkEventID ]);
//                            return "<a href='{$url}'>{$data->pkEventID}</a>";
//                        },
//                        'format' => 'raw'
//                    ],
                    [
                        'label' => 'Event Type',
                        'value' => function($data) {
                            $url = Yii::$app->urlManager->createURL(['event/view', 'id' => $data->pkEventID ]);
                            return "<a href='{$url}'>{$data->eventTypeString}</a>";
                        },
                        'format' => 'raw'
                    ],              
//                    [
//                        'label' => 'Event Type(s)',
//                        'value' => function($data) {
//                            return $data->eventTypeString;
//                        }
//                    ],
                    [
                        'attribute' => 'requestDateTime',
                        'format' => ['date', 'M/d/Y']
                    ],                    
                    [
                        'attribute' => 'eventDate',
                        'format' => ['date', 'M/d/Y']
                    ],
                    [
                        'label' => 'Contact',
                        'value' => function($data) {
                            $url = Yii::$app->urlManager->createURL(['person/view', 'id' => $data->contact->pkPersonID ]);
                            return "<a href='$url'>{$data->contact->firstName} {$data->contact->lastName}</a>";
                        },
                        'format' => 'raw'
                    ],
                    [
                        'label' => 'Staffing',
                        'value' => function($data) {
                            return $data->staffingString;
                        },
                        'format' => 'raw'
                    ],
                ],
//            'model' => $model->events,
//            'attributes' => [ 'requestDateTime', 'city'],
	]); ?>
        
</div>
