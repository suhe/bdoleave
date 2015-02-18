<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
    $this->params['addUrl'] = 'administration/helpdesk_add';
?>
<div class="row">
    <div class="col-lg-12">						
	<!-- START YOUR CONTENT HERE -->
            <div class="portlet">
		<div class="portlet-heading">
		    <?php if($query){ ?>
		    <div class="portlet-title">
			<h4 class="danger"><?=Yii::$app->session->getFlash('msg')?></h4>
		    </div>
		    <?php } ?>
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
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-sm-8 search\">{input} {error}</div>\n",
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                        ],
                        ]);?>
			<?=$form->field($model,'employee_id')->dropDownList($employee)?>
			<?=$form->field($model,'role_id')->dropDownList($role)?>
			
			<div class="form-group pull-right">
                            <div class="col-md-offset-1 col-md-11">
                                <?=Html::submitButton($query?Yii::t('app','update'):Yii::t('app','save'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
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