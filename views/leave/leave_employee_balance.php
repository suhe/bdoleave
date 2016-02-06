<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\Auth;

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','employee'),'url' => ['leave/employee']],
    ['label' => Yii::t('app','beginning balance'),'url' => ['leave/employee_balance','id' => $query->employee_id]]
];
$this->params['addUrl'] = ['leave/employee_balance_add','id'=>$query->employee_id];
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
		    
		    <?=GridView::widget( [
			'dataProvider' => $dataProvider,
			'tableOptions' => ['class'=>'table table-bordered table-hover tc-table table-responsive'],
			'layout' => '<div class="hidden-sm hidden-xs hidden-md">{summary}</div>{errors}{items}<div class="pagination pull-right">{pager}</div> <div class="clearfix"></div>',
			'columns'=>[
			    ['class' => 'yii\grid\SerialColumn'],
			    'leave_balance_id' => [
				'attribute' => 'leave_balance_id',
				'footer' => Yii::t('app','id'),
				'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
				'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
				'footerOptions' => ['class'=>'hidden-xs hidden-sm'],
			    ],
			    'leave_balance_date' => [
				    'attribute' => 'leave_balance_date',
				    'footer' => Yii::t('app','date'),
			    ],
			    'leave_balance_description' => [
				    'attribute' => 'leave_balance_description',
				    'footer' => Yii::t('app','description'),
			    ],
			    'leave_balance_type' => [
				    'attribute' => 'leave_balance_type',
				    'footer' => Yii::t('app','type'),
			    ],
			    'leave_balance_total' => [
				    'attribute' => 'leave_balance_total',
				    
				    'footer' => Yii::t('app','total'),
			    ],
			    ['class'=>'yii\grid\ActionColumn',
				'controller'=>'leave',
				'template'=>'{employee_balance_delete}',
				'buttons' => [
				    
				    'employee_balance_delete' => function ($url,$data) {
					return Html::a('<i class="fa fa-trash icon-only"></i>',$url,['class' => 'btn btn-inverse btn-xs btn-delete',
					]);
				    },
				    
				    
				],
			    ],
		        ],
			'showFooter' => true ,
		    ] );?>
		</div>
	    </div>
	</div><!--/Portlet -->
    </div>  <!-- Enf of col lg-->                                      
</div> <!-- ENd of row -->


<script>
	//for tables checkbox demo
    jQuery(function($) {
	$('.btn-delete').click(function (e) {
	    if (!confirm('<?=Yii::t('app/message','msg btn delete')?>')) return false;
	    return true;
	});
    });
</script>