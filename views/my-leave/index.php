<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Leaves;

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','my leave'),'url' => ['my-leave/index']],

];
$this->params['addUrl'] = ['my-leave/form'];
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
		    
		    <div class="col-lg-2">
		    	<a href="<?=\yii\helpers\Url::to(['my-leave/form'])?>" class="btn btn-primary"><i class="fa fa-pencil"></i> <?=Yii::t('app','leave form')?></a>
		    </div>
		    
			<div class="col-lg-10" style="margin-bottom:20px">
			    <?php $form = ActiveForm::begin([
			    'id' => 'form',
			    'method' => 'GET',
			  	'options' => ['class' => 'form-inline pull-right','role' => 'form',],
			    'fieldConfig' => ['template' => "{input}",]
			    ]);?>
			    
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
				    'attribute' => 'leave_status_string',
				],
			    'leave_source' => [
			    	'attribute' => 'leave_source_string',
			    ],
				
				['class'=>'yii\grid\ActionColumn',
				 //'controller'=>'leave',
				 'template'=>'{detail-view}',
				    'buttons' => [
				       'detail-view' => function ($url,$data) {
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