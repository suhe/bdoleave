<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\Auth;

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','setting'),'url' => ['#']],
    ['label' => Yii::t('app','helpdesk'),'url' => ['administration/helpdesk']]
];

$this->params['addUrl'] = 'administration/helpdesk_add';

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
		    
		    <?php $form = ActiveForm::begin([
		    'id' => 'form',
		    'method' => 'get',
		    'action' => ['administration/helpdesk'],
		    'options' => ['class' => 'form-inline pull-right'],
		    'fieldConfig' => ['template' => "{input}",]
		    ]);?>
		    
		    <?=$form->field($model,'EmployeeFirstName')->textInput()?>
		    <?=$form->field($model,'role_id')->dropDownList($role)?>
		    <div class="form-group ">
			<?=Html::submitButton(Yii::t('app/backend','search'), ['class' => 'btn btn-primary btn-md','name' => 'search'])?>
		    </div>
		    <?php ActiveForm::end(); ?>
		    
		    <div style="margin-bottom:20px"></div>
		    
		    <?=GridView::widget( [
                        'dataProvider' => $dataProvider,
			'tableOptions' => ['class'=>'table table-bordered table-hover tc-table'],
			'layout' => '<div class="hidden-sm hidden-xs hidden-md">{summary}</div>{errors}{items}<div class="pagination pull-right">{pager}</div> <div class="clearfix"></div>',
			
			'columns'=>[
			    ['class' => 'yii\grid\SerialColumn'],
			    'EmployeeID' => [
				'attribute' => 'EmployeeID',
				'footer' => Yii::t('app','nik'),
			    ],	
			    'EmployeeFirstName' => [
				'attribute' => 'EmployeeFirstName',
				'footer' => Yii::t('app','name'),
			    ],	
			    'role' => [
				'attribute' => 'role',
				'footer' => Yii::t('app','role'),
			    ],	
			    
			    ['class'=>'yii\grid\ActionColumn',
			     'controller'=>'administration',
			     'template'=>'{helpdesk_delete}',
			     'buttons' => [
				'helpdesk_edit' => function ($url, $model) {
				    return Html::a('<i class="fa fa-pencil icon-only"></i>',$url,['class' => 'btn btn-inverse btn-xs',
				    ]);
				},
				
				'helpdesk_delete' => function ($url, $model) {
				    return Html::a('<i class="fa fa-times icon-only"></i>',$url,['class' => 'btn btn-danger btn-xs btn-delete',
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