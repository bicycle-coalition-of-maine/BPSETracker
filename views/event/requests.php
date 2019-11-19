<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'New Requests';
$this->params['breadcrumbs'][] = 'Requests';

?>
<div class="event-requests">

    <h1><?= Html::encode($this->title) ?></h1>
	
    <p>
        <?php 
            $checkAll = ( Yii::$app->request->get('showAll') == '1' ? " checked" : "" );
            $checkNew = ( $checkAll ? "" : " checked" );
        ?>
        <label class="radio-inline"><input type="radio" name="showAll" value="0" <?= $checkNew ?> onchange="javascript:window.location.href='/index.php?r=event%2Frequests';">New Requests Only</label>
        <label class="radio-inline"><input type="radio" name="showAll" value="1" <?= $checkAll ?> onchange="javascript:window.location.href='/index.php?r=event%2Frequests&showAll=1';">All Requests</label>
    </p>
    
    <?= GridView::widget([
                    'dataProvider' => $requests,

                    'columns' => [
                            [
                                'attribute' => 'requestDateTime',
                                'format' => ['date', 'M/d/Y']
                            ],
//                            [
//                                'label' => 'Event Type(s)',
//                                'value' => function($data) {
//                                        $typeNames = array();
//                                        foreach( $data->eventTypes as $row )
//                                                $typeNames[] = $row->eventType;
//                                        return join( ' + ', $typeNames );
//                                },
//                            ],
                            [
                                'label' => 'Event Type(s)',
                                'value' => function($data) {
                                    return $data->eventTypeString;
                                }
                            ],                                        
                            'datetimes',
                            'organization.name',
                            'city',
                            'county',
                            [
                                'label' => 'Contact Person',
                                'value' => function($data) { 
                                        return $data->contact->firstName . ' ' . $data->contact->lastName;
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{assign}',
                                'buttons' => [
                                    'assign' => function ($url, $model, $key) {
                                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                                            }
                                ]
                            ],
                    ]
            ]);
    ?>

</div>
