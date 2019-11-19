<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\CityCounty;
use app\models\Contact;

/* @var $this yii\web\View */
/* @var $model app\models\Organization */
/* @var $form yii\widgets\ActiveForm */
?>

<script type='text/javascript'>
    
    function setContactChanged(ctlName) 
    {
        //var s = 'contactChanged' + ctlName.substr(ctlName.indexOf('['));
        var ctl = document.getElementById('contactChanged' + ctlName.substr(ctlName.indexOf('[')));
        if(ctl !== null) {
            ctl.value = 1;
            //alert(ctl.name);
        }
    }
</script>
    
<div class="organization-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

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
    
    <h2>Contacts</h2>
    
    <?php
    
        /*
         * Each contact has a set of fields numbered sequentially from 0:
         *   contactOrigPersonID[n] hidden      The original pkPersonID of the dropdown (0 for the new contact field set)
         *   contactChanged[n]      hidden      Originally 0, set to 1 by Javascript if any of the other fields in the set changed
         *   contactNewPersonID[n]  dropdown    The pkPersonID of the new person selected
         *   contactTitle[n]        text        The title of the person for this organization
         * 
         * The dropdown and text fields call javascript setContactChanged on their change event, with their name.
         * That function sets the value of the "contactChanged" hidden field with the same "[n]".
         */
        
        $contactCount = 0;
        foreach($model->people as $contact)
        {
            ?>
            <input type='hidden' 
                   id='contactOrigPersonID[<?= $contactCount ?>]'
                   name='contactOrigPersonID[<?= $contactCount ?>]' 
                   value='<?= $contact->pkPersonID ?>'>
            <input type='hidden' 
                   id='contactChanged[<?= $contactCount ?>]' 
                   name='contactChanged[<?= $contactCount ?>]' 
                   value='0'>
            
            <div class='row'>
            
                <div class='col-sm-4'>
                    <div class='form-group'>
                        <select id='contactNewPersonID[<?= $contactCount ?>]' name='contactNewPersonID[<?= $contactCount ?>]' 
                                onchange='setContactChanged(this.name);' class='form-control'>
                            <option value='0'>&lt; Delete Contact &gt;</option>
                            <?php
                            foreach( array_keys($people) as $pkPersonID)
                            {
                                $selected = ($pkPersonID == $contact->pkPersonID ? ' selected' : '');
                                echo "<option value='{$pkPersonID}'{$selected}>{$people[$pkPersonID]}</option>\n";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class='col-sm-8'>
                    <input type='text' size='40' 
                           id='contactTitle[<?= $contactCount ?>]' name='contactTitle[<?= $contactCount ?>]'
                           onchange='setContactChanged(this.name);'
                           value='<?= 
                            (Contact::find()
                                ->where([
                                    'fkOrgID' => Yii::$app->globals->var['fkOrgID'],
                                    'fkPersonID' => $contact->pkPersonID,
                                    ])
                                ->one()
                            )->title ?>'>
                </div>
            
            </div>
            <?php
            ++$contactCount;
        }

        if($contactCount) { ?>
            <p style='font-weight: bold'>Additional Contact:</p>
        <?php } ?>

        <input type='hidden' 
               id='contactOrigPersonID[<?= $contactCount ?>]' 
               name='contactOrigPersonID[<?= $contactCount ?>]' 
               value='0'>
        <input type='hidden' 
               id='contactChanged[<?= $contactCount ?>]' 
               name='contactChanged[<?= $contactCount ?>]' 
               value='0'>

        <div class='row'>

            <div class='col-sm-4'>
                <div class='form-group'>
                    <select id='contactNewPersonID[<?= $contactCount ?>]' name='contactNewPersonID[<?= $contactCount ?>]' 
                            onchange='setContactChanged(this.name);' class='form-control'>";
                        <option value='0'>&lt; Select a contact &gt;</option>";
                        <?php
                        foreach( array_keys($people) as $pkPersonID)
                        {
                            echo "<option value='{$pkPersonID}'>{$people[$pkPersonID]}</option>\n";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class='col-sm-8'>
                <input type='text' size='40'
                       id='contactTitle[<?= $contactCount ?>]' name='contactTitle[<?= $contactCount ?>]'
                       onchange='setContactChanged(this.name);'>
            </div>
            
         </div>
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
