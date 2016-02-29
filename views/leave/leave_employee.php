<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\Auth;

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','leave management'),'url' => ['leave/management']],
    ['label' => Yii::t('app','employee'),'url' => ['leave/employee']],
];
$this->params['addUrl'] = 'ticket/new';
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
			    'action' => ['leave/employee'],
			    'options' => ['class' => 'form-inline pull-right','role' => 'form',],
			    'fieldConfig' => ['template' => "{input}",]
			    ]);?>
			    <?=$form->field($model,'EmployeeName')->textInput(['placeholder'=>Yii::t('app','name or nik')])?>
			    <?=$form->field($model,'leave_status')->dropDownList(\app\models\Employee::getDropDownLeaveStatus(TRUE))?>
             
			    <div class="form-group ">
				<?=Html::submitButton('<i class="fa fa-search icon-only"></i> '.Yii::t('app','search'), ['class' => 'btn btn-primary btn-md','name' => 'search'])?>
				<?=Html::a('<i class="fa fa-file-pdf-o icon-only"></i> '.Yii::t('app','export'),['leave/employee_export'], ['class' => 'btn btn-primary','target'=>'_blank'])?>
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
				'leave_status' => [
				    'attribute' => 'leave_status',
				    'footer' => Yii::t('app','status'),
				],
				'EmployeeTitle' => [
				    'attribute' => 'EmployeeTitle',
				    'footer' => Yii::t('app','title'),
				],
				
				
				'EmployeeLeaveUse' => [
				    'attribute' => 'EmployeeLeaveUse',
				    'contentOptions'=> ['class'=>'text-right'],
				    'footer' => Yii::t('app','leave'),
				],
				
				'EmployeeLeaveOver' => [
				    'attribute' => 'EmployeeLeaveOver',
				    'contentOptions'=> ['class'=>'text-right'],
				    'footer' => Yii::t('app','over'),
				],
				
				['class'=>'yii\grid\ActionColumn',
				 'controller'=>'leave',
				 'template'=>'{employee_bio}{employee_export}',
				 'buttons' => [
				    'employee_bio' => function ($url,$data) {
					return Html::a('<i class="fa fa-eye icon-only"></i>',$url,['class' => 'btn btn-inverse btn-xs',
					]);
				    },
				    'employee_export' => function ($url,$data) {
					return Html::a('<i class="fa fa-file-pdf-o icon-only"></i>',$url,['class' => 'btn btn-inverse btn-xs',
					]);
				    }
				    
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