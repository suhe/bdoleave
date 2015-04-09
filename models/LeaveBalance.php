<?php
namespace app\models;
use yii;
 
class LeaveBalance extends \yii\db\ActiveRecord {
    
    public $leave_date_from;
    public $leave_date_to;
    public $leave_balance_type;
    public $balance;
    
    
    public static function tableName(){
        return 'leave_balance';
    }
    
    public function rules(){
        return [
            [['leave_balance_date'],'required','on'=>['save']],
            [['leave_balance_description'],'required','on'=>['save']],
            [['leave_balance_total'],'required','on'=>['save']],
            [['leave_balance_total'],'number','on'=>['save']],
            [['leave_date_from'],'safe','on'=>['search']],
            [['leave_date_to'],'safe','on'=>['search']],
        ];
    }
    
    public function attributeLabels(){
        return [
            'leave_balance_date' => Yii::t('app','date'),
            'leave_balance_total' => Yii::t('app','total'),
        ];
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
                  "leave_balance_description","leave_balance_total"])
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
            $model->leave_balance_total = $this->leave_balance_total;
            $model->insert();
            
            //update balance for employee
            $update = \app\models\Employee::updateAll(['EmployeeLeaveTotal' => $this->leave_balance_total,'EmployeeLeaveDate' => preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_balance_date)],['employee_id'=>$employee_id]);            
            return true;
        }
        return false;
    }
    
    public function getLeaveBalanceByEmployee($employee_id,$params){
        $sqlquery = "
            SELECT
            DATE_FORMAT(leave_balance_date,'%d/%m/%Y') as leave_balance_date,leave_balance_description,leave_balance_total,@saldo:=@saldo+leave_balance_total AS balance
            FROM
            (SELECT leave_balance_date leave_balance_date,leave_balance_description,+leave_balance_total
            FROM leave_balance
            WHERE employee_id = $employee_id
            UNION ALL
            SELECT leave_date leave_balance_date,leave_description,-leave_total
            FROM `leaves` 
            WHERE employee_id = $employee_id
            ) balance 
            JOIN (SELECT @saldo:=0) a
            WHERE balance.leave_balance_date<>''
            
        ";
        
        if($this->leave_date_from) $sqlquery.="AND balance.leave_balance_date >= '".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_from)."' ";
        if($this->leave_date_to)   $sqlquery.="AND balance.leave_balance_date <= '".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_to)."' ";
        
        $sqlquery.= " ORDER BY balance.leave_balance_date ";
        
        return LeaveBalance::findBySql($sqlquery)
        ->all();
    }
    
    public function getLeaveBalanceDataByEmployee($employee_id,$params){
        $sqlquery = "
            SELECT
            DATE_FORMAT(leave_balance_date,'%d/%m/%Y') as leave_balance_date,leave_balance_description,leave_balance_total,@saldo:=@saldo+leave_balance_total AS balance
            FROM
            (SELECT leave_balance_date leave_balance_date,leave_balance_description,+leave_balance_total
            FROM leave_balance
            WHERE employee_id = $employee_id
            UNION ALL
            SELECT leave_date leave_balance_date,leave_description,-leave_total
            FROM `leaves` 
            WHERE employee_id = $employee_id and leave_approved = 1
            ) balance 
            JOIN (SELECT @saldo:=0) a
            WHERE balance.leave_balance_date<>''
            
        ";
        
        if($this->leave_date_from) $sqlquery.="AND balance.leave_balance_date >= '".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_from)."' ";
        if($this->leave_date_to)   $sqlquery.="AND balance.leave_balance_date <= '".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_to)."' ";
        
        $sqlquery.= " ORDER BY balance.leave_balance_date ";
        
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

}