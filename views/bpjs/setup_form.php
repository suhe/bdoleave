<?php
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\HTML;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','setup'), 'url' => ['bpjs/setup']];
    

$form = ActiveForm::begin([
    'id' => 'setup-form',
    'method' => 'post',
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}{input} <div class='col-lg-offset-2 col-lg-8'>{error}</div>",
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]);
?>

<?=$form->field($model,'bpjs_type',[
     'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right">{input}</div></div>'])
->dropDownList($bpjsType,['class'=>'col-lg-3','options' => [$query?$query->bpjs_type:0=> ["\nselected" => true],]])?>


<?=$form->field($model,'bpjs_company',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-user text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','company'),
             'value' => $query?$query->bpjs_company:'']);?>


<?=$form->field($model,'bpjs_vc',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-user text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','bpjs vc'),
             'value' => $query?$query->bpjs_vc:'']);?>
             
<?=$form->field($model,'bpjs_bank',[
     'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right">{input}</div></div>'])
->dropDownList($bpjsBank,['class'=>'col-lg-3','options' => [$query?$query->bpjs_bank:0=> ["\nselected" => true],]])?>

<?=$form->field($model,'bpjs_registration_date',[
 'inputTemplate' => '<div class="col-lg-3"><div class="input-icon right"><span class="fa fa-calendar text-gray">*</span>{input}</div></div>'])                                              
->widget(DatePicker::className(),['dateFormat'=>'dd/MM/yyyy','clientOptions' => ['defaultDate' => '24/01/2014']]) ?>


<?=$form->field($model,'bpjs_pks',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-user text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','pks no'),
             'value' => $query?$query->bpjs_pks:'']);?>                   

<?=$form->field($model,'bpjs_pks_code',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-user text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','pks code'),
             'value' => $query?$query->bpjs_pks_code:'']);?>

<?=$form->field($model,'bpjs_expired_date',[
 'inputTemplate' => '<div class="col-lg-3"><div class="input-icon right"><span class="fa fa-calendar text-gray">*</span>{input}</div></div>'])                                              
->widget(DatePicker::className(),['dateFormat'=>'dd/MM/yyyy','clientOptions' => ['defaultDate' => '24/01/2014']]) ?>


<?=$form->field($model,'bpjs_dependent_code',[
     'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right">{input}</div></div>'])
->dropDownList($bpjsDependent,['class'=>'col-lg-3','options' => [$query?$query->bpjs_dependent_code:0=> ["\nselected" => true],]])?>

<?=$form->field($model,'bpjs_kc_code',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-user text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','kc code'),
             'value' => $query?$query->bpjs_kc_code:'']);?>

<?=$form->field($model,'bpjs_dati2',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-user text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','dati2 code'),
             'value' => $query?$query->bpjs_dati2:'']);?>

<br/>

<div class="form-group pull-right">
    <div class="col-md-12">
        <?=Html::submitButton('<span class="fa fa-save text-white"></span> '.Yii::t('app','update'), ['class' => 'btn btn-primary','name' => 'update'])?>
    </div>
</div>

<div class="clearfix"></div>
             
<?php ActiveForm::end()?>