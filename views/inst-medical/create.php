<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorMedical */

$this->title = 'Create Medical Knowledge';
$this->params['breadcrumbs'][] = ['label' => 'Medical Knowledge', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instructor-medical-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['inst-medical']) ?>'>Cancel</a>

</div>
