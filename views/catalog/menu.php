<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use  yii\widgets\LinkPager;
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
		<div class="portlet-body no-padding">
		    
		    <?php $form = ActiveForm::begin([
		    'id' => 'menu-form',
		    'method' => 'POST',
		    'options' => ['class' => 'form-inline pull-right'],
		    'fieldConfig' => [
			'template' => "{label}\n<div class=\"col-lg-12 search\">{input}{error}</div>\n",
			'labelOptions' => ['class' => 'col-md-0 control-label sr-only'],
		    ],
		    ]);?>
		    <?=$form->field($model,'menu_label')->textInput(['placeholder' => 'Menu label','class'=>'col-lg-12']);?>
		    <?=$form->field($model,'menu_url')->textInput(['placeholder' => 'Menu url','class'=>'col-lg-12']);?>
		    <div class="form-group">
			<div class="col-md-offset-1 col-md-11">
			    <?=Html::submitButton(Yii::t('app','Search'), ['class' => 'btn btn-primary','name' => 'login-button'])?>
			</div>
		    </div>
		    <?php ActiveForm::end(); ?>
		    <div class="clearfix"></div>
		    
		    <?=$table?>
		    <?=LinkPager::widget(['pagination' => $pages,'options'=>['class'=>'pagination pull-right'] ]);?>
		    
		</div>
	    </div>
	</div><!-- /Portlet -->
    </div>  <!-- Enf of col lg-->                                      
</div> <!-- ENd of row -->
<style>
    .search {padding:10px}
</style>
