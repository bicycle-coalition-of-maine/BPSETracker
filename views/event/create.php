<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Event */

$this->title = 'Create Event';
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="event-form">

        <?php $form = ActiveForm::begin(); ?>
    
        <?= $this->render('_create', [
            'model' => $model,
            'form' => $form,
            'orgs' => $orgs,
        ]) ?>
        
        <?= $this->render('_update', [
            'model' => $model,
            'form' => $form,
            'eventTypes' => $eventTypes,
            'eventContact' => $eventContact,
            'eventAgeGroups' => $eventAgeGroups,
            'pastInstructor' => $pastInstructor,
        ]) ?>
        
        <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
        
        <?php ActiveForm::end(); ?>
        <a href='<?= Yii::$app->urlManager->createURL(['event']) ?>'>Cancel</a>

    </div>
</div>
