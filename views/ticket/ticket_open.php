<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','my ticket'),'url' => ['ticket/index']],
    ['label' => Yii::t('app','replies'),'url' => ['#']]
];
$this->params['addUrl'] = 'ticket/order';
?>
<div class="row">
    <div class="col-lg-12">
	<!-- START YOUR CONTENT HERE -->
	    <div id="timeline-1">	
		<div class="row">
		    <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
			<ul class="timeline">
			    <li class="timeline-day">
				<span class="label label-primary arrowed-in-right label-xlg">
				    <?=Yii::$app->formatter->asDatetime($query->ticket_date,"php:d/m/Y H:i:s");?>
				</span>
			    </li>
			    <li class="timeline-event">
				<div class="timeline-event-point"></div>
				<div class="timeline-event-wrap">
				    <div class="timeline-event-time">
					<i class="fa fa-comments bigger-130"></i> <?=$query->ticket_usercomment.' '.Yii::t('app','from').' '.$query->EmployeeFirstName ?> <?=!$logQuery?', '.Yii::t('app/message','msg not reply'):''?>
				    </div>
				    <div class="timeline-event-massage no-border no-padding">
					<div class="portlet">
					    <div class="portlet-heading inverse">
						<div class="portlet-title">
						    <h4><?=$query->ticket_subject;?></h4>
						</div>
						<div class="portlet-widgets">
						    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
						    <span class="divider"></span>
						    <a data-toggle="collapse" data-parent="#accordion" href="#no-border"><i class="fa fa-chevron-down"></i></a>
						</div>
						<div class="clearfix"></div>
					    </div>
					    <div id="no-border" class="panel-collapse collapse in">
						<div class="portlet-body">
						    <p><?=$query->ticket_note?></p>
						    <?php
						       if($queryatt){
							    foreach($queryatt as $row){
								print '<p>'.\yii\helpers\Html::a(Yii::t('app','attachment'),$row['attachment_file'],['target'=>'_blank']).'</p>';
							    }
						       }
						    ?>
						</div>
					    </div>
					</div>
				    </div>
				    
				</div>
			    </li>
																		
			    <?php foreach($logQuery as $row){?>
			    <li class="timeline-day">
				<span class="label label-inverse arrowed-in-right label-xlg">
				    <?=$row->log_date?>
				</span>
			    </li>
			    <li class="timeline-event">
				<div class="timeline-event-point"></div>
				<div class="timeline-event-wrap">
				    <div class="timeline-event-time">
					<i class="fa fa-comments bigger-130"></i> <?=Yii::t('app','reply from')?> : <?=$row->EmployeeFirstName?>
				    </div>
				    <div class="timeline-event-massage">
					<div class="clearfix">
					    <div class="pull-left">
						<?=$row->log_desc?>
						<p class="small">
						    <i class="fa fa-clock-o"></i> <?=\app\components\Common::timeAgo(strtotime($row->log_odate))?>
						</p>
					    </div>
					</div>
				    </div>
				</div>
			    </li>
			    <?php } ?>
			    
			    <div id="commentAjax">
				
			    </div>
			    
			    <?php if($query->ticket_status!=0){?>									
			    <li class="timeline-day form-comment">
				<span class="label label-danger arrowed-in-right label-xlg">
				    <?=date('d/m/Y H:i:s')?>
				</span>
			    </li>
			    <li class="timeline-event">
				<div class="timeline-event-point"></div>
				<div class="timeline-event-wrap">
				    <div class="timeline-event-time">
					<i class="fa fa-comments bigger-130"></i> <?=Yii::t('app','reply from')?> : <?=Yii::$app->user->identity->EmployeeFirstName?>
				    </div>
				    <div class="timeline-event-massage">
					<div class="loading" style="display:none"><img src="<?=\yii\helpers\Url::base()?>/assets/images/loading.gif"/> &nbsp;&nbsp; <?=Yii::t('app','syncronize data ...')?></div>
					<br/>
					<?php $form = ActiveForm::begin([
					'id' => 'form',
					'method' => 'post',
					'options' => ['class' => 'form-horizontal'],
					'fieldConfig' => [
					    'template' => "\n<div class=\"col-sm-12 search\">{input} {error}</div>\n",
					    'labelOptions' => ['class' => 'col-sm-2 control-label'],
					],
					]);?>
					<?=$form->field($model,'log_desc')->textarea(['rows' => 2])?>
					<?php if($query->ticket_status==1){ ?>
					<div class="col-lg-12">
					    <?=$form->field($model,'log_status')->checkbox(['value'=>1])?>
					</div>
					<?php } ?>
					<div class="form-group pull-right">
					    <div class="col-md-offset-1 col-md-11">
						<?=Html::submitButton(Yii::t('app','reply'), ['class' => 'btn btn-primary','name' => 'reply'])?>
					    </div>
					</div>
					<div class="clearfix"></div>
					<?php ActiveForm::end() ?>
					
				    </div>
				</div>
			    </li>
			    <?php } ?>
			</ul>								
		    </div>
	    </div>
	</div>
	    <!-- END YOUR CONTENT HERE -->
    </div>
</div>

<?php 
$js = <<<JS
$('#form').on('beforeSubmit', function(e) {
    var form = $(this);
    if (form.find('.has-error').length){
      return false;
    }
    $('.loading').show();
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
            success: function(data) {
                if(data.success==true){
		    var stringLi = '<li class="timeline-day">' +
		    '<span class="label label-inverse arrowed-in-right label-xlg">' +
		    data.date + 
		    '</span>' +
		    '</li>' +
		    '<li class="timeline-event">' +
		    '<div class="timeline-event-point"></div>' +
		    '<div class="timeline-event-wrap">' +
		    '<div class="timeline-event-time">' +
		    '<i class="fa fa-comments bigger-130"></i> Balasan Dari :' + data.employee + 
		    '</div>' +
		    '<div class="timeline-event-massage">' +
		    '<div class="clearfix">' +
		    '<div class="pull-left">' +
		    data.comment + 
		    '</div>' +
		    '</div>' +
		    '</div>' +
		    '</div>' +
		    '</li>'; 
		    $("#commentAjax").append(stringLi);
		    $("#ticketlog-log_desc").val("");
		    $('.loading').hide();
		}
		else {
		    alert("Error");
		    $('.loading').hide();
		}
            }
    });
    
}).on('submit', function(e){
    e.preventDefault();
});
JS;
$this->registerJs($js);