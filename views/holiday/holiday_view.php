<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','leave management'),'url' => ['leave/management']],
    ['label' => Yii::t('app','holiday'),'url' => ['holiday/index']],
    ['label' => Yii::t('app','view holiday'),'url' => ['holiday/view','id'=>$model->holiday_id]],
];
$this->params['addUrl'] = 'holiday/new';
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
		    <?=\yii\widgets\DetailView::widget([
			'model' => $model,
			'attributes' => [
			    'holiday_date',
			    'holiday_desc',
			    
			],
		    ]);?>
		    
		    <br/>
		    <div style="margin-bottom:20px"></div>
		    
		    <div class="form-group pull-right">
                        <div class="col-md-offset-1 col-md-11">
                            <?=Html::a(Yii::t('app','back'),['holiday/index'], ['class' => 'btn btn-primary'])?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
		</div>    
	    </div>
	</div><!--/Portlet -->
    </div>  <!-- Enf of col lg-->                                      
</div> <!-- ENd of row -->	    
