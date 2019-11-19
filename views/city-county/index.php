<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CityCountySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'City Counties';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-county-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create City County', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'city',
            'county',
        ],
    ]); ?>
</div>
