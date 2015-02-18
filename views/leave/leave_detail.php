<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','my leave'),'url' => ['leave/index']],
    ['label' => Yii::t('app','details'),'url' => ['leave/detail','id'=>$id]]
];
$this->params['addUrl'] = ['leave/form'];
use yii\widgets\DetailView;
?>
<div class="row">
    <div class="col-lg-12">						
	<!-- START YOUR CONTENT HERE -->
	<div class="portlet"><!-- /Portlet -->
	    <div class="portlet-heading dark">
		<div class="portlet-title">
		    <h4 class="text-danger"><?=Yii::$app->session->getFlash('msg')?></h4>
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
		    <?=DetailView::widget([
			'model' => $model,
			'attributes' => [
			    'leave_id',
			    [
				'attribute'=>'leave_type',
				'value' => \app\models\Leaves::getStringType($model->leave_type)
			    ],
			    'leave_date',
			    'employee_name',
			    [
				'attribute'=>'leave_date_from',
				'value' => $model->leave_date_from .' to '. $model->leave_date_to
			    ],
			    [
				'attribute'=>'leave_total',
				'value' => $model->leave_total .' '.Yii::t('app','days')
			    ],
			    'leave_range',
			    'leave_description',
			    'leave_address',
			],
		    ]);?>
		    
		    <br/>
		    <div style="margin-bottom:20px"></div>
		    
			<?=\yii\grid\GridView::widget( [
			    'dataProvider' => $dataViewProvider,
			    'tableOptions' => ['class'=>'table table-bordered table-hover tc-table table-responsive'],
			    'layout' => '{errors}{items}<div class="pagination pull-right">{pager}</div> <div class="clearfix"></div>',
			    'caption' => Yii::t('app','leave status'),
			    'captionOptions' => ['class'=>'align-left'],
			    'columns'=>[
				['class' => 'yii\grid\SerialColumn'],
				'leave_log_date' => [
				    'attribute' => 'leave_log_date',
				],	
				'leave_log_title' => [
				    'attribute' => 'leave_log_title',
				],	
				'leave_log_desc' => [
				    'attribute' => 'leave_log_desc',
				],
			    ],
			    
			] );?>
		    
		    <div class="form-group pull-right">
			<?php if(($model->leave_status==2) || ($model->leave_status==12) ){ ?>
			<div class="col-md-offset-2 col-md-3">
                            <?=Html::a(Yii::t('app','complete'),['leave/request_complete','id'=>$model->leave_id], ['class' => 'btn btn-primary'])?>
                        </div>
			<?php } ?>
			<div class="col-md-offset-2 col-md-2">
                            <?=Html::a(Yii::t('app','back'),['leave/index'], ['class' => 'btn btn-primary'])?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
		</div>    
	    </div>
	</div><!--/Portlet -->
    </div>  <!-- Enf of col lg-->                                      
</div> <!-- ENd of row -->	    
