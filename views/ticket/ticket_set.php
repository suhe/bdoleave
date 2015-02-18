<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','list new ticket'),'url' => ['ticket/management']],
    ['label' => Yii::t('app','set helpdesk'),'url' => ['#']]
];
$this->params['addUrl'] = 'ticket/add';
use yii\widgets\DetailView;
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
		    <?=DetailView::widget([
			'model' => $model,
			'attributes' => [
			    'ticket_id',
			    'employee_name',
			    'ticket_date',
			    'ticket_subject',
			    'ticket_type',
			    'ticket_note',
			],
		    ]);?>
		    
		    <br/>
		    <?php
		    if($queryatt){
			foreach($queryatt as $row){
			    print '<p>'.\yii\helpers\Html::a(Yii::t('app','attachment view'),$row['attachment_file'],['target'=>'_blank']).'</p>';
			}
		    }
		    ?>
	    
		    <div style="margin-bottom:20px"></div>
		    
		    
		    <?php $form = ActiveForm::begin([
                        'id' => 'form',
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-sm-10 search\">{input} {error}</div>\n",
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                        ],
                        ]);
		    ?>
		    
		    <?=$form->field($model,'ticket_helpdesk')->dropDownList($dropDownHelpdesk,['class'=>'col-lg-12',])?>
		    <?=$form->field($model,'ticket_handling')->textInput();?>
		
		    <div class="form-group pull-right">
                        <div class="col-md-offset-1 col-md-11">
                            <?=Html::submitButton(Yii::t('app','set helpdesk'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
		
		    <?php ActiveForm::end() ?>
		    
		</div>    
	    </div>
	</div><!--/Portlet -->
    </div>  <!-- Enf of col lg-->                                      
</div> <!-- ENd of row -->	    
