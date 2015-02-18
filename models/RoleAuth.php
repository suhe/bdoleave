<?php
namespace backend\models;
use yii\db\ActiveRecord;

class RoleAuth extends ActiveRecord {
    
    public static function tableName(){
        return 'role_auth';
    }
    
     public function rules(){
        return [
            [['role_id'],'integer'],
            [['module_name'],'string'],
        ];
    }
    
    public function getRole(){
        return $this->hasOne(Role::className(),['role_id'=>'role_id']);
    }
    
    public function getAuthAccess(){
        RoleAuth::find(['role_id' => Yii::$app->user->identity->role_id]);
    }
    
    public static function getArrayModule($id){
        $data = [];
        $query = RoleAuth::find()->select(['module_name'])->where(['role_id' => $id])->all();
        if($query){
            foreach($query as $row){
                $data[] = $row['module_name'];
            }
        }
        return $data;
    }
}