<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Leaves;

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
			  	'options' => ['class' => 'form-inline pull-right','role' => 'form',],
			    'fieldConfig' => ['template' => "{input}",]
			    ]);?>
			    <?=$form->field($model,'employee_name')->textInput(['placeholder' => Yii::t('app/message','msg search by nik or name') ]);?>
			    <?=$form->field($model,'leave_date_from',['template' => '<div class="input-group date">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span> </div></div>'])->textInput();?>
			    <?=$form->field($model,'leave_date_to',['template' => '<div class="input-group date">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span> </div></div>'])->textInput();?>
			   	<?=$form->field($model,'leave_status')->dropDownList(Leaves::getListStatus(Yii::t('app','all')))?>
			    <?=$form->field($model,'leave_source')->dropDownList(Leaves::getListSource(Yii::t('app','all')))?>
             
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
				 ],
			    'employeeid' => [
			    	'attribute' => 'employeeid',
			    	'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
			    	'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
			    ],
			    'employee_name' => [
			    	'attribute' => 'employee_name',
			    	'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
			    	'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
			    ],
			    'leave_date_from' => [
			    	'attribute' => 'leave_date_from',
			    	'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
			    	'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
			   	],
			    'leave_date_to' => [
			    	'attribute' => 'leave_date_to',
			    	'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
			    	'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
			    ],
					
				'leave_total' => [
				    'attribute' => 'leave_total',
					'contentOptions'=> ['class'=>'text-right'],
				    
				],
				'leave_status' => [
					'label' => Yii::t('app','status'),	
				    'attribute' => 'leave_status_string',
				],
			    
			    'leave_source' => [
			    	'label' => Yii::t('app','status'),
			    	'attribute' => 'leave_source_string',
			    ],
			   
				
				['class'=>'yii\grid\ActionColumn',
				 //'controller'=>'leave',
				 'template'=>'{outstanding-view}',
				    'buttons' => [
				       'outstanding-view' => function ($url,$data) {
					   return Html::a('<i class="fa fa-eye icon-only"></i>',$url,['class' => 'btn btn-inverse btn-xs',
					   ]);
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
  
	$('#btn-add').on('click' , function() {
		var bulk_type = $("select[name='bulk_type']").val();
		var bulk_form = $("select[name='bulk_form']").val();

	   	if(bulk_type == 0 && bulk_form == 0) {
	   		$(location).attr('href','<?=Url::to(['manage-leave/form-leave'])?>');
		}
	   	else if(bulk_type == 0 && bulk_form == 1) {
	   		$(location).attr('href','<?=Url::to(['manage-leave/form-balance'])?>');
	   	} 
	   	else if(bulk_type == 1 && bulk_form == 0) {
	   		$(location).attr('href','<?=Url::to(['manage-leave/bulk-leave'])?>');
	   	}
	   	else if(bulk_type == 1 && bulk_form == 1) {
	   		$(location).attr('href','<?=Url::to(['manage-leave/bulk-balance'])?>');
	   	}  
	   			
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