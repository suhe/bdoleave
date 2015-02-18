<?php
use yii\helpers\Url;
?>
<div class="col-lg-12">
    <!-- START YOUR CONTENT HERE -->
    <div class="row">
	<div class="col-lg-12 col-md-12">
	    <div class="tc-tabs"><!-- Nav tabs style 1 -->
		<!-- Tab Menu--> 
                <ul class="nav nav-tabs tab-lg-button tab-color-dark background-dark white">
		    <li <?=$tabPage=='setting_general'?'class="active"':''?> >
			<a href="<?=Url::to(['administration/general'])?>"><i class="fa fa-edit bigger-130"></i> <?=Yii::t('app','general setting')?></a>
		    </li>
		    <li <?=$tabPage=='setting_approval'?'class="active"':''?> >
			<a href="<?=Url::to(['administration/approval'])?>"><i class="fa fa-user bigger-130"></i> <?=Yii::t('app','leave approval')?></a>
		    </li>
		    <li <?=$tabPage=='setting_password'?'class="active"':''?> >
			<a href="<?=Url::to(['administration/password'])?>"><i class="fa fa-key bigger-130"></i> <?=Yii::t('app','change password')?></a>
		    </li>
		</ul>
                <!-- end Tab Menu -->
		
                <!-- Tab Content -->
		<div class="tab-content">
                    <div class="tab-pane fade in active" id="tab">
                        <?php if(Yii::$app->session->getFlash('msg')){?>
                        <div class="notice bg-success marker-on-left"><?=Yii::$app->session->getFlash('msg')?></div>
			<?php } ?>
                        <?=$this->render($tabPage,[
			    'model' => $model,
			    'query' => $query
			])?>								
		    </div>
		</div>
	    </div><!--nav-tabs style 1-->
	</div>
    </div>		
    <!-- END YOUR CONTENT HERE -->					
</div>