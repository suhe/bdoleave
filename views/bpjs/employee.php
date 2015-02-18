<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\Auth;
use yii\helpers\Url;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','bpjs employee'), 'url' => ['bpjs/employee']];
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
		    'id' => 'menu-form',
		    'method' => 'get',
		    'action' => ['administration/user'],
		    'options' => ['class' => 'form-horizontal'],
		    'fieldConfig' => ['template' => "{input}",]
		    ]);?>
		    
		    <div class="row">
			<div class="col-lg-12" style="margin-bottom:10px">
			    <div class="row">
				<div class="col-lg-12">
				    <div class="row">
                                        <div class="col-lg-2">
					    <div class="btn-group btn-group-sm pull-left">
						<button class="btn btn-primary dropdown-toggle hidden-xs" data-toggle="dropdown">
                                                    <i class="fa fa-cog icon-only"></i> <?=Yii::t('app','export via')?> <span class="caret"></span>
						</button>
						<button class="btn btn-primary dropdown-toggle visible-xs" data-toggle="dropdown">
                                                    <i class="fa fa-cog icon-only"></i>
						</button>
						<ul class="dropdown-menu dropdown-primary" role="menu">
						    <li><a href="<?=Url::to(['bpjs/employee_excel'])?>"><i class="fa fa-file-excel-io"></i> <?=Yii::t('app','export excel')?></a></li>
						    <li class="divider"></li>
                                                </ul>
					    </div>
					</div>
                                        
					<div class="col-lg-3">
					    <?=$form->field($model,'employee_identity_id',['options' => ['class' => 'form-group col-sm-12']])->textInput(['placeholder' => Yii::t('app','nik')])?>
					</div>
					<div class="col-lg-3 ">
					    <?=$form->field($model,'employee_name',['options' => ['class' => 'form-group col-sm-12']])->textInput(['placeholder' => Yii::t('app','full name')])?>
					</div>
					<div class="col-lg-3 ">
					    <?=$form->field($model,'employee_position',['options' => ['class' => 'form-group col-sm-12']])->textInput(['placeholder' => Yii::t('app','position')])?>
					</div>
					<div class="col-lg-1 ">
					    <div class="form-group ">
						<?=Html::submitButton(Yii::t('app/backend','search'), ['class' => 'btn btn-primary btn-md','name' => 'search'])?>
					    </div> 
					</div>
				    </div>
				</div>
				
			    </div>
			</div>
		    </div>	
		    
		    
		    <?=GridView::widget( [
                        'dataProvider' => $dataProvider,
			//'filterModel'  => $model,
			'tableOptions' => ['class'=>'table table-bordered table-hover tc-table'],
			'layout' => '{summary}{errors}{items}<div class="pagination pull-right">{pager}</div> <div class="clearfix"></div>',
			
			'columns'=>[
			    ['class' => 'yii\grid\SerialColumn'],
			    
			    'employee_identity_id' => [
				'attribute' => 'employee_identity_id',
				'filter' => false,
				'footer' => Yii::t('app','nik'),
			    ],
                            'employee_identity_kk' => [
				'attribute' => 'employee_identity_kk',
				'filter' => false,
				'footer' => Yii::t('app','no kk'),
			    ],
                            'employee_pisat' => [
				'attribute' => 'employee_pisat',
				'filter' => false,
				'footer' => Yii::t('app','pisat'),
			    ],
			    'employee_name' => [
				'attribute' => 'employee_name',
				'filter' => false,
				'footer' => Yii::t('app','full name'),
			    ],	
			    'employee_position' => [
				'attribute' => 'employee_position',
				'filter' => false,
				'footer' => Yii::t('app','position'),
			    ],			 
			    'employee_created_date' => [
				'attribute' => 'employee_created_date',
				'filter' => false,
				'footer' => Yii::t('app','registration date'),
			    ],
			],
			'showFooter' => true ,
                    ] );?>
		    
		    <?php ActiveForm::end(); ?>
		    
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