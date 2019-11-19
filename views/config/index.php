<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Configuration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'pkOptionName',
            'label',
            'intValue',
            'strValue',
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update}' ],
        ],
    ]); ?>
</div>
