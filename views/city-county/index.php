<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CityCountySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'City Attributes';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('/css/bcm.css');
?>
<div class="city-county-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Add City', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update}'],
            'city',
            'county',
            [
                'attribute' => 'pacts',
                'format' => 'boolean',
                'contentOptions' => function($model, $key, $index, $column) {
                    return ['class' => ($model->pacts ? 'Yes' : 'No')];
                },
            ],
            [
                'attribute' => 'bacts',
                'format' => 'boolean',
                'contentOptions' => function($model, $key, $index, $column) {
                    return ['class' => ($model->bacts ? 'Yes' : 'No')];
                },
            ],
            [
                'attribute' => 'focus21',
                'format' => 'boolean',
                'contentOptions' => function($model, $key, $index, $column) {
                    return ['class' => ($model->focus21 ? 'Yes' : 'No')];
                },
            ],
        ]
    ]); ?>
</div>
