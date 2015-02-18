<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','login'), 'url' => ['site/login']];
$this->params['addUrl'] = 'ticket/add';

?>
<div class="row">
    <div class="col-lg-3">
       <div class="portlet portlet-basic">
	    <div class="portlet-heading">
	       <div class="portlet-title"><h4><?=Yii::t('app','login')?></h4></div>
	    </div>  
	    <div class="portlet-body">
		<div class="row">
		    <?php if(Yii::$app->session->getFlash('msg')){?>
                        <div class="notice bg-danger marker-on-left"><?=Yii::$app->session->getFlash('msg')?></div>
		    <?php } ?> 
		    <div class="col-md-12">
		       <!-- BEGIN LOGIN BOX -->
		       <div id="login-box" class="visible">
			  <p class="bigger-110"><i class="fa fa-key"></i><?=Yii::t('app/message','msg please enter your information')?></p>
			  <div class="hr hr-8 hr-double dotted"></div>
			  <?php $form = ActiveForm::begin([
			  'id' => 'form',
			  'method' => 'post',
			  'fieldConfig' => [
			      'template' => "{label}{input}{error}",
			  ],
			  ]);?>
		
			  <?=$form->field($model,'EmployeeID',[
			     'inputTemplate' => '<div class="form-group"><div class="input-icon right"><span class="fa fa-user text-gray"></span>{input}</div></div>'])
			     ->textInput(['placeholder' => Yii::t('app/message','msg enter your nik')]);?>
			  <?=$form->field($model,'passtext',[
			     'inputTemplate' => '<div class="form-group"><div class="input-icon right"><span class="fa fa-key text-gray"></span>{input}</div></div>'])
			     ->passwordInput(['placeholder' => Yii::t('app/message','msg enter your password')]);?>
			    <div class="tcb">
			      <label> <?=$form->field($model,'remember_me')->checkbox()?></label>
			       <div class="form-group pull-right">
				 <?=Html::submitButton('<i class="fa fa-key icon-on-right"></i> '.Yii::t('app','login'), ['class' => 'btn btn-primary','name' => 'login'])?>
			       </div> 
			    </div>  
			   <div class="space-4"></div>
			 <?php $form = ActiveForm::end();?>  											
			</div>
			<!-- END LOGIN BOX -->									
		    </div>
            </div>
           </div>											
	</div>
	<!-- END YOUR CONTENT HERE -->												
    </div>
    
    <div class="col-lg-9">
       <!--<div class="portlet portlet-basic">
	   <div class="portlet-heading">
	       <div class="portlet-title"></h4></div>
	   </div>
	   <div class="portlet-body">
		<div class="row">
		    <!--<img src="./assets/images/cuti.jpg" class="img-responsive" />
		</div>
           </div>											
	</div>
	<!-- END YOUR CONTENT HERE -->												
    </div>

</div>			