<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;
$this->params ['breadcrumbs'] = [ 
		[ 
				'label' => Yii::t ( 'app', 'my leave' ),
				'url' => [ 
						'leave/index' 
				] 
		],
		[ 
				'label' => Yii::t ( 'app', 'leave form' ),
				'url' => [ 
						'leave/form' 
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
					    		'label' => Yii::t('app','hrd approval'),
					    		'value' => $employee->hrd_approval,
					    	],
					    	[
					    		'label' => Yii::t('app','partner approval'),
					    		'value' => $employee->partner_approval,
					    	],
					    	[
					    		'label' => Yii::t('app','leave saldo'),
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
                     	'action' => ['leave/form'],	
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
					
					<?=$form->field($model,'leave_type')->dropDownList(\app\models\Leaves::getDropDownSelfLeave(),['class'=>'col-lg-12',])?>
                    <?=$form->field($model,'leave_date_from')->widget(yii\jui\DatePicker::className(),['dateFormat'=>'dd/MM/yyyy','clientOptions' => ['defaultDate' => date('d/m/Y'),],])?>
                    <?=$form->field($model,'leave_date_to')->widget(yii\jui\DatePicker::className(),['dateFormat'=>'dd/MM/yyyy','clientOptions' => ['defaultDate' => date('d/m/Y'),],])?>
                    <?=$form->field($model,'leave_description')->textInput();?>
					<?=$form->field($model,'leave_address')->textInput();?>
			
					<div class="form-group pull-right" style="margin-top: 10px">
						<div class="col-md-12">
                                <?=Html::submitButton(Yii::t('app','submit'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
                                <a href="<?=\yii\helpers\Url::to(['leave/index'])?>" class="btn btn-primary"><i class="fa fa-refresh"></i> <?=Yii::t('app','back')?></a>
                            </div>
					</div>
					<div class="clearfix"></div>
                    <?php ActiveForm::end()?>
                         
		    	</div>
			</div>
		</div>
	</div>
</div>


<style>
.help-block {
	line-height: 10px;
}
</style>