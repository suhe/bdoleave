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
		<div id="jssor_1"
			style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 100%; height: 325px; overflow: hidden; visibility: hidden;">
			<!-- Loading Screen -->
			<div data-u="loading"
				style="position: absolute; top: 0px; left: 0px;">
				<div
					style="filter: alpha(opacity = 70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
				<div
					style="position: absolute; display: block; background: url('img/loading.gif') no-repeat center center; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
			</div>
			<div data-u="slides"
				style="cursor: default; position: relative; top: 0px; left: 0px; width: 600px; height: 325px; overflow: hidden;">
				<div data-p="112.50" style="display: none;">
					<img data-u="image" src="<?=Yii::$app->request->baseUrl?>/assets/images/01.jpg" />
					<div data-u="thumb">Do you notice it is draggable by mouse/finger?</div>
				</div>
				<div data-p="112.50" style="display: none;">
					<img data-u="image" src="<?=Yii::$app->request->baseUrl?>/assets/images/02.jpg" />
					<div data-u="thumb">Did you drag by either horizontal or vertical?</div>
				</div>
				<div data-p="112.50" style="display: none;">
					<img data-u="image" src="<?=Yii::$app->request->baseUrl?>/assets/images/03.jpg" />
					<div data-u="thumb">Do you notice navigator responses when drag?</div>
				</div>
				<div data-p="112.50" style="display: none;">
					<img data-u="image" src="<?=Yii::$app->request->baseUrl?>/assets/images/04.jpg" />
					<div data-u="thumb">Do you notice arrow responses when click?</div>
				</div>
			</div>
			<!-- Thumbnail Navigator -->
			<div data-u="thumbnavigator" class="jssort09-600-45"
				style="position: absolute; bottom: 0px; left: 0px; width: 600px; height: 45px;">
				<div
					style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: #000; filter: alpha(opacity = 40.0); opacity: 0.4;"></div>
				<!-- Thumbnail Item Skin Begin -->
				<div data-u="slides" style="cursor: default;">
					<div data-u="prototype" class="p">
						<div data-u="thumbnailtemplate" class="t"></div>
					</div>
				</div>
				<!-- Thumbnail Item Skin End -->
			</div>
			<!-- Bullet Navigator -->
			<div data-u="navigator" class="jssorb01"
				style="bottom: 16px; right: 16px;">
				<div data-u="prototype" style="width: 12px; height: 12px;"></div>
			</div>
			<!-- Arrow Navigator -->
			<span data-u="arrowleft" class="jssora05l"
				style="top: 0px; left: 8px; width: 40px; height: 40px;"
				data-autocenter="2"></span> <span data-u="arrowright"
				class="jssora05r"
				style="top: 0px; right: 8px; width: 40px; height: 40px;"
				data-autocenter="2"></span> <a href="http://www.jssor.com"
				style="display: none">Slideshow Maker</a>
		</div>
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
		
			  <?=$form->field ( $model, 'EmployeeID', [ 'inputTemplate' => '<div class="form-group"><div class="input-icon right"><span class="fa fa-user text-gray"></span>{input}</div></div>' ] )->textInput ( [ 'placeholder' => Yii::t ( 'app/message', 'msg enter your nik' ) ] );?>
			  <?=$form->field ( $model, 'passtext', [ 'inputTemplate' => '<div class="form-group"><div class="input-icon right"><span class="fa fa-key text-gray"></span>{input}</div></div>' ] )->passwordInput ( [ 'placeholder' => Yii::t ( 'app/message', 'msg enter your password' ) ] );?>
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
            
            var jssor_1_SlideshowTransitions = [
              {$Duration:1200,x:-0.3,$During:{$Left:[0.3,0.7]},$Easing:{$Left:$Jease$.$InCubic,$Opacity:$Jease$.$Linear},$Opacity:2},
              {$Duration:1200,x:0.3,$SlideOut:true,$Easing:{$Left:$Jease$.$InCubic,$Opacity:$Jease$.$Linear},$Opacity:2}
            ];
            
            var jssor_1_options = {
              $AutoPlay: true,
              $SlideshowOptions: {
                $Class: $JssorSlideshowRunner$,
                $Transitions: jssor_1_SlideshowTransitions,
                $TransitionsOrder: 1
              },
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
              },
              $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
              },
              $ThumbnailNavigatorOptions: {
                $Class: $JssorThumbnailNavigator$,
                $Cols: 1,
                $Align: 0
              }
            };
            
            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);
            
            //responsive code begin
            //you can remove responsive code if you don't want the slider scales while window resizing
            function ScaleSlider() {
                var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                if (refSize) {
                    refSize = Math.min(refSize, 100);
                    jssor_1_slider.$ScaleWidth(refSize);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }
            //ScaleSlider();
            $(window).bind("load", ScaleSlider);
            $(window).bind("resize", ScaleSlider);
            $(window).bind("orientationchange", ScaleSlider);
            //responsive code end
        });
    </script>
