<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\Auth;

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app','my leave'),'url' => ['leave/index']],
    ['label' => Yii::t('app','guide'),'url' => ['guide/index']], 
];
$this->params['addUrl'] = ['leave/form'];
?>
<div class="row">
    <div class="col-lg-12">						
	<!-- START YOUR CONTENT HERE -->
	<div class="portlet"><!-- /Portlet -->
	    <div class="portlet-heading dark">
		<div class="portlet-title">
		    <h4><?=Yii::t('app','guide')?></h4>
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
			<div class="col-lg-12" style="margin-bottom:20px">
			    <h5 class="text-uppercase"><strong>1. Setting Approval</strong></h5>
			    <p><i class="fa fa-quote-left"></i>
				Before you create a leave form online , you must setting email and approval, in this menu
				you must access sidebar menu , <strong>Setting</strong> => <strong>General Setting</strong>
			    </p>
			    <img src="assets/images/guide/general-setting.PNG" class="img-responsive" />
			    <hr/>
			    
			    <p><i class="fa fa-quote-left"></i>
				After you set the email , now you must set approval, if you have senior please fill go to <strong>Setting</strong> => <strong>Leave Approval</strong>
			    </p>
			    <img src="assets/images/guide/approval-leave.PNG" class="img-responsive" />
			    <hr/>
			    
			    <h5 class="text-uppercase"><strong>2. Create Online Leave</strong></h5>
			    <p><i class="fa fa-quote-left"></i>
				After you set the email & approval , now you can create a leave form please go to <strong>My Leave</strong> => <strong>Leave Formulir</strong>
			    </p>
			    <img src="assets/images/guide/leave-form.PNG" class="img-responsive" />
			    <hr/>
			    <h5 class="text-uppercase"><strong>3. Manage & See History of Your Leave</strong></h5>
			    <p><i class="fa fa-quote-left"></i>
				After you create form , you can manage & see history of your leave please go to <strong>My Leave</strong> => <strong>My Leave</strong>
				
			    </p>
			    <img src="assets/images/guide/myleave.PNG" class="img-responsive" />
			    <hr/>
			    <p><i class="fa fa-quote-left"></i>
				you can see detail & history of your leave you can click eye icon in the right table
			    </p>
			    <img src="assets/images/guide/myleave-view.PNG" class="img-responsive" />
			    <p><i class="fa fa-quote-left"></i>
				if you click eye icon , you can see detail & history of your leave
			    </p>
			    <img src="assets/images/guide/history.PNG" class="img-responsive" />
			    <hr/>
			    
			    <h5 class="text-uppercase"><strong>4. To be Approval Leave</strong></h5>
			    <p><i class="fa fa-quote-left"></i>
				if you Senior/Manager/Partner you can approval other staff , if your name set from other staff from him approval setting <strong>My Leave</strong> => <strong>To Be Approval</strong>
				
			    </p>
			    <img src="assets/images/guide/approval-list.PNG" class="img-responsive" />
			    <hr/>
			    <p><i class="fa fa-quote-left"></i>
				if your click eye icon you can see history & approval/reject button , if you approve you can click approval button and if you
				reject you must click reject button.
			    </p>
			    <img src="assets/images/guide/approval-approve.PNG" class="img-responsive" />
			    <hr/>
			    
			    <p><i class="fa fa-quote-left"></i>
				you can wait your approval to partner & hrd approved or rejected your leave form.
			    </p>
			</div>
		    
		     </div>
		    
		</div>
	    </div>
	</div><!--/Portlet -->
    </div>  <!-- Enf of col lg-->                                      
</div> <!-- ENd of row -->