<?php
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\HTML;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','bpjs'), 'url' => ['bpjs/form']];
    

$form = ActiveForm::begin([
    'id' => 'bpjs-form',
    'method' => 'post',
    'options' => [
        'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data',    
    ],
    'fieldConfig' => [
        'template' => "{label}{input} <div class='col-lg-offset-2 col-lg-8'>{error}</div>",
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]);
?>

<?=$form->field($model,'employee_identity_id',[
    'inputTemplate' => '<div class="col-lg-8"><div class="input-icon right"><span class="fa fa-user text-gray"></span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app/message','msg identity'),
             'value' => $query?$query->employee_identity_id:'']);?>
             
<?= $form->field($model,'employee_photo',[
    'inputTemplate'=>'<div class="input-group col-lg-7"><span class="input-group-btn" ><span style="margin-left:15px" class="btn btn-file">Browse {input}</span></span><input type="text" class="form-control" readonly></div>'])->fileInput()?>             

<?=$form->field($model,'employee_identity_kk',[
    'inputTemplate' => '<div class="col-lg-8"><div class="input-icon right"><span class="fa fa-users text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','identity family'),
             'value' => $query?$query->employee_identity_kk:'']);?>
             
<?=$form->field($model,'employee_name',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-user text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','full name'),
             'value' => $query?$query->employee_name:Yii::$app->session->get('user.name')]);?>                   

<?=$form->field($model,'employee_birth_place',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-map-marker text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','birth place'),
             'value' => $query?$query->employee_birth_place:'']);?>

<?=$form->field($model,'employee_birth_date',[
 'inputTemplate' => '<div class="col-lg-3"><div class="input-icon right"><span class="fa fa-calendar text-gray">*</span>{input}</div></div>'])                                              
->widget(DatePicker::className(),['dateFormat'=>'dd/MM/yyyy','clientOptions' => ['defaultDate' => '24/01/2014']]) ?>

<?=$form->field($model,'employee_gender',[
     'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right">{input}</div></div>'])
->dropDownList(['L'=>'L','P'=>'P'],['class'=>'col-lg-1','options' => [$query?$query->employee_gender:0=> ["\nselected" => true],]])?>

<?=$form->field($model,'employee_personal_status',[
     'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right">{input}</div></div>'])
->dropDownList(['Kawin'=>'Kawin','Belum Kawin'=>'Belum Kawin','Janda'=>'Janda','Duda'=>'Duda'],['class'=>'col-lg-3','options' => [$query?$query->employee_personal_status:0=> ["\nselected" => true],]])?>

<?=$form->field($model,'employee_address',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-map-marker text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','address'),
             'value' => $query?$query->employee_address:'']);?>

<?=$form->field($model,'employee_address_alt',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-map-marker text-gray"></span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','address'),
             'value' => $query?$query->employee_address_alt:'']);?>
             
<?=$form->field($model,'employee_address_rt',[
    'inputTemplate' => '<div class="col-lg-2"><div class="input-icon right"><span class="fa fa-map-marker text-gray"></span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','address_rt'),
             'value' => $query?$query->employee_address_rt:'']);?>              

<?=$form->field($model,'employee_address_rw',[
    'inputTemplate' => '<div class="col-lg-2"><div class="input-icon right"><span class="fa fa-map-marker text-gray"></span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','address_rw'),
             'value' => $query?$query->employee_address_rw:'']);?>

<?=$form->field($model,'employee_address_kel',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-map-marker text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','address_kel'),
             'value' => $query?$query->employee_address_kel:'']);?>             
             

<?=$form->field($model,'employee_address_kec',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-map-marker text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','address_kec'),
             'value' => $query?$query->employee_address_kec:'']);?>
             
<?=$form->field($model,'employee_address_zip',[
    'inputTemplate' => '<div class="col-lg-2"><div class="input-icon right"><span class="fa fa-map-marker text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','address_zip'),
             'value' => $query?$query->employee_address_zip:'']);?>

<?=$form->field($model,'employee_faskes',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-medkit text-gray"></span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','faskes'),
             'value' => $query?$query->employee_faskes:'']);?>

