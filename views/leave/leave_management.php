<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\Auth;

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','leave management'),'url' => ['leave/management']],
   
];
$this->params['addUrl'] = ['leavet/add_management'];
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
		    
		    <div class="row">
			<div class="col-lg-12" style="margin-bottom:20px">
			    <?php $form = ActiveForm::begin([
			    'id' => 'form',
			    'method' => 'GET',
			    'action' => ['leave/management'],
			    'options' => ['class' => 'form-inline pull-right','role' => 'form',],
			    'fieldConfig' => ['template' => "{input}",]
			    ]);?>
			    <?=$form->field($model,'employee_name')->textInput(['placeholder'=>Yii::t('app','name')])?>
			    <?=$form->field($model,'leave_date_from')->widget(yii\jui\DatePicker::className(),['dateFormat'=>'dd/MM/yyyy','clientOptions' => ['defaultDate' => '24/01/2014',],]) ?>
			    <?=$form->field($model,'leave_date_to')->widget(yii\jui\DatePicker::className(),['dateFormat'=>'dd/MM/yyyy','clientOptions' => ['defaultDate' => '24/01/2014'],]) ?>
			    <?=$form->field($model,'leave_status')->dropDownList(\app\models\Leaves::getDropDownStatus(TRUE))?>
			    <?=$form->field($model,'leave_approved')->dropDownList(\app\models\Leaves::getDropDownApproved(TRUE))?>
             
			    <div class="form-group ">
				<?=Html::submitButton(Yii::t('app','search'), ['class' => 'btn btn-primary btn-md','name' => 'search'])?>
			    </div> 
			    <?php ActiveForm::end(); ?>
			</div>
		   
			<div class="col-lg-12">
			<?=GridView::widget( [
			    'dataProvider' => $dataProvider,
			    'tableOptions' => ['class'=>'table table-bordered table-hover tc-table table-responsive'],
			    'layout' => '<div class="hidden-sm hidden-xs hidden-md">{summary}</div>{errors}{items}<div class="pagination pull-right">{pager}</div> <div class="clearfix"></div>',
			    'columns'=>[
				['class' => 'yii\grid\SerialColumn'],
				'ticket_id' => [
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
				'employee_id' => [
				    'attribute' => 'employeeid',
				    'footer' => Yii::t('app','nik'),
				],
				'employeefirstname' => [
				    'attribute' => 'employeefirstname',
				    'footer' => Yii::t('app','name'),
				],
				'ticket_range' => [
				    'attribute' => 'leave_range',
				    'footer' => Yii::t('app','range'),
				    'headerOptions' => ['class'=>'hidden-xs hidden-sm'],
				    'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
				    'footerOptions' => ['class'=>'hidden-xs hidden-sm'],
				],
				'leave_total' => [
				    'attribute' => 'leave_total',
				    'contentOptions'=> ['class'=>'text-center'],
				    'footer' => Yii::t('app','total'),
				],
				'leave_description' => [
				    'attribute' => 'leave_status',
				    'value' => function($data) { return \app\models\Leaves::getStringStatus($data->leave_status); },
				    'footer' => Yii::t('app','status'),
				],
				'leave_approved' => [
				    'attribute' => 'leave_approved',
				    'value' => function($data) { return \app\models\Leaves::getStringApproved($data->leave_approved); },
				    'footer' => Yii::t('app','approved'),
				],
				
				['class'=>'yii\grid\ActionColumn',
				 'controller'=>'leave',
				 'template'=>'{detail}{exportform}',
				 'buttons' => [
				    'detail' => function ($url,$data) {
					return Html::a('<i class="fa fa-eye icon-only"></i>',$url,['class' => 'btn btn-inverse btn-xs',
					]);
				    },
				    'exportform' => function ($url,$data) {
					return Html::a('<i class="fa fa-file-pdf-o icon-only"></i>',$url,['class' => 'btn btn-inverse btn-xs',
					]);
				    },
				    
				    
				],
				],
			    ],
			    'showFooter' => true ,
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