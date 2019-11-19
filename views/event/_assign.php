<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;

// Define the instructor list that will be used for the dropdowns
$instList = array();
//$instList[0] = '<Select an instructor>';
foreach ($instructors->getModels() as $iModel) {
    $fmtPhone = substr( $iModel['phone'], 0, 3 ) . '-' 
            . substr( $iModel['phone'], 3, 3 ) . '-'
            . substr( $iModel['phone'], 6, 4 );
    $instList[$iModel['pkPersonID']] 
            = "{$iModel['lastName']}, {$iModel['firstName']}: {$iModel['city']}, {$iModel['county']} County ({$iModel['email']}, {$fmtPhone}) {$iModel['isSameCounty']}";
            //= "{$iModel['county']} County: {$iModel['lastName']}, {$iModel['firstName']}, {$iModel['city']} ({$iModel['email']}, {$fmtPhone})";
}

?>

<div class="event-form">

    <?php  // Prior attempt at time picker stuff

    //        echo $form->field($model, 'startTime')
    //                    ->label('From')
    ////                        ->format('h:i A')
    //                    ->widget(\janisto\timepicker\TimePicker::className(), [
    //                        'mode' => 'time',
    //                        'clientOptions'=>[
    //                            'timeFormat' => 'hh:mm TT', // https://trentrichardson.com/examples/timepicker/#tp-formatting
    //                            'showSecond' => false,
    //                            ]
    //                        ]);

    //        echo $form->field($model, 'endTime')
    //                    ->label('To')
    ////                        ->format('h:i A')
    //                    ->widget(\janisto\timepicker\TimePicker::className(), [
    //                        'mode' => 'time',
    //                        'clientOptions'=>[
    //                            'timeFormat' => 'hh:mm TT',
    //                            'showSecond' => false,
    //                            ]
    //                        ]);
    ?>

<!-- 
        <label class="control-label" for="event-eventdate">Confirmed Date</label>
        <input type="text" id="event-eventdate" name="Event[eventDate]" size="10" value="<?= Yii::$app->globals->formatSQLDate($model->eventDate, "n/j/Y" ) ?>">
-->

<div class='row'>
    <div class='col-sm-3'>
        <?= 
            $form->field($model, 'eventDate')
                ->label('Event Date')
                ->widget(\yii\jui\DatePicker::class,
                        ['dateFormat' => 'M/d/yyyy']
                        );
        ?>
    </div>
    <div class='col-sm-2'>
        <label class="control-label" for="event-starttime">From</label>
        <input type="text" id="event-starttime" name="Event[startTime]" size="6" value="<?= Yii::$app->globals->formatSQLDate($model->startTime, "g:i A" ) ?>">
    </div>
    <div class='col-sm-2'>
        <label class="control-label" for="event-starttime">to</label>
        <input type="text" id="event-endtime" name="Event[endTime]" size="6" value="<?= Yii::$app->globals->formatSQLDate($model->endTime, "g:i A" ) ?>">
    </div>
</div> <!-- row -->
    
   <?php
        
        $instCount = 0;
        echo "<p style='font-weight: bold'>Instructor(s):</p>";
        foreach( $model->staffing as $staffing )
        {
            ?>
            <div class="form-group field-staffing-fkpersonid required">
            <select id="staffing[<?= $staffing->pkStaffingID ?>]" name="staffing[<?= $staffing->pkStaffingID ?>]" class="form-control" aria-required="true">
            <option value="0">&lt; Select an instructor &gt;</option>
            <?php
            foreach( array_keys($instList) as $instID)
            {
                $selected = ( $instID == $staffing->fkPersonID ? " selected" : "" );
                echo "<option value='{$instID}'{$selected}>{$instList[$instID]}</option>\n";
            }
            echo "</select></div>";
            ++$instCount;
        }
    
        if( $instCount )
        {
            echo "<p style='font-weight: bold'>Additional Instructor:</p>";
        }
    ?>

    <div class="form-group field-staffing-fkpersonid required">
    <select id="staffing[0]" name="staffing[0]" class="form-control" aria-required="true">
    <option value="0">&lt; Select an instructor &gt;</option>
        
    <?php
        foreach( array_keys($instList) as $instID)
            echo "<option value='{$instID}'>{$instList[$instID]}</option>\n";
        echo "</select></div>";

        echo $form->field($model, 'notes')->textarea(['rows' => 6]);
    ?>
    
</div>
