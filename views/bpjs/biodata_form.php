<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="row">
    <div class="col-lg-9">
        <div class="portlet portlet-basic">
	    <div class="portlet-heading">
                <div class="portlet-title">
		    <h4><?=$title;?></h4>
		</div>								
	    </div>
            <div class="portlet-body">
		<?php if(Yii::$app->session->getFlash('msg')){?>
		<div class="note bg-success">
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
		    <h4><i class="fa fa-tags"></i> <?=Yii::t('app','status')?></h4>
		    <hr class="separator">
		    <p><?=Yii::$app->session->getFlash('msg')?></p>
		</div>
		<?php } ?>
                <p class="help-block">Keterangan : Tanda *  <?=Yii::t('app','must required')?></p>
		<div class="clearfix" style="margin-bottom:20px"></div>
		
		<?=$this->render($page,[
                        'model'=>$model,
                        'query'=>$query,
			'master'=>$master,
			'page' => $page,
			'post' => $post,
                        'pisatDropdown'=> $pisatDropdown
                    ])?>																												
            </div>
        </div>
    <!-- END YOUR CONTENT HERE -->
    </div>
							
    <div class="col-lg-3">
	<div class="portlet portlet-basic"><!-- /with badge -->
	    <div class="portlet-heading">
		<div class="portlet-title">
		    <h4><i class="fa fa-warning text-warning"></i> BJPS Form</h4>
		</div>
            </div>
	    <div id="notes-warn" class="panel-collapse collapse in">
		<div class="portlet-body">
		    <div class="note"><a href="<?=Url::to(['bpjs/form'])?>">Biodata Karyawan</a></div>
                    <div class="note"><a href="<?=Url::to(['bpjs/form','key'=>'2'])?>">Biodata Suami/Istri</a></div>
                    <div class="note"><a href="<?=Url::to(['bpjs/form','key'=>'3'])?>">Biodata Anak ke 1</a></div>
                    <div class="note"><a href="<?=Url::to(['bpjs/form','key'=>'4'])?>">Biodata Anak ke 2</a></div>
                    <div class="note"><a href="<?=Url::to(['bpjs/form','key'=>'5'])?>">Biodata Anak ke 3</a></div>
		</div>
	    </div>
	</div><!-- /with badge -->
    </div>
</div>				

