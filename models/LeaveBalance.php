<?php
namespace app\models;
use yii;
 
class LeaveBalance extends \yii\db\ActiveRecord {
    public $leave_date;
    public $leave_date_from;
    public $leave_date_to;
    public $leave_balance_type;
    public $leave_description;
    public $leave_source_string;
    public $leave_status_string;
    public $leave_type_string;
    public $leave_total;
    public $leave_saldo;
    public $balance;
    public $date;
    public $leave_type; //
    public $total;
    public $source;
    
    
    public static function tableName(){
        return 'leave_balance';
    }
    
    public function rules(){
        return [
        	[['employee_id'],'required','on'=>['save']],
            [['leave_balance_date'],'required','on'=>['save']],
            [['leave_balance_description'],'required','on'=>['save']],
            //[['leave_balance_stype'],'required','on'=>['save']],
            [['leave_balance_total'],'required','on'=>['save']],
            [['leave_balance_total'],'number','on'=>['save']],
            [['leave_date_from'],'safe','on'=>['search']],
            [['leave_date_to'],'safe','on'=>['search']],
        ];
    }
    
    public function attributeLabels(){
        return [
            'leave_balance_date' => Yii::t('app','date'),
            'leave_balance_description' => Yii::t('app','description'),
            'leave_balance_stype' => Yii::t('app','type'),
            'leave_balance_type' => Yii::t('app','type'),
            'leave_balance_total' => Yii::t('app','total'),
        ];
    }
    
    public function getSaveBalanceRequest() {
    	if($this->validate()) {
    		$model = new LeaveBalance();
    		$model->employee_id = $this->employee_id;
    		$model->leave_balance_description = $this->leave_balance_description;
    		$model->leave_balance_date = $this->leave_balance_date;
    		$model->leave_balance_saldo = $this->leave_balance_saldo;
    		$model->insert();
    		return true;
    	}
    	return false;
    }
    
    public function getEmployeeLeaveBalance($employee_id){
        $date_now = date('Y-m-d');
        $employee = \app\models\Employee::findOne($employee_id);
        if(!$employee->EmployeeHireDate) return false;
        $range = strtotime($date_now) -  strtotime($employee->EmployeeHireDate);
        $days = $range/(60*60*24);
        if($days>364){
            $leave_date =  date('Y').'-'.substr($employee->EmployeeHireDate,5,5); //2014-01-02
            $range = strtotime($date_now) -  strtotime($leave_date);
            //check range
            if($range<0){
                $leave_date = (date('Y')-1).'-'.substr($employee->EmployeeHireDate,5,5); //2014-01-02
            }
            
            //update or insert
            $balance  = LeaveBalance::find()
            ->where(['employee_id' =>$employee_id])
            ->one();
            
            if($balance){
                $model = new LeaveBalance();
                $model = $model->findOne($balance->leave_balance_id);
                $model->leave_balance_date = $leave_date;
                $model->update();    
            } else {
                $model = new LeaveBalance();
                $model->employee_id = $employee_id;
                $model->leave_balance_date =  $leave_date;
                $model->leave_balance_total = 12;
                $model->insert();  
            }
        }
        else {
            return false;
        }
        return true;
    }
    
    public function getLeaveBalanceData($employee_id){
        $query = LeaveBalance::find()
        ->select(["leave_balance_id","DATE_FORMAT(leave_balance_date,'%d/%m/%Y') as leave_balance_date",
                  "leave_balance_description","leave_balance_total","IF(leave_balance_stype=0,'Saldo Awal','Tambahan') as leave_balance_type"])
        ->from('leave_balance lb')
        ->where(['lb.employee_id' => $employee_id]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        return $dataProvider;
    
    }
    
    public function getSaveBalance($employee_id){
        if($this->validate()){
            $model = new LeaveBalance();
            $model->employee_id = $employee_id;
            $model->leave_balance_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_balance_date);
            $model->leave_balance_description = $this->leave_balance_description;
            $model->leave_balance_stype = $this->leave_balance_stype;
            $model->leave_balance_total = $this->leave_balance_total;
            $model->insert();
            
            
            /** Perhitungan Update Cuti **/
            //total cuti
            $sumTotal = \app\models\LeaveBalance::sumLastBalanceByEmployee($employee_id);
            if(count($sumTotal)>0)
                $xtotals = $sumTotal->total;
            else 
                $xtotals = 0;
            
            //total saldo cuti
            $sumUse = \app\models\Leaves::sumLastLeaveByEmployee($employee_id);
            if(count($sumUse->total)>0)
                $xuse = $sumUse->total;
            else 
                $xuse = 0;
                
            //update employee
            $update = \app\models\Employee::updateAll(['EmployeeLeaveTotal' => $xtotals,'EmployeeLeaveUse' => $xuse],['employee_id'=>$employee_id]);
            
            //update tanggal
            $lastDate = \app\models\LeaveBalance::lastDateSadloBalanceByEmployee($employee_id);
            if($lastDate)
                 $updateDate = \app\models\Employee::updateAll(['EmployeeLeaveDate' => $lastDate->date],['employee_id'=>$employee_id]);
            /** Perhitungan Update Cuti **/
            
            return true;
        }
        return false;
    }
    
