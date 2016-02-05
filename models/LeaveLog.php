<?php
namespace app\models;
use yii;
 
class LeaveLog extends \yii\db\ActiveRecord {

    public static function tableName(){
        return 'leave_log';
    }
    
    public static function getLeaveLogData($id){
        $query = LeaveLog::find()
        ->where(['leave_id' =>$id]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        return $dataProvider;
    }
    
    public static function getSaveData($id,$title,$desc){
        $model = new LeaveLog();
        $model->leave_id  = $id;
        $model->leave_log_date  = date('Y-m-d H:i:s');
        $model->leave_log_title = $title;
        $model->leave_log_desc = $desc;
        $model->insert();
        return true;
    }
    
    /** 
     * Set to log data from leave
     * @param array $data
     */
    public static function set($data = array()) {
    	$model = new LeaveLog();
    	$model->leave_id = $data['id'];
    	$model->leave_log_date = date('Y-m-d H:i:s');
    	$model->leave_status = $data['status'];
    	$model->leave_log_title = $data['title'];
    	$model->leave_employee_id_approval = $data['approval'];
    	$model->leave_employee_name_approval = $data['approval_name'];
        if($model->insert()) 
        	return true;
        return false;
    }
    
}