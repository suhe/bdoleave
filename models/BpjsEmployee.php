<?php
namespace app\models;
use yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class BpjsEmployee extends ActiveRecord {
    
    public $EmployeeID;
    
    
    public static function tableName(){
        return 'bpjs_employee';
    }
    
    
    public function rules(){
        return [
          //required for all
          [['employee_pisat','employee_identity_kk','employee_name','employee_birth_place','employee_birth_date',
            'employee_gender','employee_relation','employee_personal_status','employee_address','employee_address_kel',
            'employee_address_kec','employee_address_zip','employee_nationality','employee_id','id'],
            'required','on'=>['personal','relation','child']],
          //required for personal,relation
          [['employee_identity_id'],'required','on'=>['personal','relation']],
          //required for personal
          [['employee_email','employee_mobile','employee_position','employee_status','employee_active_date'
            ],'required','on'=>'personal'],
          //safe for personal
          [['employee_photo','employee_npwp'],'safe','on'=>'personal'],
          //safe for child
          [['employee_identity_id','employee_active_date','employee_npwp','employee_position',
            'employee_mobile','employee_email','employee_status'],'safe','on'=>'child'],
          //safe for relation
          [['employee_mobile','employee_position','employee_status','employee_active_date','employee_npwp'],'safe','on'=>'relation'],
          //email
          [['employee_email'],'email','on'=>['personal','relation','child']],
          //number
          [['employee_salary','employee_mobile','employee_npwp'],'number','on'=>'personal'],
          //safe
          [['employee_address_alt','employee_address_rt','employee_address_rw','employee_faskes',
            'employee_faskes_dr','employee_faskes_dr_name','employee_npp','employee_care_class',
            'employee_assurance_polis_no','employee_assurance_name','employee_passport_no',
            'employee_created_date','employee_updated_date','employee_salary'],'safe'],
          [['employee_photo'], 'file','on'=>['personal']],
        ];
    }
    
    
    public function pisatDropdown(){
        $data = ['L','P','Istri','Anak ke 1','Anak ke 2','Anak ke 3'];
        return $data;
    }
    
    public function attributeLabels(){
        return [
            'EmployeeID' => Yii::t('app','nik'),
            'employee_identity_kk' => Yii::t('app','identity family'),
            'employee_identity_id' => Yii::t('app','nik'),
            'employee_name' => Yii::t('app','full name'),
            'employee_photo' => Yii::t('app','photo'),
            'employee_pisat' => Yii::t('app','pisat'),
            'employee_birth_place' => Yii::t('app','birth place'),
            'employee_birth_date' => Yii::t('app','birth date'),
            'employee_gender' => Yii::t('app','gender'),
            'employee_personal_status' => Yii::t('app','status'),
            'employee_address' => Yii::t('app','address'),
            'employee_address_alt' => '',
            'employee_address_rt' => Yii::t('app','address_rt'),
            'employee_address_rw' => Yii::t('app','address_rw'),
            'employee_address_kel' => Yii::t('app','address_kel'),
            'employee_address_kec' => Yii::t('app','address_kec'),
            'employee_address_zip' => Yii::t('app','address_zip'),
            'employee_faskes' => Yii::t('app','faskes'),
            'employee_faskes_dr' => Yii::t('app','faskes_dr_gigi'),
            'employee_faskes_dr_name' => Yii::t('app','faskes_name_dr_gigi'),
            'employee_mobile' => Yii::t('app','mobile'),
            'employee_email' => Yii::t('app','email'),
            'employee_npp' => Yii::t('app','npp'),
            'employee_position' => Yii::t('app','position'),
            'employee_status' => Yii::t('app','status'),
            'employee_care_class' => Yii::t('app','care_class'),
            'employee_active_date' => Yii::t('app','start_work'),
            'employee_salary' => Yii::t('app','salary'),
            'employee_nationality' => Yii::t('app','nationality'),
            'employee_assurance_polis_no' => Yii::t('app','polis no'),
            'employee_assurance_name' => Yii::t('app','assurance name'),
            'employee_npwp' => Yii::t('app','npwp'),
            'employee_passport_no' => Yii::t('app','passport no'),
            'employee_created_date' => Yii::t('app','registration date'),
        ];
    }
    
    public function getUpdate($post){
        $employee = new BpjsEmployee();
        $find = $employee->find()
        ->where(['employee_id'=>Yii::$app->session->get('user.id'),'employee_relation'=>$post['relation']])
        ->one();
        if($find){
            $employee = $find;
            $employee->employee_updated_date = date('Y-m-d H:i:s');
        }
        else {
            $employee->employee_id = Yii::$app->session->get('user.id');
            $employee->employee_created_date = date('Y-m-d H:i:s');
        }
        
        if($post['relation']==2)
            $post['pisat'] = $this->employee_pisat;
            
        $employee->employee_relation = $post['relation'];
        $employee->employee_pisat = $post['pisat'];
        $employee->employee_identity_id = $this->employee_identity_id?$this->employee_identity_id:"-"; 
        $employee->employee_identity_kk = $this->employee_identity_kk; 
        $employee->employee_name = $this->employee_name;
        $employee->employee_photo = $this->employee_photo;
        $employee->employee_birth_place = $this->employee_birth_place;
        $employee->employee_birth_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->employee_birth_date);
        $employee->employee_gender = $this->employee_gender;
        $employee->employee_personal_status = $this->employee_personal_status;
        $employee->employee_address = $this->employee_address;
        $employee->employee_address_alt = $this->employee_address_alt;
        $employee->employee_address_rt = $this->employee_address_rt;
        $employee->employee_address_rw = $this->employee_address_rw;
        $employee->employee_address_kel = $this->employee_address_kel;
        $employee->employee_address_kec = $this->employee_address_kec;
        $employee->employee_address_zip = $this->employee_address_zip;
        $employee->employee_faskes = $this->employee_faskes;
        $employee->employee_faskes_dr = $this->employee_faskes_dr;
        $employee->employee_faskes_dr_name = $this->employee_faskes_dr_name;
        $employee->employee_mobile = $this->employee_mobile;
        $employee->employee_email = $this->employee_email;
        $employee->employee_npp = $this->employee_npp;
        $employee->employee_position = $this->employee_position?$this->employee_position:"-";
        $employee->employee_status = $this->employee_status;
        $employee->employee_care_class = $this->employee_care_class;
        $employee->employee_active_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->employee_active_date);
        $employee->employee_salary = $this->employee_salary;
        $employee->employee_nationality = $this->employee_nationality;
        $employee->employee_assurance_polis_no = $this->employee_assurance_polis_no;
        $employee->employee_assurance_name = $this->employee_assurance_name;
        $employee->employee_npwp = $this->employee_npwp;
        $employee->employee_passport_no = $this->employee_passport_no;
        $employee->save();
        return true;
    }
    
    
    public function getBpjsEmployeeData($params){
        $query = BpjsEmployee::find()
        ->select(['*'])
        ->from('bpjs_employee')
        ->orderBy('employee_id,employee_relation');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => 25
            ]    
        ]);

        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
        
        
        $query->andFilterWhere(['like', 'employee_identity_id',  $this->employee_identity_id]);
        $query->andFilterWhere(['like', 'employee_name',  $this->employee_name]);
        $query->andFilterWhere(['like', 'employee_position', $this->employee_position]);
        return $dataProvider;
    }
}    