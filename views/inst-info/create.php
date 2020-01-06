<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorInfo */

$this->title = 'Create Instructor Details';
$personName = "{$person->firstName} {$person->lastName}";

$this->params['breadcrumbs'][] = ['label' => 'People', 'url' => ['person/index']];
$this->params['breadcrumbs'][] = ['label' => $personName, 'url' => ['person/view', 'id' => $model->fkPersonID]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="instructor-info-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <h2><?= $personName ?> (<?= $model->fkPersonID ?>)</h2> `

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <a href='<?= Yii::$app->urlManager->createURL(['person/view', 'id' => $model->fkPersonID]) ?>'>Cancel</a>

</div>
