<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorMechanical */

$this->title = 'Create Mechanical Knowledege';
$this->params['breadcrumbs'][] = ['label' => 'Mechanical Knowledege', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instructor-mechanical-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['inst-mechanical']) ?>'>Cancel</a>

</div>
