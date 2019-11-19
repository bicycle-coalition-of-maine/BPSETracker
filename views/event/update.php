<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = "{$model->eventTypeString} @ {$model->organization->name} ({$model->pkEventID})";
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['view', 'id' => $model->pkEventID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <h2>Request Details</h2>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'requestDateTime',
                'value' => function($data) {
                    return Yii::$app->globals->formatSQLDate($data->requestDateTime, 'n/d/Y g:i A');
                }
            ],
            'need',
            'ageDescription',
            'presentations',
            'participation',
            'datetimes',
            'comments',
    ]]);
    ?>
    
    <div class="event-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $this->render('_update', [
            'model' => $model,
            'form' => $form,
            'eventTypes' => $eventTypes,
            'eventContact' => $eventContact,
            'eventAgeGroups' => $eventAgeGroups,
            'pastInstructor' => $pastInstructor,
        ]) ?>

        <h2>Confirmed Details</h2>

        <?= $this->render('_assign', [
            'model' => $model,
            'form' => $form,
            'instructors' => $instructors,
        ]) ?>

        <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>
    <a href='<?= Yii::$app->urlManager->createURL(['event/view', 'id' => $model->pkEventID]) ?>'>Cancel</a>

    </div>
</div>
