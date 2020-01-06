<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

use app\models\InstructorStatus;
use app\models\InstructorLci;
use app\models\InstructorMechanical;
use app\models\InstructorMedical;
use app\models\InstructorActivity;
use app\models\InstructorAge;
use app\models\InstructorRidetype;
use app\models\InstructorAvailability;

/* @var $this yii\web\View */
/* @var $model app\models\InstructorInfo */
/* @var $form yii\widgets\ActiveForm */

// Wrap HTML content in a Bootstrap "col-sm-x" column class
function wrapInColumn($content, $cols) {
    return "<div class='col-sm-$cols'>$content</div>";
}

$genStatuses = InstructorStatus::find()
        ->select('instructorStatus')
        ->indexBy('pkInstructorStatus')
        ->where('isActive = 1')
        ->orderBy('instructorStatus')
        ->column();

$lciStatuses = InstructorLci::find()
        ->select('instructorLCI')
        ->indexBy('pkInstructorLCI')
        ->where('isActive = 1')
        ->orderBy('instructorLCI')
        ->column();

$mechKnowledge = InstructorMechanical::find()
        ->select('instructorMechanical')
        ->indexBy('pkInstructorMechanical')
        ->where('isActive = 1')
        ->orderBy('sequence')
        ->column();

$medKnowledge = InstructorMedical::find()
        ->select('instructorMedical')
        ->indexBy('pkInstructorMedical')
        ->where('isActive = 1')
        ->orderBy('sequence')
        ->column();

$activities = InstructorActivity::find()
        ->select('instructorActivity')
        ->indexBy('pkInstructorActivity')
        ->where('isActive = 1')
        ->orderBy('instructorActivity')
        ->column();

$ages = InstructorAge::find()
        ->select('instructorAgeGroup')
        ->indexBy('pkInstructorAgeGroup')
        ->where('isActive = 1')
        ->orderBy('sequence')
        ->column();

$rideTypes = InstructorRidetype::find()
        ->select('instructorRideType')
        ->indexBy('pkInstructorRideType')
        ->where('isActive = 1')
        ->orderBy('instructorRideType')
        ->column();

$availability = InstructorAvailability::find()
        ->select('instructorAvailability')
        ->indexBy('pkInstructorAvailability')
        ->where('isActive = 1')
        ->orderBy('sequence')
        ->column();
?>

