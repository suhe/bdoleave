<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->params['breadcrumbs'] = [
		['label' => Yii::t('app','reports'),'url' => ['report/index']],
    	['label' => Yii::t('app','bulk beginning balance'),'url' => ['tool/beginning-balance']],

];
$this->params['addUrl'] = ['manage-leave/form'];
?>
<div class="row">
    <div class="col-lg-12">						
	<!-- START YOUR CONTENT HERE -->
	<div class="portlet"><!-- /Portlet -->
	    <div class="portlet-heading dark">
		<div class="portlet-title">
		    <h4 class="text-white"><i class="fa fa-user-md"></i> <?=$title?></h4>
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
		    <div class="row">
				<div class="col-lg-12">
				<?=Yii::$app->session->getFlash('message')?>
				<div class="form">
				    <?php 
				    $form = ActiveForm::begin([
				    	//'action' => ['tool/go-blog'],	
				    	'fieldConfig' => [
							'template' => "{input}{error}",
						 ],
				    ]); ?>
				    <table class="table table-striped table-hover table-bordered">
				        <thead>
					        <tr>
					        	<th class="col-md-1"><?=Yii::t('app','no')?></th>
					        	<th class="col-md-3"><?=Yii::t('app','name')?></th>
					        	<th class="col-md-1"><?=Yii::t('app','nik')?></th>
					        	<th class="col-md-2"><?=Yii::t('app','position')?></th>
					        	<th class="col-md-1"><?=Yii::t('app','hire date')?></th>
					        	<th class="col-md-2"><?=Yii::t('app','balance date')?></th>
					        	<th class="col-md-1"><?=Yii::t('app','saldo')?></th>
					        	<th class="col-md-1">#</th>
					        </tr>
				         <thead>
				        <tbody> 
				        <?php 
				        $no = 1;
				        foreach($users as $i=>$user){ ?>
				            <tr>
				            	<td><?=$no?></td>
				                <td><?=$user->employee_name?></td>
				                <td><?=$user->EmployeeID?></td>
				                <td><?=$user->EmployeeTitle?></td>
				                <td><?=$user->EmployeeHireDate?></td>
				                <td><?=$form->field($user,"[$i]employee_date_from",['template' => '<div class="input-group date">{input}<div class="input-group-addon"><span class="glyphicon glyphicon-th"></span> </div></div>'])->textInput(['class'=>'form-control input-sm']);?></td>
				                <td><?=$form->field($user,"[$i]EmployeeLeaveTotal")->textInput(['class'=>'form-control input-sm text-right','placeholder'=>0]); ?></td>
				                <td class="text-center"><?=$form->field($user,"[$i]checklist")->checkbox()->label(false);?></td>
				            </tr>
				        <?php 
				        $no++;
				        } ?>
				        </tbody>
				    </table>
				    
				    <div class="form-group ">
				    	<div class="pull-right" style="margin-top:10px">
							<?=Html::submitButton('<i class="fa fa-save"></i>'.Yii::t('app','submit'), ['class' => 'btn btn-primary btn-md','name' => 'submit'])?>
					 	</div>
					 	<div class="clearfix"></div>
					 </div> 
			    
				    <?php ActiveForm::end(); ?>
				</div><!-- form -->
				
				</div>
		     </div>
		    
		</div>
	    </div>
	</div><!--/Portlet -->
    </div>  <!-- Enf of col lg-->  
</div>
<!-- ENd of row -->

<script>
//for tables checkbox demo
jQuery(function($) {
	$('.input-group.date').datepicker({
        autoclose : true,
     	format: "dd/mm/yyyy"
   	});
  

});
</script>