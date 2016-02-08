<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\models\Holiday;
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','leave management'),'url' => ['leave/management']],
    ['label' => Yii::t('app','holiday'),'url' => ['holiday/index']],
    ['label' => $query?Yii::t('app','edit holiday'):Yii::t('app','add holiday'),'url' => $query?['holiday/edit','id'=>$query->holiday_id]:['holiday/new']],
];
$this->params['addUrl'] = 'holiday/new';
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
                            'template' => "{label}\n<div class=\"col-sm-10 search\">{input} {error}</div>\n",
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                        ],
                        ]);?>
                        <?=$form->field($model,'holiday_date',['template' => '{label}<div class="col-sm-2 search"><div class="input-group date">{input}{error}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span> </div></div></div>'])->textInput();?>
                        <?=$form->field($model,'holiday_type')->dropDownList(Holiday::getListType(),['class'=>'col-lg-3'])?>
                        <?=$form->field($model,'holiday_desc')->textInput();?>
			
						<div class="form-group pull-right" style="margin-top:20px ">
				             <div class="col-md-offset-1 col-md-11">
				                  <?=Html::submitButton('<i class="fa fa-save"></i>'.Yii::t('app','submit'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
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

<script>
//for tables checkbox demo
jQuery(function($) {
	$('.input-group.date').datepicker({
        autoclose : true,
     	format: "dd/mm/yyyy"
   	});      
});
</script>