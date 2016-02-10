<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\Auth;

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','employee'),'url' => ['leave/employee']],
    ['label' => Yii::t('app','balance'),'url' => ['leave/employee_balance','id' => $query->employee_id]],
    ['label' => Yii::t('app','balance add'),'url' => ['leave/employee_balance_add','id' => $query->employee_id]]
];
$this->params['addUrl'] = ['leave/employee_balance_add'];
?>
<div class="row">
    <div class="col-lg-12">						
	<!-- START YOUR CONTENT HERE -->
	<div class="portlet"><!-- /Portlet -->
	    <div class="portlet-heading dark">
		<div class="portlet-title">
		    <h4 class="text-danger"><?=$query->EmployeeID?></h4>
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
		    <ul class="nav nav-tabs nav-justified background-dark">
			<li><a href="<?=\yii\helpers\Url::to(['leave/employee_bio','id'=>$query->employee_id])?>"><i class="fa fa-home"></i> <?=Yii::t('app','personal')?></a></li>
			<li class="active"><a href="<?=\yii\helpers\Url::to(['leave/employee_balance','id'=>$query->employee_id])?>"><i class="fa fa-user"></i> <?=Yii::t('app','beginning balance')?> </a></li> 
			<li><a href="<?=\yii\helpers\Url::to(['leave/employee_activity','id'=>$query->employee_id])?>"><i class="<?=\yii\helpers\Url::to(['leave/employee_bio','id'=>$query->employee_id])?>"></i> <?=Yii::t('app','leave activity')?></a></li>
		    </ul>
		    
		    <?=Yii::$app->session->getFlash('msg')?>
		    
		    <?php
			$form = ActiveForm::begin([
			    'id' => 'personal-form',
			    'options' => ['class' => 'form-horizontal'],
			    'fieldConfig' => [
				'template' => "{label}\n<div class=\"col-sm-10\">{input} {error}</div>\n",
				'labelOptions' => ['class' => 'col-sm-2 control-label'],
			    ],
			]);?>
			<?=$form->field($model,'leave_balance_date')->widget(yii\jui\DatePicker::className(),['dateFormat'=>'dd/MM/yyyy','clientOptions' => ['defaultDate' => '24/01/2014'],]) ?>
			<?=$form->field($model,'leave_balance_description')->textInput(['value' => 'Saldo Awal per Tanggal ']);?>
			<?=$form->field($model,'leave_balance_stype')->dropDownList(['0'=>'Saldo Awal',1=>'Tambahan'])?>
			<?=$form->field($model,'leave_balance_total')->textInput(['value'=>'0','class'=>'col-lg-2 text-right']);?>
			
			<div class="form-actions">
			    <div class="form-group pull-right">
				<div class="col-md-offset-1 col-md-11">
				    <?=Html::submitButton(Yii::t('app','update'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
				</div>
			    </div>
			    <div class="clearfix"></div>
			</div>
		    <?php $form = ActiveForm::end()?>
		    
		</div>
	    </div>
	</div><!--/Portlet -->
    </div>  <!-- Enf of col lg-->                                      
</div> <!-- ENd of row -->