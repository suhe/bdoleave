<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\Leaves;
$this->params ['breadcrumbs'] = [ 
		[ 
				'label' => Yii::t ( 'app', 'my leave' ),
				'url' => [ 
						'my-leave/index' 
				] 
		],
		[ 
				'label' => Yii::t ( 'app', 'leave form' ),
				'url' => [ 
						'my-leave/detail-view/'.$id 
				] 
		] 
];
$this->params ['addUrl'] = [ 
		'leave/form' 
];
?>

<div class="row">
	<div class="col-lg-6">
		<!-- START YOUR CONTENT HERE -->
		<div class="portlet">
			<div class="portlet-heading">

				<div class="portlet-title">
					<h4 class="danger"><i class="fa fa-user-md"></i>  <?=Yii::t('app','employee profile')?></h4>
				</div>

				<div class="portlet-widgets">
					<a data-toggle="collapse" data-parent="#accordion" href="#ft-3"><i
						class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clearfix"></div>
			</div>

			<div id="ft-3" class="panel-collapse collapse in">
				<div class="portlet-body">
					<?=DetailView::widget([
					    'model' => $employee,
					    'attributes' => [
					    	[
					    		'label' => Yii::t('app','nik'),
					    		'value' => $employee->EmployeeID,
					    	],
					        [
					            'label' => Yii::t('app','name'),
					            'value' => $employee->EmployeeFirstName.' '.$employee->EmployeeLastName,
					        ],
					    	[
					    		'label' => Yii::t('app','position'),
					    		'value' => $employee->EmployeeTitle,
					    	],
					    	[
					    		'label' => Yii::t('app','hire date'),
					    		//'format' => ['date', 'php:d M Y'],
					    		'value' => $employee->EmployeeHireDate,
					    	],
					    	[
					    		'label' => Yii::t('app','manager approval'),
					    		'value' => $employee->manager_approval,
					    	],
					    	[
					    		'label' => Yii::t('app','manager email'),
					    		'value' => $employee->manager_email,
					    	],
					    	[
					    		'label' => Yii::t('app','hrd approval'),
					    		'value' => $employee->hrd_approval,
					    	],
					    	[
					    		'label' => Yii::t('app','hrd email'),
					    		'value' => $employee->hrd_email,
					    	],
					 
					    	[
					    		'label' => Yii::t('app','partner approval'),
					    		'value' => $employee->partner_approval,
					    	],
					    	[
					    		'label' => Yii::t('app','partner email'),
					    		'value' => $employee->partner_email,
					    	],
					    	[
					    		'label' => Yii::t('app','saldo balanced'),
					    		'value' => $employee->EmployeeLeaveTotal - $employee->EmployeeLeaveUse,
					    	],
					        
					    ],
						
							
					])?>  
					
					  
		    	</div>
			</div>
		</div>
	</div>
	
	<div class="col-lg-6">
		<!-- START YOUR CONTENT HERE -->
		<div class="portlet">
			<div class="portlet-heading">

				<div class="portlet-title">
					<h4 class="danger"><i class="fa fa-file-o"></i> <?=$title?></h4>
				</div>

				<div class="portlet-widgets">
					<a data-toggle="collapse" data-parent="#accordion" href="#ft-3"><i
						class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clearfix"></div>
			</div>

			<div id="ft-3" class="panel-collapse collapse in">
				<div class="portlet-body">
					<?=DetailView::widget([
					    'model' => $leave,
					    'attributes' => [
					    	[
					    		'label' => Yii::t('app','type'),
					    		'value' => $leave->leave_type_string,
					    	],
					    	[
					    		'label' => Yii::t('app','status'),
					    		'value' => $leave->leave_status_string,
					    	],
					    	[
					    		'label' => Yii::t('app','source'),
					    		'value' => $leave->leave_source_string,
					    	],
					    	[
					    		'label' => Yii::t('app','date of filing'),
					    		'value' => $leave->leave_date,
					    	],	
					    	[
					    		'label' => Yii::t('app','leave date'),
					    		'value' => $leave->leave_date_from .' -  '.$leave->leave_date_to,
					    	],
					    	[
					    		'label' => Yii::t('app','total days'),
					    		'value' => $leave->leave_total,
					    	],
					    	[
					    		'label' => Yii::t('app','total before filing'),
					    		'value' => $leave->leave_saldo_total,
					    	],
					    	
					    	[
					    		'label' => Yii::t('app','remaining if approved'),
					    		'value' => $leave->leave_saldo_balanced,
					    	],
					    	[
					    		'label' => Yii::t('app','leave date'),
					    		'value' => $leave->leave_range,
					    	],
					    	[
					    		'label' => Yii::t('app','necessary'),
					    		'value' => $leave->leave_description,
					    	],
					    	[
					    		'label' => Yii::t('app','address'),
					    		'value' => $leave->leave_address,
					    	],
					    		 
					        
					    ],
						
							
					])?>    
					                         
		    	</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-lg-6">
		<!-- START YOUR CONTENT HERE -->
		<div class="portlet">
			<div class="portlet-heading">

				<div class="portlet-title">
					<h4 class="danger"><i class="fa fa-newspaper-o"></i> <?=Yii::t('app','log leave history')?></h4>
				</div>

				<div class="portlet-widgets">
					<a data-toggle="collapse" data-parent="#accordion" href="#ft-3"><i
						class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clearfix"></div>
			</div>

			<div id="ft-3" class="panel-collapse collapse in">
				<div class="portlet-body">
					<?=GridView::widget( [
					    'dataProvider' => $logDataProvider,
					    'tableOptions' => ['class'=>'table table-bordered table-hover table-striped  tc-table table-responsive'],
					    'layout' => '<div class="hidden-sm hidden-xs hidden-md">{summary}</div>{errors}{items}<div class="pagination pull-right">{pager}</div> <div class="clearfix"></div>',
					    'columns'=>[
						['class' => 'yii\grid\SerialColumn'],
						'leave_log_date' => [
						    'attribute' => 'leave_log_date',
							'label' => Yii::t('app','date'),	
							'format' => ['date', 'php:d M Y H:i:s'],
						 ],
					    'leave_log_status' => [
					    		'label' => Yii::t('app','status'),
					    	'attribute' => 'leave_log_status_string',
					    		
					    ],
					    'leave_log_title' => [
					    	'label' => Yii::t('app','title'),
					    	'attribute' => 'leave_log_title',
					    	
					    ],
					    'leave_log_approval_name' => [
					    	'label' => Yii::t('app','to be approval'),
					    	'attribute' => 'leave_log_approval_name',
					    		
					    ],
					    
						
					    ],
					   //'showFooter' => true ,
					] );?> 
					            
		    	</div>
			</div>
		</div>
	</div>
	
	<div class="col-lg-6">
		<!-- START YOUR CONTENT HERE -->
		<div class="portlet">
			<div class="portlet-heading">

				<div class="portlet-title">
					<h4 class="danger"><i class="fa fa-file-o"></i> <?=Yii::t('app','approval form')?></h4>
				</div>

				<div class="portlet-widgets">
					<a data-toggle="collapse" data-parent="#accordion" href="#ft-3"><i
						class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clearfix"></div>
			</div>

			<div id="ft-3" class="panel-collapse collapse in">
				<div class="portlet-body">
					<?=Yii::$app->session->getFlash('msg')?>
                     <?php 
                     $form = ActiveForm::begin ( [ 
						'id' => 'menu-add-form',
						'method' => 'post',
						'options' => [ 
							'class' => 'form-horizontal' 
						],
						'fieldConfig' => [ 
							'template' => "{label}\n<div class=\"col-sm-10 search\">{input} {error}</div>\n",
							'labelOptions' => [ 
								'class' => 'col-sm-2 control-label' 
							] 
						] 
					] );?>
					
					<?=$form->field($model,'leave_approval')->dropDownList(Leaves::getListApproval(),['class'=>'col-lg-3'])?>
                    <?=$form->field($model,'leave_note')->textArea(['rows'=>2]);?>
					
					<div class="form-group pull-right">
						<div class="col-md-12">
                                <?=Html::submitButton('<i class="fa fa-save"></i>'.Yii::t('app','submit'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
                                <a href="<?=\yii\helpers\Url::to(['app-leave/index'])?>" class="btn btn-primary"><i class="fa fa-refresh"></i> <?=Yii::t('app','back')?></a>
                            </div>
					</div>
					<div class="clearfix"></div>
                    <?php ActiveForm::end()?>
                         
		    	</div>
			</div>
		</div>
	</div>
	
	
</div>



<script>
//for tables checkbox demo
jQuery(function($) {
	$('.input-group.date').datepicker({
        autoclose : true,
     	format: "dd/mm/yyyy"
   	});
          
});
</script>


<style>
.help-block {
	line-height: 10px;
}
</style>