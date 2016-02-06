<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\Auth;

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','employee'),'url' => ['leave/employee']],
    ['label' => Yii::t('app','personal'),'url' => ['leave/employee_bio','id' => $query->employee_id]]
];
$this->params['addUrl'] = ['leave/add_management'];
?>
<div class="row">
    <div class="col-lg-12">						
	<!-- START YOUR CONTENT HERE -->
	<div class="portlet"><!-- /Portlet -->
	    <div class="portlet-heading dark">
		<div class="portlet-title">
		    <h4 class="text-danger"></h4>
		</div>
		<div class="portlet-widgets">
		    <a data-toggle="collapse" data-parent="#accordion" href="#basic"><i class="fa fa-chevron-down"></i></a>
                        <span class="divider"></span>
			<a href="#" class="box-close"><i class="fa fa-times"></i></a>
		</div>
		<div class="clearfix"></div>
	    </div>
	    
            <div id="basic" class="panel-collapse collapse in">
		<div class="portlet-body">
		    <ul class="nav nav-tabs nav-justified background-dark">
			<li class="<?=$title==Yii::t('app','personal')?'active':''?>"><a href="<?=\yii\helpers\Url::to(['leave/employee_bio','id'=>$query->employee_id])?>"><i class="fa fa-home"></i> <?=Yii::t('app','personal')?></a></li>
			<li class="<?=$title==Yii::t('app','balance')?'active':''?>"><a href="<?=\yii\helpers\Url::to(['leave/employee_balance','id'=>$query->employee_id])?>"><i class="fa fa-user"></i> <?=Yii::t('app','beginning balance')?> </a></li> 
			<li class="<?=$title==Yii::t('app','activity')?'active':''?>"><a href="<?=\yii\helpers\Url::to(['leave/employee_activity','id'=>$query->employee_id])?>"><i class="fa fa-area-chart"></i> <?=Yii::t('app','leave activity')?></a></li>
			<li class="<?=$title==Yii::t('app','end balance')?'active':''?>"><a href="<?=\yii\helpers\Url::to(['leave/employee_endbalance','id'=>$query->employee_id])?>"><i class="fa fa-list"></i> <?=Yii::t('app','leave balance')?></a></li>
		    </ul>
		    
		    <?=Yii::$app->session->getFlash('msg')?>
		    
		    <?php
			$form = ActiveForm::begin([
			    'id' => 'personal-form',
			    'options' => ['class' => 'form-horizontal'],
			    'fieldConfig' => [
					'template' => "{label}\n<div class=\"col-sm-10\">{input} {error}</div>\n",
					'labelOptions' => ['class' => 'col-sm-2 control-label'],
			    ],
			]);?>
			<?=$form->field($model,'EmployeeID')->textInput(['value'=>$query->EmployeeID,'disabled'=>true]);?>
			<?=$form->field($model,'EmployeeFirstName')->textInput(['value'=>$query->EmployeeFirstName]);?>
			<?=$form->field($model,'EmployeeMiddleName')->textInput(['value'=>$query->EmployeeMiddleName]);?>
			<?=$form->field($model,'EmployeeLastName')->textInput(['value'=>$query->EmployeeLastName]);?>
			<?=$form->field($model,'EmployeeEmail')->textInput(['value'=>$query->EmployeeEmail]);?>
			<?=$form->field($model,'EmployeeHireDate')->widget(yii\jui\DatePicker::className(),['dateFormat'=>'dd/MM/yyyy','clientOptions' => ['defaultDate' => '24/01/2014'],]) ?>
			<?php //$form->field($model,'EmployeeLeaveSenior')->dropDownList(\app\models\Employee::getEmployeeGroupDropdownList(Yii::$app->user->identity->department_id,'041'),['class'=>'col-lg-12',])?>
			<?=$form->field($model,'EmployeeLeaveManager')->dropDownList(\app\models\Employee::getEmployeeGroupDropdownList(Yii::$app->user->identity->department_id,'03'),['class'=>'col-lg-12',])?>
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
		    
		</div>
	    </div>
	</div><!--/Portlet -->
    </div>  <!-- Enf of col lg-->                                      
</div> <!-- ENd of row -->