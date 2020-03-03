<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use app\models\Staffing;

/* @var $this yii\web\View */
/* @var $model app\models\Event */
/* @var $instructors yii\data\ActiveDataProvider */

$this->title = 'Assign Staff';
$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['requests']];
$this->params['breadcrumbs'][] = ['label' => "{$model->eventTypeString} @ {$model->organization->name} ({$model->pkEventID})", 'url' => ['view', 'id' => $model->pkEventID]];
$this->params['breadcrumbs'][] = 'Assign';

$this->registerJs( "$('button').click( function() { if( this.type == 'submit' ) return validateForm(); });" );
?>
    
<div class="event-assign">

    <h1><?= Html::encode($this->title) ?></h1>

<script>
    function validateForm() {
        //alert('Validating');
        
        var dateFmts = [
            /^((0?[1-9]\/)|(1[0-2]\/))(([0-2]?[1-9])|(3[0-1]))(\/(20)?\d{2})?$/,
            /^20\d{2}-((0?[1-9]-)|(1[0-2]-))(([0-2]?[1-9])|(3[0-1]))$/
        ];
        
        var timeFmts = [
            /^((0?\d:)|(1[0-2]:))(\d|([0-5]\d))\s?(AM|PM)$/,
            /^(([0-1]?\d:)|(2[0-3]:))(\d|([0-5]\d))$/
        ];
        
        var fld, re, passCount, errStr; // Some reusable variables
        errStr = '';
        
        // event-eventdate
        
        fld = document.getElementById('event-eventdate');
        if(fld.value.length > 0) // optional field, so zero length is valid
        {
            passCount = 0;
            for( re of dateFmts )
            {
                //re = new RegExp(reStr);
                //alert(fld.value);
                //alert(re.toString());
                if(re.test(fld.value))
                {
                    //alert('test passed');
                    ++passCount;
                }
//                else
//                    alert( 'Field value "' + fld.value + '" failed to pass re ' + re.toString());
            }
            if(passCount == 0)
            {   
                //alert('Invalid date');
                errStr += 'Invalid date.\n';            
            }
        }

        // event-{start,end}time

        var timeFieldNames = [ 'event-starttime', 'event-endtime'];
        for( var fldName of timeFieldNames )
        {
            //alert(fldName);
            fld = document.getElementById(fldName);
            if(fld.value.length > 0)
            {
                passCount = 0;
                for( re of timeFmts )
                {
                    //re = new RegExp(reStr);
                    if(re.test(fld.value))
                        ++passCount;
                }
                //alert(passCount);
                if(passCount == 0)
                {
                    //alert('Invalid ' + fldName);
                    errStr += 'Invalid time.\n';                
                }
            }
        }
        
        if(errStr != '')
        {
            alert(errStr);
            return false;
        }
        else
            return true;
    }
</script>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'requestDateTime',
                'value' => function($data) {
                    return Yii::$app->globals->formatSQLDate($data->requestDateTime, 'n/d/Y g:i A');
                }
            ],
            [
                'label' => 'Event Type(s)',
                'value' => function($data) {
                    return $data->eventTypeString;
                }
            ],    
            [
                'label' => 'Organization',
                'value' => Html::a( $model->organization->name, "/index.php?r=org/view&id={$model->organization->pkOrgID}" )
                        . ", {$model->address1}, {$model->city} " . $model->organization->zipCodeKeepZeros(),
                'format' => 'raw',
            ],
            'county',
            [
                'label' => 'Contact Person',
                'value' => function($data) { 
                    $p = $data->contact; // convenience
                    $id = $p->pkPersonID;
                    $name = $p->firstName . ' ' . $p->lastName;
                    $email = $p->email;
                    $phone = $p->formattedPhone('-');
                    $title = $p->getContactTitle($data->fkOrgID);
                    $linkedName = Html::a( $name, "/index.php?r=person/view&id={$id}" );
                    $linkedEmail = Html::mailto( $email, $email );
                    return "{$linkedName}, {$title} ({$linkedEmail}, {$phone})";
                },
                'format' => 'raw',
            ],
            'need',
            [
                'label' => 'Audience',
                'value' => "{$model->participation} people in {$model->presentations} presentation(s)",
            ],
            [
                'label' => 'Age Groups/Grades',
                'value' => function($data) {
                    $result = "";
                    if( $data->eventAge )
                        if( $data->ageDescription )
                            $result = $data->eventAge->eventAge . ' (' . $data->ageDescription . ')';
                        else
                            $result = $data->eventAge->eventAge;
                    else
                        if( $data->ageDescription )
                            $result = $data->ageDescription;
                        else
                            $result = '(Left blank)';
                    return $result;
                },
            ],
            [
                'label' => 'Proposed Dates/Times', 'value' => $model->datetimes,
            ],
            'comments',
        ],
    ]);
    ?>
    
    <h2>Confirmed Details</h2>
    
    <div class='event-form'>
    
    <?php
    
    $form = ActiveForm::begin();
    
    echo $this->render('_assign', [
        'model' => $model,
        'form' => $form,
        'instructors' => $instructors,
    ]); ?>
        
    <div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <a href='<?= Yii::$app->urlManager->createURL(['event/requests']) ?>'>Cancel</a>

    </div>

</div>
