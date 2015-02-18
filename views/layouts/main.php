<?php
use app\assets\AppAsset;
use app\assets\AppAssetIE8;
use app\assets\AppAssetIE9;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\components\Auth;
use yii\widgets\Breadcrumbs;
AppAsset::register($this);
AppAssetIE8::register($this);
AppAssetIE9::register($this);
if(!Yii::$app->user->isGuest){
  $model = new \app\models\Leaves();
  $totalLeaveBalanceApp=count($model->getLeaveApprovalData());
  $totalLeaveApp=count($model->getLeaveApprovalData());
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="utf-8">
    <title><?=Yii::t('app','page title').' '.$this->title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
   
 <?php $this->head() ?>	
</head>
<body>
<?php $this->beginBody() ?>   
  <div id="wrapper">
    <div id="main-container">		
      <!-- BEGIN TOP NAVIGATION -->
      <nav class="navbar-top" role="navigation">
	<!-- BEGIN BRAND HEADING -->
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle pull-right" data-toggle="collapse" data-target=".top-collapse">
	    <i class="fa fa-bars"></i>
	  </button>
	  <div class="navbar-brand">
	    <a href="<?=Url::to(['/'])?>"><img src="assets/images/logo.png" alt="logo" class="img-responsive"></a>
	  </div>
	</div>
	<!-- END BRAND HEADING -->
	
	<!-- BEGIN NAV TOP NAVIGATION -->				
	<div class="nav-top">
	  <!-- BEGIN RIGHT SIDE DROPDOWN BUTTONS -->
	  <ul class="nav navbar-right">
	    <li class="dropdown">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
		  <i class="fa fa-bars"></i>
		</button>
	    </li>
	    
	      <?php if(!Yii::$app->user->isGuest){ ?>
	      <li class="dropdown">
		<a title="<?=Yii::t('app','leave approval')?>"  href="#" class="dropdown-toggle" data-toggle="dropdown">
		  <i class="fa fa-exchange"></i> <?php if($totalLeaveApp>0){ ?><span class="badge up badge-primary"><?=$totalLeaveApp?></span><?php } ?></a>
		    <ul class="dropdown-menu dropdown-scroll dropdown-messages">
			<li class="dropdown-header"><i class="fa fa-exchange"></i> <?=$totalLeaveApp?> <?=Yii::t('app','leave approval')?></li>
			<li id="messageScroll">
			  <ul class="list-unstyled">
			   <?php foreach($model->getLeaveApprovalData() as $row){ ?>
			    <li>
			      <a href="<?=Url::to(['leave/approvalform','id'=>$row->leave_id])?>">
				<div class="row">
				  <div class="col-xs-12">
				    <p>
				      <strong><?=$row->employeefirstname?></strong>: <?=$row->leave_date?>
				    </p>
				    <p class="small">
				      <i class="fa fa-clock-o"></i> <?=\app\components\Common::timeAgo(strtotime($row->leave_created_date))?>
				    </p>
				  </div>
				</div>
			      </a>
			    </li>
			    <?php } ?>
			  </ul>
			</li>
			<li class="dropdown-footer">
			  <a href="<?=Url::to(['leave/approval'])?>"><?=Yii::t('app','read all')?></a>
			</li>
		      </ul>
	      </li>
	     
	      
	      <!--Speech Icon-->
	      <li class="dropdown">
		<a href="#" class="speech-button">
		  <i class="fa fa-microphone"></i>
		</a>
	      </li>
	      <!--Speech Icon-->
	      <li class="dropdown user-box">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		  <img class="img-circle" src="assets/images/user.jpg" alt=""> <span class="user-info"><?=Yii::$app->user->identity->EmployeeFirstName?> (<?=Yii::$app->user->identity->EmployeeTitle?>)</span> <b class="caret"></b>
		</a>
		<ul class="dropdown-menu dropdown-user">
		  <li><?=Html::a('<i class="fa fa-wrench"></i> '.Yii::t('app','general setting'),['administration/general'])?></li>
		  <li><?=Html::a('<i class="fa fa-key"></i> '.Yii::t('app','change password'),['administration/password'])?></li>
		  <li><?=Html::a('<i class="fa fa-exchange"></i> '.Yii::t('app','my leave'),['leave/index'])?></li>
		  <li><?=Html::a('<i class="fa fa-power-off"></i> '.Yii::t('app','logout'),['site/logout'])?></li>
		</ul>
	      </li>
	      <?php } ?>
	      
	    </ul>
	    <!-- END RIGHT SIDE DROPDOWN BUTTONS -->
	    				
	    <!-- BEGIN TOP MENU -->
	    <div class="collapse navbar-collapse top-collapse">
	      <!-- .nav -->
	      <ul class="nav navbar-left navbar-nav">
		<li><a href="<?=Url::to(['leave/form'])?>"><i class="fa fa-plus"></i> <?=Yii::t('app','form')?></a></li>
		<li class="dropdown">
		  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
		    <i class="fa fa-link"></i> <?=Yii::t('app','quick shortcut')?> <b class="caret"></b>
		  </a>
		  <ul class="dropdown-menu">
		    <li><a href="<?=Url::to(['leave/form'])?>"><i class="fa fa-plus"></i> <?=Yii::t('app','form')?></a></li>
		    <li><a href="<?=Url::to(['leave/index'])?>"> <i class="fa fa-list"></i> <?=Yii::t('app','my leave')?></a></li>
		    <li><a href="<?=Url::to(['administration/general'])?>"> <i class="fa fa-wrench"></i> <?=Yii::t('app','general setting')?></a></li>
		     <li><a href="<?=Url::to(['administration/approval'])?>"> <i class="fa fa-user"></i> <?=Yii::t('app','leave approval')?></a></li>
		    <li><a href="<?=Url::to(['administration/password'])?>"> <i class="fa fa-key"></i> <?=Yii::t('app','change password')?></a></li>
		  </ul>
	      </li>
	      <li><a href="<?=Url::to(['guide/index'])?>" target="_blank"><i class="fa fa-umbrella"></i> <?=Yii::t('app','guide')?></a></li>	
	      <!--<li><a href="http://www.bdo.co.id" target="_blank">Visit Bdo.co.id </a></li>-->
	    </ul><!-- /.nav -->
	  </div>
	  <!-- END TOP MENU -->
	</div><!-- /.nav-top -->
      </nav><!-- /.navbar-top -->
    <!-- END TOP NAVIGATION -->

				
				<!-- BEGIN SIDE NAVIGATION -->				
				<nav class="navbar-side" role="navigation">							
					<div class="navbar-collapse sidebar-collapse collapse">
					
						<!-- BEGIN SHORTCUT BUTTONS -->
						<div class="media">							
							<ul class="sidebar-shortcuts">
								<li><a title="<?=Yii::t('app','form')?>" href="<?=Url::to(['leave/form'])?>"  class="btn"><i class="fa fa-plus icon-only"></i></a></li>
								<li><a title="<?=Yii::t('app','my leave')?>"  href="<?=Url::to(['leave/index'])?>"class="btn"><i class="fa fa-list icon-only"></i></a></li>
								<li><a title="<?=Yii::t('app','general setting')?>"  href="<?=Url::to(['administration/general'])?>"class="btn"><i class="fa fa-wrench icon-only"></i></a></li>
								<li><a title="<?=Yii::t('app','change password')?>"  href="<?=Url::to(['administration/password'])?>"class="btn"><i class="fa fa-key icon-only"></i></a></li>
							</ul>	
						</div>
						<!-- END SHORTCUT BUTTONS -->	
						
						<?php if(\app\models\Employee::isHR() && Yii::$app->user->getId() ){?>
						<?=\app\components\NavMenuWidget::widget([
						    'menu'=>[
							[
							  'label' => Yii::t('app','leave management'),
							  'url'   => 'ticket',
							  'icon'  => 'fa fa-file',
							  'sub'   => [
							    [
							      'label'=>Yii::t('app','approval leave'),
							      'url'  => 'leave/hrdapproval',
							      'icon' => 'fa fa-pencil'
							    ],
							    [
							      'label'=>Yii::t('app','leave form'),
							      'url'  => 'leave/add_management',
							      'icon' => 'fa fa-plus'
							    ],
							    [
							      'label'=>Yii::t('app','leave list'),
							      'url'  => 'leave/management',
							      'icon' => 'fa fa-list'
							    ],
							    [
							      'label'=>Yii::t('app','employee leave'),
							      'url'  => 'leave/employee',
							      'icon' => 'fa fa-user'
							    ],
							    [
							      'label'=>Yii::t('app','holiday list'),
							      'url'  => 'holiday/index',
							      'icon' => 'fa fa-area-chart'
							    ],
							    
								
							  ]
							],
			
						    ]
						])?>
						<?php }
						if(Yii::$app->user->getId()) { ?>
						<?=\app\components\NavMenuWidget::widget([
						    'menu'=>[
							[
							  'label' => Yii::t('app','my leave'),
							  'url'   => 'leave',
							  'icon'  => 'fa fa-file',
							  'sub'   => [
							    [
							      'label'=>Yii::t('app','leave form'),
							      'url'  => 'leave/form',
							      'icon' => 'fa fa-plus'
							    ],
							    [
							      'label'=>Yii::t('app','my leave'),
							      'url'  => 'leave/index',
							      'icon' => 'fa fa-list'
							    ],
							    [
							      'label'=>Yii::t('app','approval leave'),
							      'url'  => 'leave/approval',
							      'icon' => 'fa fa-pencil'
							    ],
							    [
							      'label'=>Yii::t('app','my leave balance'),
							      'url'  => 'leave/mybalance',
							      'icon' => 'fa fa-list'
							    ],
								
							  ]
							],
							[
							  'url' => '#',
							  'label'=> Yii::t('app','preference'),
							  'icon' => 'fa fa-wrench',
							  'sub'   => [
							    [
							      'label'=> Yii::t('app','general'),
							      'url'  => 'administration/general',
							      'icon' => 'fa fa-wrench'
							    ],
							    [
							      'label'=> Yii::t('app','leave approval'),
							      'url'  => 'administration/approval',
							      'icon' => 'fa fa-user'
							    ],
							    [
							      'label'=> Yii::t('app','change password'),
							      'url'  => 'administration/password',
							      'icon' => 'fa fa-key'
							    ],
								
							  ]
							],
						    ]
						])?>
						<?php } ?>
						
							
							
					</div><!-- /.navbar-collapse -->
				</nav><!-- /.navbar-side -->
			<!-- END SIDE NAVIGATION -->
				

			<!-- BEGIN MAIN PAGE CONTENT -->
				<div id="page-wrapper">
					<!-- BEGIN PAGE HEADING ROW -->
						<div class="row" style="margin-bottom:10px">
							<div class="col-lg-12">
								<!-- BEGIN BREADCRUMB -->
								<div class="breadcrumbs">
								  <?= Breadcrumbs::widget([
								      'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
								  ]) ?>
									
									
									<div class="b-right hidden-xs">
										<ul>
											<li><a href="#" title=""><i class="fa fa-signal"></i></a></li>
											<li><a href="#" title=""><i class="fa fa-comments"></i></a></li>
											<li class="dropdown"><a href="#" title="" data-toggle="dropdown"><i class="fa fa-exchange"></i><span> Tasks</span></a>
												<ul class="dropdown-menu dropdown-primary dropdown-menu-right">
													<li><a href="<?=Url::to($this->params['addUrl'])?>"><i class="fa fa-plus"></i>  <?=Yii::t('app','add new')?></a></li>
													
												</ul>
											</li>
										</ul>
									</div>
								</div>
								<!-- END BREADCRUMB -->	
								
								
								
							</div><!-- /.col-lg-12 -->
						</div><!-- /.row -->
						
					<!-- END PAGE HEADING ROW -->					
						<div class="row">
						   <?=$content;?>	
						</div>
						
					<!-- BEGIN FOOTER CONTENT -->		
						<div class="footer">
							<div class="footer-inner">
								<!-- basics/footer -->
								<div class="footer-content">
									<?=date('Y') ?><?=Yii::$app->params['copyright']?>
								</div>
								<!-- /basics/footer -->
							</div>
						</div>
						<button type="button" id="back-to-top" class="btn btn-primary btn-sm back-to-top">
							<i class="fa fa-angle-double-up icon-only bigger-110"></i>
						</button>
					<!-- END FOOTER CONTENT -->
						
				</div><!-- /#page-wrapper -->	  
			<!-- END MAIN PAGE CONTENT -->
		</div>  
	</div>
<?php $this->endBody() ?>      
  </body>
</html>
<?php $this->endPage() ?>

