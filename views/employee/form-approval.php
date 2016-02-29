<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\models\Employee;

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','employee'),'url' => ['employee/index']],
    ['label' => Yii::t('app','personal'),'url' => ['employee/form','id' => $query->employee_id]],
	['label' => $query->EmployeeFirstName,'url' => ['employee/form','id' => $query->id]]
];
$this->params['addUrl'] = ['leave/add_management'];
?>
<div class="row">
	<div class="col-lg-12">
		<!-- START YOUR CONTENT HERE -->
		<div class="portlet">
			<!-- /Portlet -->
			<div class="portlet-heading dark">
				<div class="portlet-title">
					<h4 class="text-danger"></h4>
				</div>
				<div class="portlet-widgets">
					<a data-toggle="collapse" data-parent="#accordion" href="#basic"><i
						class="fa fa-chevron-down"></i></a> <span class="divider"></span>
					<a href="#" class="box-close"><i class="fa fa-times"></i></a>
				</div>
				<div class="clearfix"></div>
			</div>

			<div id="basic" class="panel-collapse collapse in">
				<div class="portlet-body">
					<ul class="nav nav-tabs nav-justified background-dark">
						<li class=""><a
							href="<?=\yii\helpers\Url::to(['employee/form','id'=>$query->employee_id])?>"><i
								class="fa fa-home"></i> <?=Yii::t('app','personal')?></a></li>
						<li class="active"><a
							href="<?=\yii\helpers\Url::to(['employee/form-approval','id'=>$query->employee_id])?>"><i
								class="fa fa-user"></i> <?=Yii::t('app','approval setting')?> </a></li>
					</ul>
		    
				    <?=Yii::$app->session->getFlash('message')?>
				    
				    <?php
					$form = ActiveForm::begin([
					    'id' => 'personal-form',
					    'options' => ['class' => 'form-horizontal'],
					    'fieldConfig' => [
							'template' => "{label}\n<div class=\"col-sm-10\">{input} {error}</div>\n",
							'labelOptions' => ['class' => 'col-sm-2 control-label'],
					    ],
					]);?>
					<?=$form->field($model,'EmployeeID')->textInput(['disabled'=>true]);?>
					<?=$form->field($model,'EmployeeLeaveManager')->dropDownList(Employee::getEmployeeList(['label'=>Yii::t('app','no approval'),'position'=>'Manager']),['class'=>'col-lg-6',])?>
					<?=$form->field($model,'EmployeeLeaveHRD')->dropDownList(Employee::getEmployeeList(['label'=>Yii::t('app','no approval'),'position'=>'Manager HRD']),['class'=>'col-lg-6',])?>
					<?=$form->field($model,'EmployeeLeavePartner')->dropDownList(Employee::getEmployeeList(['label'=>Yii::t('app','no approval'),'position'=>'Partner']),['class'=>'col-lg-6',])?>
					<div class="form-actions">
						<div class="form-group pull-right">
							<div class="col-md-offset-1 col-md-11">
						    <?=Html::submitButton('<i class="fa fa-save"></i>'. Yii::t('app','update profile'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
						</div>
						</div>
						<div class="clearfix"></div>
					</div>
				    <?php $form = ActiveForm::end()?>
				</div>
			</div>
		</div>
		<!--/Portlet -->
	</div>
	<!-- Enf of col lg-->
</div>
<!-- ENd of row -->

<script>
//for tables checkbox demo
jQuery(function($) {
	$('.input-group.date').datepicker({
        autoclose : true,
     	format: "dd/mm/yyyy"
   	});
          
});
</script>