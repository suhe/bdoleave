<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Leaves;
use app\models\Employee;
use kartik\select2\Select2;


$this->params['breadcrumbs'] = [
		['label' => Yii::t('app','reports'),'url' => ['report/index']],
    	['label' => Yii::t('app','outstanding report'),'url' => ['report/out-standing']],

];
$this->params['addUrl'] = ['manage-leave/form'];
?>
<div class="row">
    <div class="col-lg-12">						
	<!-- START YOUR CONTENT HERE -->
	<div class="portlet"><!-- /Portlet -->
	    <div class="portlet-heading dark">
		<div class="portlet-title">
		    <h4 class="text-white"><i class="fa fa-user-md"></i> <?=$title?></h4>
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
		    
			<div class="col-lg-12" style="margin-bottom:20px">
			    <?php $form = ActiveForm::begin([
			    'id' => 'form',
			    'method' => 'GET',
			  	'options' => ['class' => 'form-inline','role' => 'form',],
			    'fieldConfig' => ['template' => "<div class='col-md-12'> {input} </div>",]
			    ]);?>
			    <?=$form->field($model, 'employee_id')->widget(Select2::classname(), [
				    'data' => Employee::getEmployeeList(),
				    'options' => ['placeholder' => Yii::t('app/message','select a employee name'),'class' => 'col-md-12'],
				    'pluginOptions' => [
				        'allowClear' => true,
				    	'class' => 'col-md-8'
				    ],]);?>
			    <div class="form-group ">
				<?=Html::submitButton('<i class="fa fa-search"></i>'.Yii::t('app','search'), ['class' => 'btn btn-primary btn-md','name' => 'search'])?>
			    </div> 
			    <?php ActiveForm::end(); ?>
			</div>
		   
			<div class="col-lg-12">
			<?=Yii::$app->session->getFlash('message')?>
			<?=GridView::widget( [
			    'dataProvider' => $dataProvider,
			    'tableOptions' => ['class'=>'table table-bordered table-hover table-striped  tc-table table-responsive'],
			    'layout' => '<div class="hidden-sm hidden-xs hidden-md">{summary}</div>{errors}{items}<div class="pagination pull-right">{pager}</div> <div class="clearfix"></div>',
			    'columns'=>[
				['class' => 'yii\grid\SerialColumn'],
			    'leave_date' => [
			    	'attribute' => 'leave_date',
			    	'label' => Yii::t('app','date'),	
			    	
			    ],
			    'leave_type_string' => [
			    	'attribute' => 'leave_type_string',
			    	'label' => Yii::t('app','type'),	
			    	'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
			    	'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
			    ],
			    'leave_description' => [
			    	'attribute' => 'leave_description',
			    	'label' => 	Yii::t('app','description'),	
			    	'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
			    	'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
			    ],
			    	
				'leave_total' => [
				    'attribute' => 'leave_total',
					'label' => Yii::t('app','total'),	
					'contentOptions'=> ['class'=>'text-right'],
				    
				],
			    'leave_saldo' => [
			    	'attribute' => 'leave_saldo',
			    	'label' => Yii::t('app','saldo'),
			    	'contentOptions'=> ['class'=>'text-right'],
			    		
			    ],
				
			    'leave_source' => [
			    	'label' => Yii::t('app','source'),
			    	'attribute' => 'leave_source_string',
			    ],
			    ['class'=>'yii\grid\ActionColumn',
			    	'template'=>'{delete}',
			    	'buttons' => [
			    		'delete' => function ($url,$data) {
			    			if($data->leave_type_string == Yii::t('app','beginning balance'))
			    				return Html::a('<i class="fa fa-trash icon-only"></i>',['report/remove-balanced-card','id'=>$data->leave_id],['class' => 'btn btn-inverse btn-xs',]);
			    		},		 
			    	],
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
         
});
</script>