<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','my ticket'),'url' => ['ticket/index']],
    ['label' => Yii::t('app','new ticket'),'url' => ['ticket/order']]
];
$this->params['addUrl'] = 'ticket/order';
?>
<div class="row">
    <div class="col-lg-12">						
	<!-- START YOUR CONTENT HERE -->
            <div class="portlet">
		<div class="portlet-heading">
		    
		    <div class="portlet-title">
			<h4 class="danger"><?=Yii::t('app','ticket form')?></h4>
		    </div>
		    
		    <div class="portlet-widgets">
			<a data-toggle="collapse" data-parent="#accordion" href="#ft-3"><i class="fa fa-chevron-down"></i></a>
		    </div>
		    <div class="clearfix"></div>
		</div>
		
                <div id="ft-3" class="panel-collapse collapse in">
		    <div class="portlet-body">
                        <?php $form = ActiveForm::begin([
                        'id' => 'menu-add-form',
                        'method' => 'post',
                        'options' => ['class' => 'form-horizontal','enctype' => 'multipart/form-data'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-lg-10 col-sm-10\">{input} {error}</div>\n",
                            'labelOptions' => ['class' => 'col-lg-2 col-sm-2 control-label'],
                        ],
                        ]);?>
			
			<?=$form->field($model,'employee_id')->dropDownList($dropDownEmployee,['class'=>'col-lg-12',])?>
			<?=$form->field($model,'ticket_subject')->textInput();?>
			<?=$form->field($model,'ticket_type')->dropDownList(Yii::$app->params['ticket_type'],['class'=>'col-lg-12',])?>
			<?=$form->field($model,'ticket_note')->textarea(['rows' => 8])?>
			<?=$form->field($model,'ticket_helpdesk')->dropDownList($dropDownHelpdesk,['class'=>'col-lg-12',])?>
			<?=$form->field($model,'ticket_handling')->textInput();?>
			
			<div class="col-lg-2">
			    <label for="ticket-ticket_note" class="col-md-12 control-label"><?=Yii::t('app','attachment')?></label>
			</div>
			
			<div class="col-lg-10">
			    <?=app\kato\DropZone::widget([  
				'options' => [
				    'paramName' => 'file',
				    'addRemoveLinks' => 'dictRemoveFile',
				    'url' => \yii\helpers\Url::to(['ticket/upload']),
				],
				'clientEvents' => [
					'complete' => "function(file){console.log(file)}",
					'removedfile' => "function(file){
							   
							    alert(file.name + ' telah dihapus');
							 }",
				]
			    ]);?>
			</div>
			
			<div class="form-group pull-right" style="margin-top:20px ">
                            <div class="col-md-offset-1 col-md-11">
                                <?=Html::submitButton(Yii::t('app','new ticket'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <?php ActiveForm::end() ?>
                         
		    </div>
		</div>
            </div>
    </div>
</div>
<style>
    .help-block{line-height:10px;}
</style>