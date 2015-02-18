<?php
use backend\assets\AppAsset;
use backend\assets\AppAssetIE8;
use backend\assets\AppAssetIE9;
use yii\helpers\Url;
use yii\helpers\Html;
AppAsset::register($this);
AppAssetIE8::register($this);
AppAssetIE9::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="utf-8">
    <title><?=Yii::t('app/backend','page title').' '.$this->title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
<?php $this->head() ?>	
  </head>
<?php $this->beginBody() ?>  
  <body class="login">
    <div id="wrapper">
    <!-- BEGIN MAIN PAGE CONTENT -->
        <div class="login-container">
	    <h2><a href="index.html"><img src="assets/images/logo.png" alt="logo" class="img-responsive"></a></h2>
	    <?=$content;?>
	<!-- END MAIN PAGE CONTENT --> 
	</div> 
<script type="text/javascript">
    function show_box(id) {
	jQuery('.login-box.visible').removeClass('visible');
	    jQuery('#'+id).addClass('visible');
}
</script>
<?php $this->endBody()?>         
  </body>
</html>
<?php $this->endPage()?>