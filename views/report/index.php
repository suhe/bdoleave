<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->params ['breadcrumbs'] = [ 
		[ 
				'label' => Yii::t ( 'app', 'reports' ),
				'url' => [ 'report/index' ] 
		] 
]
;
$this->params ['addUrl'] = [ 
		'/' 
];
?>
<div class="row">
	<div class="col-lg-12">
		<!-- START YOUR CONTENT HERE -->
		<div class="portlet">
			<!-- /Portlet -->
			<div class="portlet-heading dark">
				<div class="portlet-title">
					<h4 class="text-white">
						<i class="fa fa-github"></i> <?=$title?></h4>
				</div>
				<div class="portlet-widgets">
					<a data-toggle="collapse" data-parent="#accordion" href="#basic"><i
						class="fa fa-chevron-down"></i></a> <span class="divider"></span>
					<a href="#" class="box-close"><i class="fa fa-times"></i></a>
				</div>
				<div class="clearfix"></div>
			</div>

			<div id="basic" class="panel-collapse collapse in">
				<div class="portlet-body">

					<div class="row">
						<div class="col-md-6">
							 <ul class="list-group">
							  <li class="list-group-item"><a href="<?=Url::to(['report/outstanding'])?>"><i class="fa fa-newspaper-o"></i> <?=Yii::t('app','outstanding report')?> </a>  </li>
							 
							</ul>
						</div>
						
						<div class="col-md-6">
							 <ul class="list-group">
							   <li class="list-group-item"><a href="<?=Url::to(['report/balanced-summary'])?>"><i class="fa fa-newspaper-o"></i> <?=Yii::t('app','employee leave balance summary')?> </a>  </li>
							   <li class="list-group-item"><a href="<?=Url::to(['report/balanced-card'])?>"><i class="fa fa-newspaper-o"></i> <?=Yii::t('app','employee stok balance card')?> </a>  </li>
							</ul>
						</div>
						
					</div>

				</div>
			</div>
		</div>
		<!--/Portlet -->
	</div>
	<!-- Enf of col lg-->
</div>
<!-- ENd of row -->