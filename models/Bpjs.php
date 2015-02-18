<?php
namespace app\models;
use yii;
use yii\db\ActiveRecord;

class Bpjs extends ActiveRecord {

    public static function tableName(){
        return 'bpjs';
    }
    
    public function rules(){
        return [
          //required for all
          [['bpjs_type','bpjs_company','bpjs_vc','bpjs_bank','bpjs_registration_date','bpjs_pks','bpjs_pks_code',
            'bpjs_expired_date','bpjs_dependent_code','bpjs_kc_code','bpjs_dati2'],
            'required'],
          //safe
          [['bpjs_id','bpjs_year','bpjs_created_date','bpjs_updated_date'],'safe']
        ];
    }
    
    public function attributeLabels(){
        return [
            'bpjs_type' => Yii::t('app','bpjs type'),
            'bpjs_company' => Yii::t('app','company'),
            'bpjs_vc' => Yii::t('app','bpjs vc'),
            'bpjs_bank' => Yii::t('app','bank'),
            'bpjs_registration_date' => Yii::t('app','registration date'),
            'bpjs_pks' => Yii::t('app','pks no'),
            'bpjs_pks_code' => Yii::t('app','pks code'),
            'bpjs_expired_date' => Yii::t('app','expired date'),
            'bpjs_dependent_code' => Yii::t('app','dependent code'),
            'bpjs_kc_code' => Yii::t('app','kc code'),
            'bpjs_dati2' => Yii::t('app','dati2 code'),
        ];
    }
    
    public function getBpjsType(){
        return $data = [
            'A1' => 'Pegawai BUMN',
            'A2' => 'Pegawai BUMD',
            'A3' => 'Pegawai Swassta',
            'B1' => 'Pensiunan BUMN',
            'B2' => 'Pensiunan BUMD',
            'B3' => 'Pensiunan Swasta',
        ];
    }
    
    public function getBpjsBank(){
        return $data = [
            '1' => 'Bank Mandiri',
            '2' => 'Bank BNI',
            '3' => 'Bank BRI',
        ];
    }
    
    public function getBpjsDependent(){
        return $data = [
            '1' => 'Propinsi',
            '2' => 'Pemda',
            '3' => 'Badan/Instansi/Perusahaan',
        ];
    }
    
    public function getUpdate(){
        $bpjs = new Bpjs();
        $find = $bpjs->findOne(1);
        if($find){
            $bpjs = $find;
            $bpjs->bpjs_updated_date = date('Y-m-d H:i:s');
        }
        else {
            $bpjs->bpjs_id = 1;
            $bpjs->bpjs_created_date = date('Y-m-d H:i:s');
        }
        $bpjs->bpjs_year = '2014';
        $bpjs->bpjs_type = $this->bpjs_type;
        $bpjs->bpjs_company = $this->bpjs_company;
        $bpjs->bpjs_vc = $this->bpjs_vc;
        $bpjs->bpjs_bank = $this->bpjs_bank;
        $bpjs->bpjs_registration_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->bpjs_registration_date);
        $bpjs->bpjs_pks = $this->bpjs_pks;
        $bpjs->bpjs_pks_code = $this->bpjs_pks_code;
        $bpjs->bpjs_expired_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->bpjs_expired_date);
        $bpjs->bpjs_dependent_code = $this->bpjs_dependent_code;
        $bpjs->bpjs_kc_code = $this->bpjs_kc_code;
        $bpjs->bpjs_dati2 = $this->bpjs_dati2;
        $bpjs->save();
        return true;
    }    
    
}