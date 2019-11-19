<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'LCI Status';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instructor-lci-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create LCI Status', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'instructorLCI',
            'isActive:boolean',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>
