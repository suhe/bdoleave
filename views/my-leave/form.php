<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;
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
						'my-leave/form' 
				] 
		] 
];
$this->params ['addUrl'] = [ 
		'leave/form' 
];
?>
<div class="row">
	<div class="col-lg-12">
	<div class="alert alert-danger"><i class="fa fa-warning"></i>  <strong><?=Yii::t('app','info')?>! </strong><?=Yii::t('app/message','msg approval and approval email must be valid')?></div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<!-- START YOUR CONTENT HERE -->
		<div class="portlet">
			<div class="portlet-heading">

				<div class="portlet-title">
					<h4 class="danger"><i class="fa fa-user-md"></i>  <?=Yii::t('app','my profile')?></h4>
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
					    		'format' => ['date', 'php:d M Y'],
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
					
					<?=$form->field($model,'leave_type')->dropDownList(\app\models\Leaves::getDropDownSelfLeave(),['class'=>'col-lg-6'])?>
                    <?=$form->field($model,'leave_description')->textArea(['rows'=>3]);?>
                    <?=$form->field($model,'leave_date_from',['template' => '{label}<div class="col-sm-4 search"><div class="input-group date">{input}{error}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span> </div></div></div>'])->textInput();?>
			    	<?=$form->field($model,'leave_date_to',['template' => '{label}<div class="col-sm-4 search"><div class="input-group date">{input}{error}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span> </div></div></div>'])->textInput();?>
                    
					<?=$form->field($model,'leave_address')->textArea(['rows'=>3]);?>
					<?=$form->field($model,'leave_saldo_total',['template'=> '{input}'])->hiddenInput(['value'=> ($employee->EmployeeLeaveTotal - $employee->EmployeeLeaveUse) ])?>
			
					<div class="form-group pull-right" style="margin-top: 5px">
						<div class="col-md-12">
                                <?=Html::submitButton('<i class="fa fa-save"></i>'.Yii::t('app','submit'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
                                <a href="<?=\yii\helpers\Url::to(['my-leave/index'])?>" class="btn btn-primary"><i class="fa fa-refresh"></i> <?=Yii::t('app','back')?></a>
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