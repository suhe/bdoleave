<?php
use app\assets\AppAsset;
use app\assets\AppAssetIE8;
use app\assets\AppAssetIE9;
use yii\helpers\Url;
AppAsset::register ( $this );
AppAssetIE8::register ( $this );
AppAssetIE9::register ( $this );
if (! Yii::$app->user->isGuest) {
	$model = new \app\models\Leaves ();
	$totalLeaveBalanceApp = count ( $model->getLeaveApprovalData () );
	$totalLeaveApp = count ( $model->getLeaveApprovalData () );
}
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="utf-8">
<title><?=Yii::t('app','page title').' - '.$this->title?></title>
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
					<button type="button" class="navbar-toggle pull-right"
						data-toggle="collapse" data-target=".top-collapse">
						<i class="fa fa-bars"></i>
					</button>
					<div class="navbar-brand">
						<a href="<?=Url::to(['/'])?>"><img src="assets/images/logo.png" alt="logo" class="img-responsive"></a>
					</div>
				</div>
				<!-- END BRAND HEADING -->

				<!-- BEGIN NAV TOP NAVIGATION -->
				<div class="nav-top">
					
				</div>
				<!-- /.nav-top -->
			</nav>
			<!-- /.navbar-top -->
			<!-- END TOP NAVIGATION -->

			<!-- BEGIN MAIN PAGE CONTENT -->
			<div id="page-wrapper-no-nav">
				<!-- BEGIN PAGE HEADING ROW -->
				<div class="row" style="margin-bottom: 10px">
					<div class="col-lg-12">
						
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<!-- /.row -->

				<!-- END PAGE HEADING ROW -->
				<div class="row">
						   <?=$content;?>	
						</div>

				<!-- BEGIN FOOTER CONTENT -->
				<div class="footer">
					<div class="footer-inner-no-nav">
						<!-- basics/footer -->
						<div class="footer-content">
									<?=date('Y') ?><?=Yii::$app->params['copyright']?>
								</div>
						<!-- /basics/footer -->
					</div>
				</div>
				<button type="button" id="back-to-top"
					class="btn btn-primary btn-sm back-to-top">
					<i class="fa fa-angle-double-up icon-only bigger-110"></i>
				</button>
				<!-- END FOOTER CONTENT -->

			</div>
			<!-- /#page-wrapper -->
			<!-- END MAIN PAGE CONTENT -->
		</div>
	</div>
<?php $this->endBody() ?>      
  </body>
</html>
<?php $this->endPage() ?>

