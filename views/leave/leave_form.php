<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->params ['breadcrumbs'] = [ 
		[ 
			'label' => Yii::t ( 'app', 'leave management' ),
			'url' => [ 'leave/management' ] 
		],
		[ 
			'label' => Yii::t ( 'app', 'leave form' ),
			'url' => [ 'leave/add_management' ] 
		] ];
$this->params ['addUrl'] = [ 'leave/add_management' ];
?>
<div class="row">
	<div class="col-lg-12">
		<!-- START YOUR CONTENT HERE -->
		<div class="portlet">
			<div class="portlet-heading">

				<div class="portlet-title">
					<h4 class="danger"><?=$title?></h4>
				</div>

				<div class="portlet-widgets">
					<a data-toggle="collapse" data-parent="#accordion" href="#ft-3"><i
						class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clearfix"></div>
			</div>

			<div id="ft-3" class="panel-collapse collapse in">
				<div class="portlet-body">			
                        <?php																								
$form = ActiveForm::begin ( [ 
																										'id' => 'menu-add-form',
																										'method' => 'post',
																										'options' => [ 
																												'class' => 'form-horizontal' 
																										],
																										'fieldConfig' => [ 
																												'template' => "{label}\n<div class=\"col-sm-10 search\">{input} {error}</div>\n",
																												'labelOptions' => [ 
																														'class' => 'col-sm-2 control-label' 
																												] 
																										] 
																								] );
																								?>
			<?=$form->field($model,'employee_id')->dropDownList($dropDownEmployee,['class'=>'col-lg-12',])?>
			<?=$form->field($model,'leave_type')->dropDownList(\app\models\Leaves::getDropDownType(),['class'=>'col-lg-12',])?>
                        <?=$form->field($model,'leave_date_from')->widget(yii\jui\DatePicker::className(),['dateFormat'=>'dd/MM/yyyy','clientOptions' => ['defaultDate' => date('d/m/Y'),],])?>
                        <?=$form->field($model,'leave_date_to')->widget(yii\jui\DatePicker::className(),['dateFormat'=>'dd/MM/yyyy','clientOptions' => ['defaultDate' => date('d/m/Y'),],])?>
                        <?=$form->field($model,'leave_description')->textInput();?>
			<?=$form->field($model,'leave_address')->textInput();?>
			
			<div class="form-group pull-right" style="margin-top: 20px">
						<div class="col-md-offset-1 col-md-11">
                                <?=Html::submitButton(Yii::t('app','submit'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
                            </div>
					</div>
					<div class="clearfix"></div>
                        <?php ActiveForm::end()?>
                         
		    </div>
			</div>
		</div>
	</div>
</div>
<style>
.help-block {
	line-height: 10px;
}
</style>