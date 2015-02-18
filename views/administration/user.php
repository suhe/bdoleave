<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\Auth;
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
				<div  class="col-lg-3">
				    <div class="row">
					<div class="col-lg-7">
					    <?=$form->field($model,'user_bulk',['options' => ['class' => 'form-group col-sm-12']])->dropDownList($bulk)?>
					</div>
					<div class="col-lg-5">
					    <div class="form-group ">
						<?=Html::submitButton(Yii::t('app/backend','bulk process'), ['class' => 'btn btn-primary','name' => 'bulk'])?>
					    </div>
					</div>
				    </div>
				</div>
				
				<div class="col-lg-9">
				    <div class="row">
					<div class="col-sm-offset-2 col-lg-3">
					    <?=$form->field($model,'user_name',['options' => ['class' => 'form-group col-sm-12']])->textInput(['placeholder' => Yii::t('app/backend','username')])?>
					</div>
					<div class="col-lg-3 ">
					    <?=$form->field($model,'user_email',['options' => ['class' => 'form-group col-sm-12']])->textInput(['placeholder' => Yii::t('app/backend','email')])?>
					</div>
					<div class="col-lg-3 ">
					    <?=$form->field($model,'user_created',['options' => ['class' => 'form-group col-sm-12']])->textInput(['placeholder' => Yii::t('app/backend','created by')])?>
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
			    ['class'=>'yii\grid\CheckboxColumn','name' => 'id'],
			    'user_name' => [
				'attribute' => 'user_name',
				'filter' => false,
				'footer' => Yii::t('app/backend','username'),
			    ],	
			    'user_email' => [
				'attribute' => 'user_email',
				'filter' => false,
				'footer' => Yii::t('app/backend','email'),
			    ],	
			    'user_created_by' => [
				'attribute' => 'user_created',
				'filter' => false,
				'footer' => Yii::t('app/backend','created by'),
			    ],			 
			    'user_created_date' => [
				'attribute' => 'user_created_date',
				'filter' => false,
				'footer' => Yii::t('app/backend','created date'),
			    ],	
			    ['class'=>'yii\grid\ActionColumn',
			     'controller'=>'administration',
			     'template'=>'{user_edit} {user_pwd} {user_delete}',
			     'buttons' => [
				'user_edit' => function ($url, $model) {
				    if(Auth::access('administration/menu_edit'))
				    return Html::a('<i class="fa fa-pencil icon-only"></i>',$url,['class' => 'btn btn-inverse btn-xs',
				    ]);
				},
				'user_pwd' => function ($url, $model) {
				    if(Auth::access('administration/menu_pwd'))
				    return Html::a('<i class="fa fa-key icon-only"></i>',$url,['class' => 'btn btn-default btn-xs btn-pwd',
				    ]);
				},
				'user_delete' => function ($url, $model) {
				    if(Auth::access('administration/menu_delete'))
				    return Html::a('<i class="fa fa-times icon-only"></i>',$url,['class' => 'btn btn-danger btn-xs btn-delete',
				    ]);
				},
			    ],
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