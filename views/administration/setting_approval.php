<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','setting'),'url' => ['administration/general']],
    ['label' => Yii::t('app','leave approval'),'url' => ['administration/approval']],
];
$this->params['addUrl'] = 'ticket/new';
?>

<div class="hr hr-12 hr-double"></div>
<?=Yii::$app->session->getFlash('msg')?>
<div class="notice bg-success marker-on-left" style="display:none"></div>
<?php
$form = ActiveForm::begin([
    'id' => 'edit-password-form',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
	'template' => "{label}\n<div class=\"col-sm-10\">{input} {error}</div>\n",
	'labelOptions' => ['class' => 'col-sm-2 control-label'],
    ],
]);?>

<?php //if(Yii::$app->user->identity->project_title=='042'){?>
<?=$form->field($model,'EmployeeLeaveSenior')->dropDownList(\app\models\Employee::getEmployeeGroupDropdownList(Yii::$app->user->identity->department_id,'041'),['class'=>'col-lg-12',])?>
<?php //} else { ?>
<?php //$form->field($model,'EmployeeLeaveSenior')->hiddenInput()?>
<?php //} ?>

<?php //if(Yii::$app->user->identity->project_title=='03'){?>
<?php // $form->field($model,'EmployeeLeaveManager')->hiddenInput()?>
<?php //} else { ?>
<?=$form->field($model,'EmployeeLeaveManager')->dropDownList(\app\models\Employee::getEmployeeGroupDropdownList(Yii::$app->user->identity->department_id,'03'),['class'=>'col-lg-12',])?>
<?php //} ?>

<?=$form->field($model,'EmployeeLeaveHRD')->dropDownList(['0'=>Yii::t('app','not set'),10768=>'Maria Immaculat Chessy Purnamawati'],['class'=>'col-lg-12',])?>
<?=$form->field($model,'EmployeeLeavePartner')->dropDownList(\app\models\Employee::getEmployeeGroupDropdownList(Yii::$app->user->identity->department_id,'01'),['class'=>'col-lg-12',])?>

<div class="form-actions">
    <div class="form-group pull-right">
	<div class="col-md-offset-1 col-md-11">
	    <?=Html::submitButton(Yii::t('app','update'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
	</div>
    </div>
    <div class="clearfix"></div>
</div>
<?php $form = ActiveForm::end()?>