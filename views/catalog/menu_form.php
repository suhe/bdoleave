<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
?>
<div class="row">
    <div class="col-lg-12">						
	<!-- START YOUR CONTENT HERE -->
            <div class="portlet">
		<div class="portlet-heading dark">
		    
		    <div class="portlet-widgets">
			<a data-toggle="collapse" data-parent="#accordion" href="#ft-3"><i class="fa fa-chevron-down"></i></a>
		    </div>
		    <div class="clearfix"></div>
		</div>
		
                <div id="ft-3" class="panel-collapse collapse in">
		    <div class="portlet-body">
                        <?php $form = ActiveForm::begin([
                        'id' => 'menu-add-form',
                        'method' => 'POST',
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-sm-8 search\">{input} <br/> <br/>{error}</div>\n",
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                        ],
                        ]);?>
                        <?=$form->field($model,'menu_label')->textInput(['class'=>'col-lg-5','value' => $query?$query->menu_label:'']);?>
                        <?=$form->field($model,'menu_position')->dropDownList(['Top'=>'Top','Right'=>'Right','Bottom'=>'Bottom','Left'=>'Left'],['class'=>'col-lg-5',
									      'options' => [$query?$query->menu_position:0=> ["\nselected" => true],]])?>
			<?=$form->field($model,'menu_order')->textInput(['class'=>'col-lg-1','value' => $query?$query->menu_order:'' ]);?>
                        <?=$form->field($model,'menu_default')->dropDownList([1=>'Page',2=>'Category'],['class'=>'col-lg-2','options' => [$query?$query->page_id>0?1:2:0=> ["\nselected" => true],]]);?>
                        <?=$form->field($model,'page_id')->dropDownList($dropdownPage,['class'=>'col-lg-5'],['class'=>'col-lg-5','options' => [$query?$query->page_id:0=> ["\nselected" => true],]])?>
			<?=$form->field($model,'category_id')->dropDownList($dropdownCategory,['class'=>'col-lg-5','options' => [$query?$query->category_id:0=> ["\nselected" => true],]])?>
			<?=$form->field($model,'menu_parent_id')->dropDownList($dropdownMenu,['class'=>'col-lg-5','options' => [$query?$query->menu_parent_id:0=> ["\nselected" => true],]])?>
                        <div class="form-group pull-right">
                            <div class="col-md-offset-1 col-md-11">
                                <?=Html::submitButton(Yii::t('app','save'), ['class' => 'btn btn-primary','name' => 'save-button'])?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        
                        <?php ActiveForm::end() ?>
                         
		    </div>
		</div>
            </div>
    </div>
</div>
<style>
    .help-block{line-height:10px;}
</style>
