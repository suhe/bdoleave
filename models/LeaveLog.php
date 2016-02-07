<?php
namespace app\models;
use yii;
use app\models\Leaves;
 
class LeaveLog extends \yii\db\ActiveRecord {
	
	/** Variabel
	 * 
	 * @return variabel
	 */
	public static $request = 5;
	public $leave_log_status_string = "";

    public static function tableName(){
        return 'leave_log';
    }
    
    /**
     * Log Data Provider
     * @param unknown $id
     * @return \yii\data\ActiveDataProvider
     */
    public static function getLogLeaveDataProvider($id){
        $query = LeaveLog::find()
        ->select(["*","(CASE WHEN leave_log_status =".self::$request." THEN '".Yii::t('app','request')."'
   		WHEN leave_log_status = ".Leaves::$approve_manager." THEN '".Yii::t('app','approved by manager')."'
    	WHEN leave_log_status = ".Leaves::$inapprove_manager." THEN '".Yii::t('app','inapproved by manager')."'
    	WHEN leave_log_status = ".Leaves::$approve_hrd." THEN '".Yii::t('app','approved by hrd')."'
    	WHEN leave_log_status = ".Leaves::$inapprove_hrd." THEN '".Yii::t('app','inapproved by hrd')."'
    	WHEN leave_log_status = ".Leaves::$approve_partner." THEN '".Yii::t('app','approved by partner')."'
    	WHEN leave_log_status = ".Leaves::$inapprove_partner." THEN '".Yii::t('app','inapproved by partner')."'	  		
        END) as leave_log_status_string"])
        ->where(['leave_id' => $id]);
        
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
    	$model->leave_log_status = $data['status'];
    	$model->leave_log_title = $data['title'];
    	$model->leave_log_desc = $data['description'];
    	$model->leave_log_approval_id = $data['approval'];
    	$model->leave_log_approval_name = $data['approval_name'];
        if($model->insert()) 
        	return true;
        return false;
    }
    
}