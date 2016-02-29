<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','leave management'),'url' => ['leave/management']],
    ['label' => Yii::t('app','holiday'),'url' => ['holiday/index']],
];
$this->params['addUrl'] = ['holiday/new'];
?>
<div class="row">
    <div class="col-lg-12">						
	<!-- START YOUR CONTENT HERE -->
	<div class="portlet"><!-- /Portlet -->
	    <div class="portlet-heading dark">
		<div class="portlet-title">
		    <h4 class="text-white"><i class="fa fa-area-chart"></i> <?=$title?></h4>
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
		    <div class="col-lg-4">
		    	<div class="row">
		    		<div class="col-md-2">
		    			<a id="btn-add" href="<?=Url::to(['holiday/form']) ?>" class="btn btn-primary"><i class="fa fa-pencil"></i> <?=Yii::t('app','new')?></a>
		    		</div>
		    	</div>
		    	
		    	
		    </div>
		    
			<div class="col-lg-8" style="margin-bottom:20px">
			    <?php $form = ActiveForm::begin([
			    'id' => 'form',
			    'method' => 'GET',
			    'action' => ['holiday/index'],
			    'options' => ['class' => 'form-inline pull-right','role' => 'form',],
			    'fieldConfig' => ['template' => "{input}",]
			    ]);?>
			    
			    <?=$form->field($model,'holiday_date_from',['template' => '<div class="input-group date">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span> </div></div>'])->textInput();?>
			    <?=$form->field($model,'holiday_date_to',['template' => '<div class="input-group date">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span> </div></div>'])->textInput();?>
			   
             
			    <div class="form-group ">
				<?=Html::submitButton('<i class="fa fa-search"></i>'. Yii::t('app','search'), ['class' => 'btn btn-primary btn-md','name' => 'search'])?>
			    </div> 
			    <?php ActiveForm::end(); ?>
			</div>
		   
			<div class="col-lg-12">
			<?=Yii::$app->session->getFlash('message')?>
			<?=GridView::widget( [
			    'dataProvider' => $dataProvider,
			    'tableOptions' => ['class'=>'table table-bordered table-hover table-striped tc-table table-responsive'],
			    'layout' => '<div class="hidden-sm hidden-xs hidden-md">{summary}</div>{errors}{items}<div class="pagination pull-right">{pager}</div> <div class="clearfix"></div>',
			    'columns'=>[
					['class' => 'yii\grid\SerialColumn'],
						'holiday_date' => [
				    	'attribute' => 'holiday_date',
				    	'footer' => Yii::t('app','date'),
					],	
			    	'holiday_date' => [
			    		'attribute' => 'holiday_date',
			    		'footer' => Yii::t('app','date'),
			    	],
			    	'holiday_type' => [
			    		'attribute' => 'holiday_type',
			    		'footer' => Yii::t('app','type'),
			    	],
					'holiday_desc' => [
				    	'attribute' => 'holiday_desc',
				    	'footer' => Yii::t('app','status'),
					],
				
					['class'=>'yii\grid\ActionColumn',
					 //'controller'=>'holiday',
					 'headerOptions' => ['class'=>'col-md-1'],
					 'template'=>'{view}{form}{delete}',
					 'buttons' => [
					    'view' => function ($url,$data) {
							return Html::a('<i class="fa fa-eye icon-only"></i>',$url,['class' => 'btn btn-inverse btn-xs',
								]);
						    },
						    'form' => function ($url, $model) {
							return Html::a('<i class="fa fa-pencil icon-only"></i>',$url,['class' => 'btn btn-inverse btn-xs',
								]);
						    },
						    'delete' => function ($url, $model) {
							return Html::a('<i class="fa fa-times icon-only"></i>',$url,['class' => 'btn btn-danger btn-xs btn-delete',
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
</div> <!-- ENd of row -->

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
	
	
	$('.btn-delete').click(function (e) {
	    if (!confirm('<?=Yii::t('app/message','msg btn delete')?>')) return false;
	    return true;
	});
    });
</script>