<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\Auth;

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','employee'),'url' => ['leave/employee']],
    ['label' => Yii::t('app','leave activity'),'url' => ['leave/employee_activity','id' => $query->employee_id]]
];
$this->params['addUrl'] = ['leave/add_management'];
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
		    
		    <ul class="nav nav-tabs nav-justified background-dark">
			<li class="<?=$title==Yii::t('app','personal')?'active':''?>"><a href="<?=\yii\helpers\Url::to(['leave/employee_bio','id'=>$query->employee_id])?>"><i class="fa fa-home"></i> <?=Yii::t('app','personal')?></a></li>
			<li class="<?=$title==Yii::t('app','balance')?'active':''?>"><a href="<?=\yii\helpers\Url::to(['leave/employee_balance','id'=>$query->employee_id])?>"><i class="fa fa-user"></i> <?=Yii::t('app','beginning balance')?> </a></li> 
			<li class="<?=$title==Yii::t('app','activity')?'active':''?>"><a href="<?=\yii\helpers\Url::to(['leave/employee_activity','id'=>$query->employee_id])?>"><i class="fa fa-area-chart"></i> <?=Yii::t('app','leave activity')?></a></li>
			<li class="<?=$title==Yii::t('app','end balance')?'active':''?>"><a href="<?=\yii\helpers\Url::to(['leave/employee_endbalance','id'=>$query->employee_id])?>"><i class="fa fa-list"></i> <?=Yii::t('app','leave balance')?></a></li>
		    </ul>
		    
		    <?php $form = ActiveForm::begin([
			'id' => 'form',
			'method' => 'GET',
			    'action' => ['leave/employee_activity','id'=>$query->employee_id],
			    'options' => ['class' => 'form-inline pull-right','role' => 'form',],
			    'fieldConfig' => ['template' => "{input}",]
			    ]);?>
			    <?=$form->field($model,'leave_date_from')->widget(yii\jui\DatePicker::className(),['dateFormat'=>'dd/MM/yyyy']) ?>
			    <?=$form->field($model,'leave_date_to')->widget(yii\jui\DatePicker::className(),['dateFormat'=>'dd/MM/yyyy']) ?>
			    <?=$form->field($model,'leave_status')->dropDownList(\app\models\Leaves::getDropDownStatus(TRUE))?>
             
			<div class="form-group ">
			    <?=Html::submitButton(Yii::t('app','search'), ['class' => 'btn btn-primary btn-md','name' => 'search'])?>
			</div> 
		    <?php ActiveForm::end(); ?>
		    
		    <div class="clearfix" style="margin-bottom:20px"></div>	
		    
		    
		    <?=GridView::widget( [
			'dataProvider' => $dataProvider,
			'tableOptions' => ['class'=>'table table-bordered table-hover tc-table table-responsive'],
			'layout' => '<div class="hidden-sm hidden-xs hidden-md">{summary}</div>{errors}{items}<div class="pagination pull-right">{pager}</div> <div class="clearfix"></div>',
			'columns'=>[
			    ['class' => 'yii\grid\SerialColumn'],
			    'leave_id' => [
				'attribute' => 'leave_id',
				'footer' => Yii::t('app','id'),
				'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
				'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
				'footerOptions' => ['class'=>'hidden-xs hidden-sm'],
			    ],
			    'leave_date' => [
				'attribute' => 'leave_date',
				'footer' => Yii::t('app','date of filing'),
			    ],	    
			    'leave_range' => [
				'attribute' => 'leave_range',
				'footer' => Yii::t('app','range'),
				'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
				'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
				'footerOptions' => ['class'=>'hidden-xs hidden-sm'],
			    ],
			    'leave_description' => [
				'attribute' => 'leave_description',
				'footer' => Yii::t('app','necessary'),
				'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
				'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
				'footerOptions' => ['class'=>'hidden-xs hidden-sm'],
			    ],
			    'leave_total' => [
				'attribute' => 'leave_total',
				'footer' => Yii::t('app','total'),
			    ],
			    'leave_status' => [
				'attribute' => 'leave_status',
				'value' => function($data) { return \app\models\Leaves::getStringStatus($data->leave_status); },
				'footer' => Yii::t('app','status'),
			    ],
			    ['class'=>'yii\grid\ActionColumn',
				'controller'=>'leave',
				'template'=>'{detail}',
				'buttons' => [
				    'detail' => function ($url,$data) {
					return Html::a('<i class="fa fa-eye icon-only"></i>',$url,['class' => 'btn btn-inverse btn-xs',
					]);
				    }, 
				],
			    ],
			],
			    'showFooter' => true ,
		    ]);?>
		     
		    
		</div>
	    </div>
	</div><!--/Portlet -->
    </div>  <!-- Enf of col lg-->                                      
</div> <!-- ENd of row -->