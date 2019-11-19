<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\Models\Invoice */
/* @var $form yii\widgets\ActiveForm */

if($model->approveDate) {
    $appDtStr = Yii::$app->globals->formatSQLDate($model->approveDate, 'n/d/y g:i A');
    echo "<hr><p style='color: green;'>Approved on $appDtStr by {$model->approver->firstName} {$model->approver->lastName}</p>";
    ?>
    <label for="approve" style='color: red;'>
        <input type='checkbox' name='approve' id='approve' value='no'>
        Unapprove
    </label>
    <hr>
    <?php
}
?>

    <div class='row'>
        <div class='col-sm-1'><?= $form->field($model, 'hours')->textInput() ?></div>
        <div class='col-sm-2'><?= $form->field($model, 'presentations')->textInput() ?></div>
        <div class='col-sm-2'><?= $form->field($model, 'presentees')->textInput() ?></div>
        <div class='col-sm-2'><?= $form->field($model, 'hourlyrate')->textInput(['maxlength' => true]) ?></div>
        <div class='col-sm-2'><?= $form->field($model, 'invoiceAmount')->textInput(['maxlength' => true]) ?></div>
    </div>

<?= $form->field($model, 'approverComments')->textarea(['rows' => 3]) ?>

<?php if(!($model->approveDate)) { ?>
    <label for="approve" style='color: green;'>
        <input type='checkbox' name='approve' id='approve' value='yes'>
        Approve
    </label>
<?php }?>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>