    public function getLeaveBalanceByEmployee($employee_id,$params='')
    {
        $sqlquery = "
            SELECT DATE_FORMAT(Balanced.leave_balance_date,'%d/%m/%Y') as leave_balance_date,
            Balanced.leave_balance_description,Balanced.leave_balance_total,Balanced.source,Balanced.balance
            FROM
            (
                SELECT
                leave_balance_date as leave_balance_date,leave_balance_description,leave_balance_total,source,@saldo:=@saldo + leave_balance_total AS balance
                FROM
                (SELECT leave_balance_date leave_balance_date,leave_balance_stype,leave_balance_description,+leave_balance_total,'Leaves' as source
                FROM leave_balance
                WHERE employee_id = $employee_id
                UNION ALL
                SELECT leave_date leave_balance_date,'2' as leave_balance_stype,CONCAT(leave_description,' (',leave_range,')') leave_description,-leave_total,IF(leave_source=1,'Timesheet','Leaves') as source
                FROM `leaves` 
                WHERE employee_id = $employee_id and leave_approved = 1
                ) balance 
                JOIN (SELECT @saldo := 0) a
                ORDER BY balance.leave_balance_date,balance.leave_balance_stype DESC
            ) AS Balanced
            WHERE Balanced.leave_balance_date <> ''
        ";
        
        if(\Yii::$app->session->get('leave_date_from')) $sqlquery.="AND Balanced.leave_balance_date >= '".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',\Yii::$app->session->get('leave_date_from'))."' ";
        if(\Yii::$app->session->get('leave_date_to'))   $sqlquery.="AND Balanced.leave_balance_date <= '".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',\Yii::$app->session->get('leave_date_to'))."' ";
         
        return LeaveBalance::findBySql($sqlquery)
        ->all();
    }
    
    public function getLeaveBalanceDataByEmployee($employee_id,$params=''){
        $sqlquery = "
            SELECT DATE_FORMAT(Balanced.leave_balance_date,'%d/%m/%Y') as leave_balance_date,
            Balanced.leave_balance_description,Balanced.leave_balance_total,Balanced.source,Balanced.balance
            FROM
            (
                SELECT
                leave_balance_date as leave_balance_date,leave_balance_description,leave_balance_total,source,@saldo:=@saldo+leave_balance_total AS balance
                FROM
                (SELECT leave_balance_date leave_balance_date,leave_balance_stype,leave_balance_description,+leave_balance_total,'Leaves' as source
                FROM leave_balance
                WHERE employee_id = $employee_id
                UNION ALL
                SELECT leave_date leave_balance_date,'2' as leave_balance_stype,CONCAT(leave_description,' (',leave_range,')') leave_description,-leave_total,IF(leave_source=1,'Timesheet','Leaves') as source
                FROM `leaves` 
                WHERE employee_id = $employee_id and leave_approved = 1
                ) balance 
                JOIN (SELECT @saldo:=0) a
                ORDER BY balance.leave_balance_date,balance.leave_balance_stype DESC
            ) AS Balanced
            WHERE Balanced.leave_balance_date <> ''
        ";
        
        if($this->leave_date_from) $sqlquery.="AND Balanced.leave_balance_date >= '".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_from)."' ";
        if($this->leave_date_to)   $sqlquery.="AND Balanced.leave_balance_date <= '".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_to)."' ";
        
        //$sqlquery.= " ORDER BY balance.leave_balance_date,balance.leave_balance_stype DESC ";
        
        $query = LeaveBalance::findBySql($sqlquery);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'totalCount' => count($query),
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        
        return $dataProvider;
    }
    
    public static function sumLastLeaveBalance($employee_id,$date){
        $sqlquery = "
            SELECT leave_balance_date,SUM(leave_balance_total) AS total
            FROM
            (SELECT leave_balance_date,+leave_balance_total
            FROM leave_balance
            WHERE employee_id = $employee_id
            UNION ALL
            SELECT leave_date as leave_balance_date,-leave_total
            FROM `leaves` 
            WHERE employee_id = $employee_id and leave_approved = 1
            ) balance 
            WHERE balance.leave_balance_date < '".$date."'
            ORDER BY balance.leave_balance_date    
        ";
        return LeaveBalance::findBySql($sqlquery)->one();
    }
	
    public static function sumLastLeaveBalanceDateRange($employee_id,$date_from,$date_to){
        $sqlquery = "
            SELECT leave_balance_date,SUM(leave_balance_total) AS total
            FROM
            (SELECT leave_balance_date,+leave_balance_total
            FROM leave_balance
            WHERE employee_id = $employee_id
            UNION ALL
            SELECT leave_date as leave_balance_date,-leave_total
            FROM `leaves` 
            WHERE employee_id = $employee_id and leave_approved = 1
            ) balance 
            WHERE balance.leave_balance_date >= '".$date_from."'
			and balance.leave_balance_date < '".$date_to."'
            ORDER BY balance.leave_balance_date    
        ";
        return LeaveBalance::findBySql($sqlquery)->one();
    }
    
    public static function sumLastBalanceByEmployee($employee_id){
        $sqlquery = "
            SELECT SUM(leave_balance_total) AS total
            FROM leave_balance
            WHERE employee_id = ".$employee_id."
        ";
        return LeaveBalance::findBySql($sqlquery)->one();
    }
    
    public static function lastDateSadloBalanceByEmployee($employee_id){
        $sqlquery = "
            SELECT leave_balance_date as date
            FROM leave_balance
            WHERE employee_id = ".$employee_id."
            AND leave_balance_stype = 0
            ORDER BY leave_balance_date DESC
        ";
        return LeaveBalance::findBySql($sqlquery)->one();
    }

}