<div class="instructor-info-form">

    <?php $form = ActiveForm::begin(); ?>
        
    <div class="row">

        <?php
            if(Yii::$app->controller->action->id == 'create')
                echo wrapInColumn( $form->field($model, 'year')->textInput(), 2 );
        ?>
        
        <?= wrapInColumn( $form->field($model, 'fkInstStatus')->dropdownList($genStatuses), 5 ) ?>

        <?= wrapInColumn($form->field($model, 'fkInstLCI')->dropDownList($lciStatuses), 5) ?>

    </div>
    
    <!-- ************************* Activities ************************* -->
    
    <div class="form-group">
        
        <input type="hidden" id="instructor_info_activity_CHANGED" name="instructor_info_activity_CHANGED" value="0">
        
        <label class="control-label">Activities</label> (check all that apply)<br/>
        
        <?php
            $selectedActivities = array_map(
                    function($row) { return $row['fkInstructorActivity']; },
                    (new \yii\db\Query())
                    ->select('fkInstructorActivity')
                    ->from(' instructor_info_activity')
                    ->where(['fkInstructorInfo' => $model->pkInstructorInfo])
                    ->all()
                );
                    
            foreach(array_keys($activities) as $activityKey)
            {
                $found = array_search($activityKey, $selectedActivities);
                $thisChecked = ((array_search($activityKey, $selectedActivities) !== FALSE ) ? 'checked' : '');
                ?>
                <input type='checkbox' value="1" <?= $thisChecked ?>
                       name='instructor_info_activity[<?= $activityKey?>]' 
                       onClick="document.getElementById('instructor_info_activity_CHANGED').value='1';"
                       > <?= $activities[$activityKey] ?> &nbsp;&nbsp;&nbsp;
                <?php
            }
        ?>
        
    </div>
    
    <!-- ************************* Age Groups ************************* -->
    
    <hr>
    
    <div class="form-group">
        
        <input type="hidden" id="instructor_info_ages_CHANGED" name="instructor_info_ages_CHANGED" value="0">
        
        <label class="control-label">Age Groups</label> (check all that apply)<br/>
        
        <?php
            $selectedAges = array_map(
                    function($row) { return $row['fkInstructorAgeGroup']; },
                    (new \yii\db\Query())
                    ->select('fkInstructorAgeGroup')
                    ->from('instructor_info_ages')
                    ->where(['fkInstructorInfo' => $model->pkInstructorInfo])
                    ->all()
                );
                    
            foreach(array_keys($ages) as $ageKey)
            {
                $found = array_search($ageKey, $selectedAges);
                $thisChecked = ((array_search($ageKey, $selectedAges) !== FALSE ) ? 'checked' : '');
                ?>
                <input type='checkbox' value="1" <?= $thisChecked ?>
                       name='instructor_info_ages[<?= $ageKey ?>]' 
                       onClick="document.getElementById('instructor_info_ages_CHANGED').value='1';"
                       > <?= $ages[$ageKey] ?> &nbsp;&nbsp;&nbsp;
                <?php                
            }
        ?>
        
    </div>
    
    <?= $form->field($model, 'isLargeGroupOK')->checkbox() ?>

    <!--
    Note: On these fields, I tried overriding the label with "->label('new label')",
    but for checkboxes, instead of replacing the label that follows the
    checkbox, it simply added the new label in front of the checkbox, while
    keeping the old one after it. So I had to change the label in the model
    instead, then override that in the summary to get a shorter one.
    -->
    
    <hr>
    
    <!-- ************************* Ride Types ************************* -->
    
    <div class="form-group">
        
        <input type="hidden" id="instructor_info_ridetypes_CHANGED" name="instructor_info_ridetypes_CHANGED" value="0">
        
        <label class="control-label">Riding Types</label> (check all that apply)<br/>
        
        <?php
            $selectedRideTypes = array_map(
                    function($row) { return $row['fkInstructorRideType']; },
                    (new \yii\db\Query())
                    ->select('fkInstructorRideType')
                    ->from(' instructor_info_ridetypes')
                    ->where(['fkInstructorInfo' => $model->pkInstructorInfo])
                    ->all()
                );
                    
            foreach(array_keys($rideTypes) as $rideTypeKey)
            {
                $found = array_search($rideTypeKey, $selectedRideTypes);
                $thisChecked = ((array_search($rideTypeKey, $selectedRideTypes) !== FALSE ) ? 'checked' : '');
                ?>
                <input type='checkbox' value="1" <?= $thisChecked ?>
                       name='instructor_info_ridetypes[<?= $rideTypeKey ?>]' 
                       onClick="document.getElementById('instructor_info_ridetypes_CHANGED').value='1';"
                       > <?= $rideTypes[$rideTypeKey] ?> &nbsp;&nbsp;&nbsp;
                <?php                                
            }
        ?>
        
    </div>
    
    <?= $form->field($model, 'ridetype_other')->textInput(['maxlength' => true]) ?>
    
    <hr>
    
    <!-- ************************* Availability ************************* -->
    
    <div class="form-group">
        
        <input type="hidden" id="instructor_info_available_CHANGED" name="instructor_info_available_CHANGED" value="0">
        
        <label class="control-label">Availability</label> (check all that apply)<br/>
        
        <?php
            $selectedAvailability = array_map(
                    function($row) { return $row['fkInstructorAvailable']; },
                    (new \yii\db\Query())
                    ->select('fkInstructorAvailable')
                    ->from(' instructor_info_available')
                    ->where(['fkInstructorInfo' => $model->pkInstructorInfo])
                    ->all()
                );
                    
            foreach(array_keys($availability) as $availKey)
            {
                $found = array_search($availKey, $selectedAvailability);
                $thisChecked = ((array_search($availKey, $selectedAvailability) !== FALSE ) ? 'checked' : '');
                ?>
                <input type='checkbox' value="1" <?= $thisChecked ?>
                       name='instructor_info_available[<?= $availKey ?>]' 
                       onClick="document.getElementById('instructor_info_available_CHANGED').value='1';"
                       > <?= $availability[$availKey] ?> &nbsp;&nbsp;&nbsp;
                <?php                                
            }
        ?>
        
    </div>
    
    <?= $form->field($model, 'availability_other')->textInput(['maxlength' => true]) ?>

    <hr>
    
    <div class="row">
        
        <?= wrapInColumn($form->field($model, 'fkInstMechanical')->dropDownList($mechKnowledge), 6 ) ?>

        <?= wrapInColumn($form->field($model, 'fkInstMedical')->dropDownList($medKnowledge), 6 ) ?>

    </div>
    
    <?= $form->field($model, 'isDirectContactOK')->checkbox() ?>

    <?= $form->field($model, 'comments')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
