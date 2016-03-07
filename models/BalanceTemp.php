<?php
namespace app\models;
use yii;
 
class BalanceTemp extends \yii\db\ActiveRecord {
	public $employee_id;
    
    public static function tableName(){
        return 'balance_tmp';
    }
    
}