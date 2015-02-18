<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','setting')],
    ['label' => Yii::t('app','general setting')],
];
$this->params['addUrl'] = 'ticket/new';
?>

<div class="hr hr-12 hr-double"></div>
<div class="notice bg-success marker-on-left" style="display:none"></div>
<?php
$form = ActiveForm::begin([
    'id' => 'edit-password-form',
    'options' => ['class' => 'form-horizontal'],
    'enableAjaxValidation'=> false,
    'enableClientValidation' => true,
    'fieldConfig' => [
	'template' => "{label}\n<div class=\"col-sm-10\">{input} {error}</div>\n",
	'labelOptions' => ['class' => 'col-sm-2 control-label'],
    ],
]);?>

<?=$form->field($model,'EmployeeFirstName')->textInput(['value'=>$query->EmployeeFirstName]);?>
<?=$form->field($model,'EmployeeMiddleName')->textInput(['value'=>$query->EmployeeMiddleName]);?>
<?=$form->field($model,'EmployeeLastName')->textInput(['value'=>$query->EmployeeLastName]);?>
<?=$form->field($model,'EmployeeEmail')->textInput(['value'=>$query->EmployeeEmail]);?>
<div class="form-actions">
    <div class="form-group pull-right">
	<div class="col-md-offset-1 col-md-11">
	    <?=Html::submitButton(Yii::t('app','update'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
	</div>
    </div>
    <div class="clearfix"></div>
</div>
<?php $form = ActiveForm::end()?>