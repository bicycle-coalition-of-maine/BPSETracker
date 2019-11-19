<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\Models\Rate */

$this->title = 'Create Rate';
$this->params['breadcrumbs'][] = ['label' => 'Rates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$backLink = Yii::$app->urlManager->createURL(['rate/index']);
?>
<div class="rate-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <div class="btn btn-default"><a href="<?= $backLink ?>">Cancel</a></div>

</div>
