<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'People';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
            $checkInst = ( Yii::$app->request->get('show') == 'I' ? " checked" : "" );
            $checkContact = ( Yii::$app->request->get('show') == 'C' ? " checked" : "" );
            $checkAdmin = ( Yii::$app->request->get('show') == 'A' ? " checked" : "" );
            $checkAll = ( Yii::$app->request->get('show') != 'I' 
                            && Yii::$app->request->get('show') != 'C'
                            && Yii::$app->request->get('show') != 'A'
                          ? " checked" 
                          : "" 
                        );
        ?>
        <label class="radio-inline"><input type="radio" name="show" value="I" <?= $checkInst ?> onchange="javascript:window.location.href='/index.php?r=person&show=I';">Instructors</label>
        <label class="radio-inline"><input type="radio" name="show" value="C" <?= $checkContact ?> onchange="javascript:window.location.href='/index.php?r=person&show=C';">Contacts</label>
        <label class="radio-inline"><input type="radio" name="show" value="A" <?= $checkAdmin ?> onchange="javascript:window.location.href='/index.php?r=person&show=A';">Admins</label>
        <label class="radio-inline"><input type="radio" name="show" value="B" <?= $checkAll ?> onchange="javascript:window.location.href='/index.php?r=person';">Everyone</label>
    </p>
    
    <p>
        <?= Html::a('Create Person', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'pkPersonID',
            [
                'attribute' => 'lastName',
                'value' => function ($data) {
                    $url = Yii::$app->urlManager->createURL(['person/view', 'id' => $data->pkPersonID ]);
                    return "<a href='{$url}'>{$data->lastName}</a>";
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'firstName',
                'value' => function ($data) {
                    $url = Yii::$app->urlManager->createURL(['person/view', 'id' => $data->pkPersonID ]);
                    return "<a href='{$url}'>{$data->firstName}</a>";
                },
                'format' => 'raw',
            ],
            'email:email',
            [
                'attribute' => 'phone',
                'value' => function($data) { 
                    return $data->formattedPhone('(');
                }
            ],
            'city',
            [ 'attribute' => 'isStaff', 'format' => 'boolean', 'filter' => false ],
            [ 'attribute' => 'isContact', 'format' => 'boolean', 'filter' => false ],
            [ 'attribute' => 'isActive', 'format' => 'boolean', 'filter' => false ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
            ],
        ],
    ]); ?>
</div>
