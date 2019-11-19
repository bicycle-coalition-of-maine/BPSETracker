<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrgSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Organizations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Organization', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'pkOrgID',
            [
                'attribute' => 'name',
                'value' => function ($data) {
                    $url = Yii::$app->urlManager->createURL(['org/view', 'id' => $data->pkOrgID ]);
                    return "<a href='{$url}'>{$data->name}</a>";
                },
                'format' => 'raw',
            ],
            'address1',
            //'address2',
            'city',
            //'state',
            //'zipcode',
            'county',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
            ],
        ],
    ]); ?>
</div>
