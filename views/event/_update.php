<?php

use app\models\CityCounty;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $form yii\widgets\ActiveForm */

?>

<div class='row'>
    <div class='col-sm-9'>
        <label>Event Type(s):</label><br/>&nbsp;&nbsp;

        <?php
            $arrIDs = array_map(
                function($et) { return $et->pkEventTypeID; }, 
                $model->eventTypes
            );

            foreach( $eventTypes as $evType) {
                $val = $evType->pkEventTypeID;
                $idx = array_search($val, $arrIDs);
                $checked = (array_search($val, $arrIDs) === FALSE) ? '' : ' checked ';
                echo "<input type='checkbox' name='eventType[$val]' value='$val' $checked> {$evType->eventType}&nbsp;&nbsp;\n";
            }
            echo '<br/><br/>';
        ?>
    </div>
    <div class='col-sm-3'>
            <?= $form->field($model, 'otherType')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<?= $form->field($model, 'address1')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'address2')->textInput(['maxlength' => true]) ?>

<div class='row'>
    <div class='col-sm-3'>
        <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
    </div>

    <div class='col-sm-1'>
        <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>
    </div>

    <div class='col-sm-2'>
        <?= $form->field($model, 'zipcode')->textInput(['maxlength' => true]) ?>
    </div>

    <div class='col-sm-4'>
        <?= $form->field($model, 'county')->dropDownList(CityCounty::getCountyDropDownItems()) ?>
    </div>
    <div class='col-sm-2'>
        <br><br><a href='<?= Yii::$app->urlManager->createURL(['city-county']) ?>' target='_blank'>City/County List</a>
    </div>
</div>

<?= $form->field($model, 'isAtOrgAddress')->checkbox() ?>

<?= $form->field($model, 'fkPersonID')->dropDownList($eventContact) ?>

<?= $form->field($model, 'fkEventAgeID')->dropDownList($eventAgeGroups) ?>