<?=$form->field($model,'employee_faskes_dr',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-user-md text-gray"></span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','faskes_dr_gigi'),
             'value' => $query?$query->employee_faskes_dr:'']);?>


<?=$form->field($model,'employee_faskes_dr_name',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-user-md text-gray"></span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','faskes_name_dr_gigi'),
             'value' => $query?$query->employee_faskes_dr_name:'']);?>

<?=$form->field($model,'employee_mobile',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-mobile-phone text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','mobile'),
             'value' => $query?$query->employee_mobile:'']);?>

<?=$form->field($model,'employee_email',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-envelope text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','email'),
             'value' => $query?$query->employee_email:'']);?>

<?=$form->field($model,'employee_npp',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-file text-gray"></span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app/message','msg nik'),
             'value' => $query?$query->employee_npp:Yii::$app->session->get('user.nik')]);?>
             
<?=$form->field($model,'employee_position',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-user text-gray">*</span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','position'),
             'value' => $query?$query->employee_position:'']);?>
             
<?=$form->field($model,'employee_status',[
     'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right">{input}</div></div>'])
->dropDownList(['Tetap'=>'Tetap','Kontrak'=>'Kontrak','Paruh Wakut'=>'Paruh Waktu','Pensiun'=>'Pensiun'],['class'=>'col-lg-3','options' => [$query?$query->employee_status:0=> ["\nselected" => true],]])?>
             

<?=$form->field($model,'employee_care_class',[
     'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right">{input}  * Diisi HRD</div></div>'])
->dropDownList(['Kelas I'=>'Kelas I','Kelas II'=>'Kelas II','Kelas III'=>'Kelas III'],['class'=>'col-lg-3','disabled'=>true,'options' => [$query?$query->employee_care_class:0=> ["\nselected" => true],]])?>
             
<?=$form->field($model,'employee_active_date',[
 'inputTemplate' => '<div class="col-lg-3"><div class="input-icon right"><span class="fa fa-calendar text-gray">*</span>{input}</div></div>'])                                              
->widget(DatePicker::className(),['dateFormat' => 'dd/MM/yyyy','clientOptions' => ['defaultDate' => '2014-01-01']]) ?>
             
<?=$form->field($model,'employee_nationality',[
     'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right">{input}</div></div>'])
->dropDownList(['1'=>'WNI','2'=>'WNA'],['class'=>'col-lg-2','options' => [$query?$query->employee_nationality:0=> ["\nselected" => true],]])?>
             
<?=$form->field($model,'employee_npwp',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-file-text text-gray"></span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','npwp'),
             'value' => $query?$query->employee_npwp:'']);?>             

<?=$form->field($model,'employee_passport_no',[
    'inputTemplate' => '<div class="col-lg-10"><div class="input-icon right"><span class="fa fa-file-text text-gray"></span>{input}</div></div>'])
->textInput(['placeholder' => Yii::t('app','passport no'),
             'value' => $query?$query->employee_passport_no:'']);?>

<br/>

<div class="form-group pull-right">
    <div class="col-md-12">
        <?=Html::submitButton('<span class="fa fa-save text-white"></span> '.Yii::t('app','update bio'), ['class' => 'btn btn-primary','name' => 'update'])?>
    </div>
</div>

<div class="clearfix"></div>
             
<?php ActiveForm::end()?>

<script type="text/javascript">

		// Custome File Input
		$(document).on('change', '.btn-file :file', function() {
			var input = $(this),
			numFiles = input.get(0).files ? input.get(0).files.length : 1,
			label = input.val().replace(/\\/g, 'http://qsthemes.com/').replace(/.*\//, '');
			input.trigger('fileselect', [numFiles, label]);
		});

		$(document).ready( function() {
			$('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        
				var input = $(this).parents('.input-group').find(':text'),
				log = numFiles > 1 ? numFiles + ' files selected' : label;
        
				if( input.length ) {
					input.val(log);
				} else {
					if( log ) alert(log);
				}
        
			});
		});
	</script>