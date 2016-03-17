<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->params ['breadcrumbs'] [] = [ 
		'label' => Yii::t ( 'app', 'login' ),
		'url' => [ 'site/login' ] 
];
$this->params ['addUrl'] = 'ticket/add';

?>
<div class="row">
	<div class="col-lg-9">
		<!--  Sliding Show -->
		<div class="row">
			<div class="col-md-12">
				<div id="jssor_1"
					style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 1310px; height: 435px; overflow: hidden; visibility: hidden;">
					<!-- Loading Screen -->
					<div data-u="loading"
						style="position: absolute; top: 0px; left: 0px;">
						<div
							style="filter: alpha(opacity = 70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
						<div
							style="position: absolute; display: block; background: url('assets/images/loading.gif') no-repeat center center; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
					</div>
					<div data-u="slides"
						style="cursor: default; position: relative; top: 0px; left: 0px; width: 1300px; height: 500px; overflow: hidden;">
						<div data-p="225.00" style="display: none;">
							<img data-u="image" src="assets/images/red.jpg" />
						</div>
						
						<div data-p="225.00" style="display: none;">
							<img data-u="image" src="assets/images/purple.jpg" />
						</div>
						<div data-p="225.00" style="display: none;">
							<img data-u="image" src="assets/images/blue.jpg" />
						</div>
					
					</div>
					<!-- Bullet Navigator -->
					<div data-u="navigator" class="jssorb05"
						style="bottom: 16px; right: 16px;" data-autocenter="1">
						<!-- bullet navigator item prototype -->
						<div data-u="prototype" style="width: 16px; height: 16px;"></div>
					</div>
					<!-- Arrow Navigator -->
					<span data-u="arrowleft" class="jssora22l"
						style="top: 0px; left: 12px; width: 40px; height: 58px;"
						data-autocenter="2"></span> <span data-u="arrowright"
						class="jssora22r"
						style="top: 0px; right: 12px; width: 40px; height: 58px;"
						data-autocenter="2"></span> <a href="http://www.jssor.com"
						style="display: none">Slideshow Maker</a>
				</div>
			</div>
		</div>
		<!-- #endregion Jssor Slider End -->
		<!--  End Of Sliding Show -->
	</div>

	<div class="col-lg-3">
		<div class="portlet portlet-basic">
			<div class="portlet-heading">
				<div class="portlet-title">
					<h4><?=Yii::t('app','login')?></h4>
				</div>
			</div>
			<div class="portlet-body">
				<div class="row">
		    <?php if(Yii::$app->session->getFlash('msg')){?>
                 <div class="notice bg-danger marker-on-left"><?=Yii::$app->session->getFlash('msg')?></div>
		    <?php } ?> 
		    <div class="col-md-12">
						<!-- BEGIN LOGIN BOX -->
						<div id="login-box" class="visible">
							<p class="bigger-110">
								<i class="fa fa-key"></i><?=Yii::t('app/message','msg please enter your information')?></p>
							<div class="hr hr-8 hr-double dotted"></div>
			  <?php $form = ActiveForm::begin ( [ 
							'id' => 'form',
							'method' => 'post',
							'fieldConfig' => [ 
									'template' => "{label}{input}{error}" 
							] 
					] );
					?>
		
			  <?=$form->field ( $model, 'EmployeeID', [ 'inputTemplate' => '<div class="form-group"><div class="input-icon right"><span class="fa fa-user text-gray"></span>{input}</div></div>' ] )->textInput ( [ 'maxlength' =>5, 'placeholder' => Yii::t ( 'app/message', 'msg enter your nik' ) ] );?>
			  <?=$form->field ( $model, 'passtext', [ 'inputTemplate' => '<div class="form-group"><div class="input-icon right"><span class="fa fa-key text-gray"></span>{input}</div></div>' ] )->passwordInput ( [ 'maxlength' =>15, 'placeholder' => Yii::t ( 'app/message', 'msg enter your password' ) ] );?>
			    <div class="tcb">
								<label> <?=$form->field($model,'remember_me')->checkbox()?></label>
								<div class="form-group pull-right">
				 <?=Html::submitButton('<i class="fa fa-key icon-on-right"></i> '.Yii::t('app','login'), ['class' => 'btn btn-primary','name' => 'login'])?>
			       </div>
							</div>
							<div class="space-4"></div>
			 <?php $form = ActiveForm::end();?>  											
			</div>
						<!-- END LOGIN BOX -->
					</div>
				</div>
			</div>
		</div>
		<!-- END YOUR CONTENT HERE -->
	</div>
</div>



<script>
	jQuery(document).ready(function ($) {
            
            var jssor_1_SlideoTransitions = [
              [{b:5500,d:3000,o:-1,r:240,e:{r:2}}],
              [{b:-1,d:1,o:-1,c:{x:51.0,t:-51.0}},{b:0,d:1000,o:1,c:{x:-51.0,t:51.0},e:{o:7,c:{x:7,t:7}}}],
              [{b:-1,d:1,o:-1,sX:9,sY:9},{b:1000,d:1000,o:1,sX:-9,sY:-9,e:{sX:2,sY:2}}],
              [{b:-1,d:1,o:-1,r:-180,sX:9,sY:9},{b:2000,d:1000,o:1,r:180,sX:-9,sY:-9,e:{r:2,sX:2,sY:2}}],
              [{b:-1,d:1,o:-1},{b:3000,d:2000,y:180,o:1,e:{y:16}}],
              [{b:-1,d:1,o:-1,r:-150},{b:7500,d:1600,o:1,r:150,e:{r:3}}],
              [{b:10000,d:2000,x:-379,e:{x:7}}],
              [{b:10000,d:2000,x:-379,e:{x:7}}],
              [{b:-1,d:1,o:-1,r:288,sX:9,sY:9},{b:9100,d:900,x:-1400,y:-660,o:1,r:-288,sX:-9,sY:-9,e:{r:6}},{b:10000,d:1600,x:-200,o:-1,e:{x:16}}]
            ];
            
            var jssor_1_options = {
              $AutoPlay: true,
              $SlideDuration: 800,
              $SlideEasing: $Jease$.$OutQuint,
              $CaptionSliderOptions: {
                $Class: $JssorCaptionSlideo$,
                $Transitions: jssor_1_SlideoTransitions
              },
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
              },
              $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
              }
            };
            
            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);
            
            //responsive code begin
            //you can remove responsive code if you don't want the slider scales while window resizing
            function ScaleSlider() {
                var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                if (refSize) {
                    refSize = Math.min(refSize, 1920);
                    jssor_1_slider.$ScaleWidth(refSize);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }
            ScaleSlider();
            $(window).bind("load", ScaleSlider);
            $(window).bind("resize", ScaleSlider);
            $(window).bind("orientationchange", ScaleSlider);
            //responsive code end
        });
</script>