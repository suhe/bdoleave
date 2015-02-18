<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','setting')],
    ['label' => Yii::t('app','change password')],
];
$this->params['addUrl'] = 'ticket/new';
?>

<div class="hr hr-12 hr-double"></div>
<div class="notice bg-success marker-on-left" style="display:none"></div>
<?php
$form = ActiveForm::begin([
    'id' => 'edit-password-form',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
	'template' => "{label}\n<div class=\"col-sm-8\">{input}{error}</div>\n",
	'labelOptions' => ['class' => 'col-sm-2 control-label'],
    ],
]);?>

<?=$form->field($model,'passtext')->passwordInput();?>
<?=$form->field($model,'passtext_confirm')->passwordInput();?>
<?=$form->field($model,'passtext_new')->passwordInput();?>
<?=$form->field($model,'passtext_new_confirm')->passwordInput();?>
<div class="form-actions">
    <div class="form-group pull-right">
	<div class="col-md-offset-1 col-md-11">
	    <?=Html::submitButton(Yii::t('app','change password'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
	</div>
    </div>
    <div class="clearfix"></div>
</div>
<?php $form = ActiveForm::end()?>