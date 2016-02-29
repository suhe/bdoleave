<?php
use app\assets\AppAsset;
use app\assets\AppAssetIE8;
use app\assets\AppAssetIE9;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\models\Employee;
use app\models\Leaves;

AppAsset::register($this);
AppAssetIE8::register($this);
AppAssetIE9::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="utf-8">
    <title><?=Yii::t('app','page title').$this->title?></title>
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
	    
	    
	    <li class="dropdown">
		<a title="<?=Yii::t('app','to be approval')?>"  href="#" class="dropdown-toggle" data-toggle="dropdown">
		  <i class="fa fa-exchange"></i> <?php if(Leaves::sumAppLeave()){ ?><span class="badge up badge-primary"><?=Leaves::sumAppLeave()?></span><?php } ?></a>
		    <ul class="dropdown-menu dropdown-scroll dropdown-messages">
			<li class="dropdown-header"><i class="fa fa-exchange"></i> <?=Leaves::sumAppLeave()?> <?=Yii::t('app','to be approval')?></li>
			<li id="messageScroll">
			  <ul class="list-unstyled">
			   <?php 
			   if(Leaves::sumAppLeave()) {
			   foreach(Leaves::getAppLeave() as $row){ ?>
			    <li>
			      <a href="<?=Url::to(['app-leave/form','id'=>$row->leave_id])?>">
					<div class="row">
						  <div class="col-xs-12">
						    <p>
						      <strong><?=$row->employee_name?></strong>: <?=$row->leave_date?>
						    </p>
						    <p class="small">
						      <i class="fa fa-clock-o"></i> <?=\app\components\Common::timeAgo(strtotime($row->leave_created_date))?>
						    </p>
						  </div>
					</div>
			      </a>
			    </li>
			    <?php } } ?>
			  </ul>
			</li>
			<li class="dropdown-footer">
			  <a href="<?=Url::to(['app-leave/'])?>"><?=Yii::t('app','read all')?></a>
			</li>
		      </ul>
	      </li>
	     
	      
	      <!--Speech Icon-->
	      <!--<li class="dropdown">
		<a href="#" class="speech-button">
		  <i class="fa fa-microphone"></i>
		</a>
	      </li>-->
	      <!--Speech Icon-->
	      <li class="dropdown user-box">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		  <img class="img-circle" src="assets/images/user.jpg" alt=""> <span class="user-info"><?=Yii::$app->user->identity->EmployeeFirstName?> (<?=Yii::$app->user->identity->EmployeeTitle?>)</span> <b class="caret"></b>
		</a>
		<ul class="dropdown-menu dropdown-user">
		  <li><?=Html::a('<i class="fa fa-user-md"></i> '.Yii::t('app','my profile'),['administration/general'])?></li>
		  <li><?=Html::a('<i class="fa fa-pencil"></i> '.Yii::t('app','approval setting'),['administration/approval'])?></li>
		  <li><?=Html::a('<i class="fa fa-key"></i> '.Yii::t('app','change password'),['administration/password'])?></li>
		  <li><?=Html::a('<i class="fa fa-power-off"></i> '.Yii::t('app','logout'),['site/logout'])?></li>
		</ul>
	   </li>
	     
	      
	    </ul>
	    <!-- END RIGHT SIDE DROPDOWN BUTTONS -->
	    				
	    <!-- BEGIN TOP MENU -->
	    <div class="collapse navbar-collapse top-collapse">
	      <!-- .nav -->
	      <ul class="nav navbar-left navbar-nav">
			<li><a href="<?=Url::to(['my-leave/form'])?>"><i class="fa fa-plus"></i> <?=Yii::t('app','leave form')?></a></li>
		
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
						
						
						<?php echo \yii\widgets\Menu::widget([
						    'items' => [
						        ['label' => '<i class="fa fa-user-md"></i> ' .Yii::t('app','my leave'), 'url' => ['my-leave/'],'visible' => true],
						    	['label' => '<i class="fa fa-plus"></i> ' .Yii::t('app','to be approval'), 'url' => ['app-leave/'],'visible' => Yii::$app->user->isGuest ? false :true],
						    	['label' => '<i class="fa fa-newspaper-o"></i> ' .Yii::t('app','manage leave'), 'url' => ['manage-leave/'],'visible' => (Yii::$app->user->identity->EmployeeTitle == Employee::ROLE_MANAGER_HRD || Yii::$app->user->identity->EmployeeTitle == Employee::ROLE_SENIOR_HRD  ) ? true :false],
						    	['label' => '<i class="fa fa-user"></i> ' .Yii::t('app','employee'), 'url' => ['employee/'],'visible' => (Yii::$app->user->identity->EmployeeTitle == Employee::ROLE_MANAGER_HRD || Yii::$app->user->identity->EmployeeTitle == Employee::ROLE_SENIOR_HRD  ) ? true :false],
						    	['label' => '<i class="fa fa-area-chart"></i> ' .Yii::t('app','holiday list'), 'url' => ['holiday/'],'visible' => (Yii::$app->user->identity->EmployeeTitle == Employee::ROLE_MANAGER_HRD || Yii::$app->user->identity->EmployeeTitle == Employee::ROLE_SENIOR_HRD  ) ? true :false],
						    	['label' => '<i class="fa fa-github"></i> ' .Yii::t('app','reports'), 'url' => ['report/'],'visible' => (Yii::$app->user->identity->EmployeeTitle == Employee::ROLE_MANAGER_HRD || Yii::$app->user->identity->EmployeeTitle == Employee::ROLE_SENIOR_HRD  ) ? true :false],
						    ],
							'options' => [
								'class' => 'nav navbar-nav side-nav',
								'id' => 'side'
							],
							'activeCssClass'=>'active',
							'encodeLabels' => false,
							'labelTemplate' =>'{label} Label',
							'linkTemplate' => '<a href="{url}">{label}<span class="fa arrow"></span></a>',
							'submenuTemplate' => "\n<ul class='collapse in nav' role='menu'>\n{items}\n</ul>\n",
								
						]);
						?>
							
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
						  <?php if(!Yii::$app->user->isGuest){?>
						  <?php if($message=\app\models\Leaves::ExpiredNotify()){ ?>
						    <div class="notice bg-danger marker-on-left"><?=$message?></div>
						  <?php }
						    }
						  ?>
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

