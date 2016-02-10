<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Leaves;

$this->params['breadcrumbs'] = [
    	['label' => Yii::t('app','reports'),'url' => ['report/index']],
		['label' => Yii::t('app','balanced summary'),'url' => ['report/balanced-summary']],

];
$this->params['addUrl'] = ['my-leave/form'];
?>
<div class="row">
    <div class="col-lg-12">						
	<!-- START YOUR CONTENT HERE -->
	<div class="portlet"><!-- /Portlet -->
	    <div class="portlet-heading dark">
		<div class="portlet-title">
		    <h4 class="text-white"><i class="fa fa-newspaper-o"></i> <?=$title?></h4>
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
		    
		    <div class="row">
		    
		    <div class="col-lg-2">
		    	
		    </div>
		    
			<div class="col-lg-10" style="margin-bottom:20px">
			    <?php $form = ActiveForm::begin([
			    'id' => 'form',
			    'method' => 'GET',
			  	'options' => ['class' => 'form-inline pull-right','role' => 'form',],
			    'fieldConfig' => ['template' => "{input}",]
			    ]);?>
			    
			    <?=$form->field($model,'employee_date_from',['template' => '<div class="input-group date">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span> </div></div>'])->textInput();?>
			    <?=$form->field($model,'employee_date_to',['template' => '<div class="input-group date">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span> </div></div>'])->textInput();?>
			    <?=$form->field($model,'employee_name')->textInput(['placeholder' => Yii::t('app/message','msg search by nik or name') ]);?>
			    <?php // $form->field($model,'leave_status')->dropDownList(Leaves::getListStatus(Yii::t('app','all')))?>
			    <?php //$form->field($model,'leave_source')->dropDownList(Leaves::getListSource(Yii::t('app','all')))?>
             
			    <div class="form-group ">
				<?=Html::submitButton('<i class="fa fa-search"></i>'.Yii::t('app','search'), ['class' => 'btn btn-primary btn-md','name' => 'search'])?>
			    </div> 
			    <?php ActiveForm::end(); ?>
			</div>
		   
			<div class="col-lg-12">
			<?=Yii::$app->session->getFlash('message')?>
			<?=GridView::widget( [
			    'dataProvider' => $dataProvider,
			    'tableOptions' => ['class'=>'table table-bordered table-hover tc-table table-striped table-responsive'],
			    'layout' => '<div class="hidden-sm hidden-xs hidden-md">{summary}</div>{errors}{items}<div class="pagination pull-right">{pager}</div> <div class="clearfix"></div>',
			    'columns'=>[
					['class' => 'yii\grid\SerialColumn'],
					'EmployeeID' => [
					    'attribute' => 'EmployeeID',
					    'footer' => Yii::t('app','id'),
					    'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
					    'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
					    'footerOptions' => ['class'=>'hidden-xs hidden-sm'],
					],
					'EmployeeName' => [
					    'attribute' => 'EmployeeName',
					    'footer' => Yii::t('app','name'),
					],
					'leave_date' => [
					    'attribute' => 'EmployeeHireDate',
					    'footer' => Yii::t('app','hire date'),
					],
					'entitlement_date' => [
					    'attribute' => 'EmployeeLeaveDate',
					    'footer' => Yii::t('app','entitlement date'),
					],
					
					'EmployeeTitle' => [
					    'attribute' => 'EmployeeTitle',
					    'footer' => Yii::t('app','title'),
					],
			    	'EmployeeLeaveTotal' => [
			    		'label' => Yii::t('app','beginning balance'),	
			    		'attribute' => 'EmployeeLeaveTotal',
			    		'headerOptions' => ['class'=>'text-center'],
			    		'contentOptions'=> ['class'=>'text-right'],
			    		
			    	],
			    	'EmployeeLeaveUse' => [
			    		'label' => Yii::t('app','leave total'),
			    		'attribute' => 'EmployeeLeaveUse',	
			    		'headerOptions' => ['class'=>'text-center'],
			    		'contentOptions'=> ['class'=>'text-right'],
			    				 
			    	],
			    	'EmployeeLeaveOver' => [
			    		'label' => Yii::t('app','ending balance'),	
			    		'attribute' => 'EmployeeLeaveOver',
			    		'headerOptions' => ['class'=>'text-center'],
			    		'contentOptions'=> ['class'=>'text-right'],
			    		
			    	],
			    	
			    ],
			    //'showFooter' => true ,
			] );?>
			</div>
		    
		     </div>
		    
		</div>
	    </div>
	</div><!--/Portlet -->
    </div>  <!-- Enf of col lg-->                                      
</div>
<!-- ENd of row -->

<script>
//for tables checkbox demo
jQuery(function($) {
	$('.input-group.date').datepicker({
        autoclose : true,
     	format: "dd/mm/yyyy"
   	});
            
		$('table th input:checkbox').on('click' , function(){
		    var that = this;
		    $(this).closest('table').find('tr > td:first-child input:checkbox')
		    .each(function(){
			this.checked = that.checked;
			$(this).closest('tr').toggleClass('selected');
		    });
							
		});
		
		$('.btn-pwd').click(function (e) {
		    if (!confirm('<?=Yii::t('app/message','msg btn password')?>')) return false;
		    return true;
		});
		
		$('.btn-delete').click(function (e) {
		    if (!confirm('<?=Yii::t('app/message','msg btn delete')?>')) return false;
		    return true;
		});
    });
</script>