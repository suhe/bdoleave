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
<?=Yii::$app->session->getFlash('msg')?>
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
		    
		    <div class="row">
			<div class="col-lg-12" style="margin-bottom:20px">
			    <?=$tanggal;?>
			    <?php $form = ActiveForm::begin([
			    'id' => 'form',
			    'method' => 'GET',
			    'action' => ['leave/management'],
			    'options' => ['class' => 'form-inline pull-right','role' => 'form',],
			    'fieldConfig' => ['template' => "{input}",]
			    ]);?>
			    <?=$form->field($model,'employee_name')->textInput(['placeholder'=>Yii::t('app','nik or name')])?>
			    <?=$form->field($model,'leave_date_type')->dropDownList(\app\models\Leaves::getDropDownDateType(TRUE))?>
			    <?=$form->field($model,'leave_date_from')->widget(yii\jui\DatePicker::className(),['dateFormat'=>'dd/MM/yyyy','clientOptions' => ['defaultDate' => date('d/m/Y')],]) ?>
			    <?=$form->field($model,'leave_date_to')->widget(yii\jui\DatePicker::className(),  ['dateFormat'=>'dd/MM/yyyy','clientOptions' => ['defaultDate' => date('d/m/Y')],]) ?>
			    <?=$form->field($model,'leave_status')->dropDownList(\app\models\Leaves::getDropDownStatus(TRUE))?>
			    <?php //$form->field($model,'leave_approved')->dropDownList(\app\models\Leaves::getDropDownApproved(TRUE))?>
             
			    <div class="form-group ">
				<?=Html::submitButton(Yii::t('app','find'), ['class' => 'btn btn-primary btn-md','name' => 'search'])?>
				<?=Html::a(Yii::t('app','export'),['leave/exportleave'],['class' => 'btn btn-primary btn-md'])?>
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
				    'headerOptions' => ['class'=>'hidden-xs hidden-sm','style'=>'width:5%'],
				    'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
				    'footerOptions' => ['class'=>'hidden-xs hidden-sm'],
				],
				'leave_date' => [
				    'attribute' => 'leave_date',
				    'headerOptions' => ['style'=>'width:12%'],
				    'footer' => Yii::t('app','date of filing'),
				],	
				'employee_id' => [
				    'attribute' => 'employeeid',
				    'headerOptions' => ['style'=>'width:8%'],
				    'footer' => Yii::t('app','nik'),
				],
				'employeefirstname' => [
				    'attribute' => 'employeefirstname',
				    'headerOptions' => ['style'=>'width:15%'],
				    'footer' => Yii::t('app','name'),
				],
				'leave_range' => [
				    'attribute' => 'leave_range',
				    'footer' => Yii::t('app','range'),
				    'headerOptions' => ['class'=>'hidden-xs hidden-sm','style'=>'width:25%'],
				    'contentOptions'=> ['class'=>'hidden-xs hidden-sm'],
				    'footerOptions' => ['class'=>'hidden-xs hidden-sm'],
				],
				'leave_total' => [
				    'attribute' => 'leave_total',
				    'headerOptions' => ['style'=>'width:5%'],
				    'contentOptions'=> ['class'=>'text-center'],
				    'footer' => Yii::t('app','total'),
				],
				'leave_status' => [
				    'attribute' => 'leave_status',
				    'headerOptions' => ['style'=>'width:5%'],
				    'value' => function($data) { return \app\models\Leaves::getStringStatus($data->leave_status); },
				    'footer' => Yii::t('app','status'),
				],
				'leave_approved' => [
				    'attribute' => 'leave_approved',
				    'headerOptions' => ['style'=>'width:5%'],
				    'value' => function($data) { return \app\models\Leaves::getStringApproved($data->leave_approved); },
				    'footer' => Yii::t('app','approved'),
				],
				
				['class'=>'yii\grid\ActionColumn',
				 'controller'=>'leave',
				 'headerOptions' => ['style'=>'width:10%'],
				 'template'=>'{detail}{exportform}{delete}',
				 'buttons' => [
				    'detail' => function ($url,$data) {
					return Html::a('<i class="fa fa-eye icon-only"></i>',$url,['class' => 'btn btn-inverse btn-xs',
					]);
				    },
				    'exportform' => function ($url,$data) {
					return Html::a('<i class="fa fa-file-pdf-o icon-only"></i>',$url,['class' => 'btn btn-inverse btn-xs',
					]);
				    },
				    'delete' => function ($url,$data) {
					return Html::a('<i class="fa fa-trash icon-only"></i>',$url,['class' => 'btn btn-delete btn-inverse btn-xs',
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