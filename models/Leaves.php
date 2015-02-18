<?php
namespace app\models;
use yii;
 
class Leaves extends \yii\db\ActiveRecord {
    
    public $employeeid;
    public $employeefirstname;
    public $employee_name;
    public $status = 5;
    public $leave_over;
    public $leave_note;
    public $leave_approval;
    public $leave_pdf;
    public $department;
    public $EmployeeLeaveTotal;
    public $EmployeeLeaveUse;
    public $EmployeeLeaveOver;
    public $user1_name;
    public $user2_name;
    public $hrd_name;
    public $pic_name;
    public $balance;
   
    
    public static function tableName(){
        return 'leaves';
    }
    
    public function rules(){
        return [
            [['employee_id'],'required','on'=>['add_leave']],
            [['leave_type'],'required','on'=>['add_leave','add_myleave']],
            [['leave_date_from'],'required','on'=>['add_leave','add_myleave']],
            [['leave_date_to'],'required','on'=>['add_leave','add_myleave']],
            [['leave_description'],'required','on'=>['add_leave','add_myleave']],
            [['leave_address'],'required','on'=>['add_leave','add_myleave']],
            [['leave_over'],'safe','on'=>['add_myleave']],
            [['employee_name'],'safe','on'=>['search']],
            [['leave_status'],'safe','on'=>['search']],
            [['leave_date_from'],'safe','on'=>['search']],
            [['leave_date_to'],'safe','on'=>['search']],
            [['leave_approval'],'required','on'=>['approval']],
            [['leave_approved'],'safe','on'=>['search','add_leave','add_myleave']],
            [['leave_note'],'safe','on'=>['approval']],
        ];
    }
    
    public function attributeLabels(){
        return [
            'leave_id' => Yii::t('app','id'),
            'employee_id' => Yii::t('app','nik'),
            'employeeid' => Yii::t('app','nik'),
            'employee_name' => Yii::t('app','name'),
            'leave_date' => Yii::t('app','date of filing'),
            'leave_type' => Yii::t('app','type'),
            'leave_status' => Yii::t('app','status'), 
            'leave_description' => Yii::t('app','necessary'),
            'leave_date_from' => Yii::t('app','date from'),
            'leave_date_to' => Yii::t('app','date to'),
            'leave_range' => Yii::t('app','range'),
            'leave_total' => Yii::t('app','total'),
            'leave_address' => Yii::t('app','leave address'),
            'employeefirstname' => Yii::t('app','name'),
            'leave_over' => Yii::t('app','the amount of leave'),
            'leave_pdf' => Yii::t('app','export pdf'),
            'leave_approved' => Yii::t('app','approved'),
        ];
    }
    
    
    public static function getDropDownType(){
        $data = [
            1 => 'Cuti Bersama' ,
            2 => 'Cuti Tahunan' ,
            3 => 'Cuti Tambahan',
			4 => 'Cuti Khusus',
        ];
        return $data;
    }
    
    
    public static function getDropDownStatus($ALL=FALSE){
        $data = [
            1 => 'Completed' ,
            2 => 'Approved By Partner' ,
            3 => 'Approved By HRD',
            4 => 'Approved By Senior/Manager',
            5 => 'Request',
            12 => 'Don\'t Agree By Partner',
            13 => 'Don\'t Agree By HRD',
            14 => 'Don\'t Agree By Senior/Manager',
            15 => 'Failed',
        ];
        
        if($ALL==TRUE)
            $data[0] = Yii::t('app','all');
           
        return $data;
    }
    
    public static function getDropDownRequest($ALL=FALSE){
        $data = [
            1 => 'Completed',
            2 => 'Request to Partner' ,
            3 => 'Request to HRD',
            4 => 'Request to Manager',
            5 => 'Request to Senior',
        ];
        
        if($ALL==TRUE)
            $data[0] = Yii::t('app','all');
           
        return $data;
    }
    
    public static function getStringType($key){
        switch($key){
            case 1 : $string = 'Cuti Bersama';break;
            case 2 : $string = 'Cuti Tahunan';break;
            case 3 : $string = 'Cuti Tambahan';break;
            case 4 : $string = 'Cuti Melahirkan';break;
        }
        return $string;
    }
    
    public static function getDropDownApproval(){
        $data = [
            1 => 'Approve' ,
            3 => 'Reject' ,
        ];
        return $data;
    }
    
    public static function getDropDownApproved(){
        $data = [
            0 => 'All',
            1 => 'Approve' ,
            2 => 'Reject',
            3 => 'Process',
        ];
        return $data;
    }
    
     public static function getDropDownHRApproval(){
        $data = [
            1 => 'Approve By HRD & Partner (By Paper)',
            3 => 'Reject By HRD & Partner (By Paper)',
            4 => 'Approve Only HRD to Approval Partner (Automatic)',
            5 => 'Reject Only HRD to Approval Partner (Automatic)',
        ];
        return $data;
    }
    
    public static function getStringStatus($key){
        switch($key){
            case 1 : $string = 'Completed';break;
            case 2 : $string = 'Approved By Partner';break;
            case 3 : $string = 'Approved By HRD';break;
            case 4 : $string = 'Approved By Senior/Manager';break;
            case 5 : $string = 'Request';break;
            case 12 : $string = 'Don\'t Agree By Partner';break;
            case 13 : $string = 'Don\'t Agree By HRD';break;
            case 14 : $string = 'Don\'t Agree By Senior/Manager';break;    
            case 15 : $string = 'Failed';break;
            default : $string = Yii::t('app','uknown');break;    
        }
        return $string;
    }
    
    public static function getStringRequest($key){
        switch($key){
            case 1 : $string = 'Completed';break;
            case 2 : $string = 'Request to Partner';break;
            case 3 : $string = 'Request to HRD';break;
            case 4 : $string = 'Request to Manager';break;
            case 5 : $string = 'Request to Senior';break;
            default : $string = Yii::t('app','uknown');break;    
        }
        return $string;
    }
    
    public static function getStringApproved($key){
        switch($key){
            case 1 : $string = 'Approved';break;
            case 2 : $string = 'Reject';break;
            case 3 : $string = 'Process';break;
            default : $string = Yii::t('app','process');break;    
        }
        return $string;
    }
    
    public function getSaveRequest($status=false){
        if($this->validate()){
            if($this->employee_id){
                //set status to 0 /completed
                $this->status = 1;
				$this->leave_approved=1;
                $this->getSaveData($this->employee_id);
                $data = $this->getLeaveSingleDataByEmployee($this->employee_id);
                $request = \app\models\LeaveLog::getSaveData($data->leave_id,'Request','Request By '.Yii::$app->user->identity->EmployeeFirstName);
                $approved = \app\models\LeaveLog::getSaveData($data->leave_id,'Approved By HRD','Approved By '.Yii::$app->user->identity->EmployeeFirstName);
                $completed = \app\models\LeaveLog::getSaveData($data->leave_id,'Completed','Completed By '.Yii::$app->user->identity->EmployeeFirstName);
			}
            else {
                $employees = \app\models\Employee::getAllEmployee();
                foreach($employees as $row){
                    //set status to 0 /completed
                    $this->status = 1;
					$this->leave_approved=1;
                    $this->getSaveData($row->employee_id);
                    $data = $this->getLeaveSingleDataByEmployee($row->employee_id);
                    $request = \app\models\LeaveLog::getSaveData($data->leave_id,'Request','Request By '.Yii::$app->user->identity->EmployeeFirstName);
                    $approved = \app\models\LeaveLog::getSaveData($data->leave_id,'Approved By HRD','Approved By '.Yii::$app->user->identity->EmployeeFirstName);
                    $completed = \app\models\LeaveLog::getSaveData($data->leave_id,'Completed','Completed By '.Yii::$app->user->identity->EmployeeFirstName);
                }
            }
            return true;
        }
        
    }
    
    public function getSaveMyRequest(){
        $this->status = 5;
        $save = $this->getSaveData(Yii::$app->user->getId(),TRUE);
        if($save){
            $data = $this->getLeaveSingleDataByEmployee(Yii::$app->user->getId());
            $status = \app\models\LeaveLog::getSaveData($data->leave_id,'Request by','Request By '.Yii::$app->user->identity->EmployeeFirstName);
            $request = \app\models\LeaveLog::getSaveData($data->leave_id,'Request to',$this->getStringRequest($data->leave_request));
            return true;
        }
        return false;
    }
    
    public function getSaveData($employee_id,$status=FALSE){
        if($this->validate()){
            //calculate range date from to date to
            $leave_date_from = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_from);
            $leave_date_to = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_to);
            $leave_range = strtotime($leave_date_to) -  strtotime($leave_date_from);
            $range = ($leave_range/(60*60*24))+1;
            
            if($range<0){
                Yii::$app->session->setFlash('msg','<div class="notice bg-success marker-on-left">'.Yii::t('app/message','msg date range is not correct').'</div>');
                return false;
            }
            
            $x = 0;
            $date_char='';
            $newdate = $leave_date_from;
            for($i=1;$i<=$range;$i++){
                //$date_char.='';
                //check sunday & saturday
                if(date('N', strtotime($newdate))<6){
                    //check holiday
                    $holiday = \app\models\Holiday::find()
                    ->where(['holiday_date' => $newdate])
                    ->one();
                    if(!$holiday){
                       $x++;
                       $date_char.= Yii::$app->formatter->asDatetime($newdate,"php:d/m/Y").",";
                    }
                    
                }
                //counter
                $newdate = date('Y-m-d', strtotime('+1 days', strtotime($newdate)));
            }
            
            if($x==0){
               $x=1;
               $date_char.=  $this->leave_date_from;
            }
            
            $model = new Leaves();
            $model->employee_id = $employee_id;
			if($this->leave_approved)
				$model->leave_approved = $this->leave_approved; //process 
			else
				$model->leave_approved = 3;
            $model->leave_type = $this->leave_type;
            $model->leave_status = $this->status;
            $model->leave_date = date('Y-m-d');
            $model->leave_date_from = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_from);
            $model->leave_date_to = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_to);
            $model->leave_total = $x;
            $model->leave_range = $date_char;
            $model->leave_description = $this->leave_description;
            $model->leave_address = $this->leave_address;
            $model->leave_created_by = Yii::$app->user->getId();
            $model->leave_created_date = date('Y-m-d H:i:s');
            
            if($status==TRUE){
                $setting = \app\models\Employee::findOne($employee_id);
                
                if($setting->EmployeeLeaveSenior){
                    $model->leave_app_user1 = $setting->EmployeeLeaveSenior;
                    $model->leave_app_user1_status = 2;
                } else {
                    $model->leave_app_user1 = 0;
                    $model->leave_app_user1_status = 1;
                }
                
                if($setting->EmployeeLeaveManager){
                    $model->leave_app_user2 = $setting->EmployeeLeaveManager;
                    $model->leave_app_user2_status = 2;
                } else {
                    $model->leave_app_user2 = 0;
                    $model->leave_app_user2_status = 1;
                }
                /*
                 *  1 => 'Completed',
                    2 => 'Request to Partner' ,
                    3 => 'Request to HRD',
                    4 => 'Request to Manager',
                    5 => 'Request to Senior',
                **/
                
                if($setting->EmployeeLeavePartner)
                    $request = 2;
                if($setting->EmployeeLeaveHRD)
                    $request = 3;
                if($setting->EmployeeLeaveManager)
                    $request = 4;  
                if($setting->EmployeeLeaveSenior)
                    $request = 5;  
                
                $model->leave_request = $request;
                $model->leave_app_hrd = $setting->EmployeeLeaveHRD;
                $model->leave_app_hrd_status = 2;
                $model->leave_app_pic = $setting->EmployeeLeavePartner;
                $model->leave_app_pic_status = 2;
                
            
            } else {
                $model->leave_request = 1;
                $model->leave_app_user1 = Yii::$app->user->getId();
                $model->leave_app_user1_status = 1;
                $model->leave_app_user2 = Yii::$app->user->getId();
                $model->leave_app_user2_status = 1;
                $model->leave_app_hrd = Yii::$app->user->getId();
                $model->leave_app_hrd_status = 1;
                $model->leave_app_pic = Yii::$app->user->getId();
                $model->leave_app_pic_status = 1;
            }
		
            // insert the data 
            $model->insert();
			
			// patch leave Approved 1 
			if($this->leave_approved==1){
				//link to EmployeeLeaveUse
				$employee = \app\models\Employee::findOne($employee_id);
				if(!$employee->EmployeeLeaveUse){
					$update = \app\models\Employee::updateAll(['EmployeeLeaveUse' => 0],['employee_id'=>$employee_id]);
				}	
				//update counter for use employee if use=0
				\app\models\Employee::updateAllCounters(['EmployeeLeaveUse' => $x],['employee_id' => $employee_id]);
			}
			
            return true;
        }
    }
    
    public function getApprovalRequest($id){
        if($this->validate()){
            //find
            $query = Leaves::findOne($id);
            //update init
            $model = new Leaves();
            $model = $model->findOne($query->leave_id);
            //check if request to senior
            if($query->leave_request==5){
                $model->leave_app_user1_status = $this->leave_approval;
                $model->leave_app_user1_note = $this->leave_note;
                $model->leave_app_user1_date = date('Y-m-d H:i:s');
                if($this->leave_approval==3)
                    $approval = 14;
                else
                    $approval = 4;                
                $model->leave_status = $approval;
                $model->leave_request = ($query->leave_request-1);
                $model->update();
                //update for status
                $status = \app\models\LeaveLog::getSaveData($query->leave_id,$this->getStringStatus($approval).' : '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note);
                $request = \app\models\LeaveLog::getSaveData($query->leave_id,'Request to',$this->getStringRequest(($query->leave_request-1)));
                return true;
            }
            elseif($query->leave_request==4){
                $model->leave_app_user2_status = $this->leave_approval;
                $model->leave_app_user2_note = $this->leave_note;
                $model->leave_app_user2_date = date('Y-m-d H:i:s');
                if($this->leave_approval==3)
                    $approval = 14;
                else
                    $approval = 4;                
                $model->leave_status = $approval;
                $model->leave_request = ($query->leave_request-1);
                $model->update();
                //update for status
                $status = \app\models\LeaveLog::getSaveData($query->leave_id,$this->getStringStatus($approval).' : '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note);
                $request = \app\models\LeaveLog::getSaveData($query->leave_id,'Request to',$this->getStringRequest(($query->leave_request-1)));
                return true;
            }
            elseif($query->leave_request==2){
                $model->leave_app_pic_status = $this->leave_approval;
                $model->leave_app_pic_note = $this->leave_note;
                $model->leave_app_pic_date = date('Y-m-d H:i:s');
                if($this->leave_approval==3)
                    $approval = 12;
                else
                    $approval = 2;                
                $model->leave_status = $approval;
                $model->leave_request = ($query->leave_request-1);
                $model->update();
                //update for status
                $status = \app\models\LeaveLog::getSaveData($query->leave_id,$this->getStringStatus($approval).' : '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note);
                $request = \app\models\LeaveLog::getSaveData($query->leave_id,'Request to',$this->getStringRequest(($query->leave_request-1)));
                return true;
            }
            else{
                $model->leave_app_pic_status = $this->leave_approval;
                $model->leave_app_pic_note = $this->leave_note;
                $model->leave_app_pic_date = date('Y-m-d H:i:s');
                if($this->leave_approval==3)
                    $approval = 13;
                else
                    $approval = 3;                
                $model->leave_status = $approval;
                $model->update();
                //update for status
                $status = \app\models\LeaveLog::getSaveData($query->leave_id,$this->getStringStatus($approval).' : '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note);
                $request = \app\models\LeaveLog::getSaveData($query->leave_id,'Request to',$this->getStringRequest(($query->leave_request-1)));
                return true;
            }
            
        
        }
        return false;
    }
    
    public function getHRApprovalRequest($id){
        if($this->validate()){
            $query = Leaves::findOne($id);
            $model = new Leaves();
            $model = $model->findOne($query->leave_id);
            if($this->leave_approval==1){
                $model->leave_app_hrd_status = 1;
                $model->leave_app_hrd_note = $this->leave_note;
                $model->leave_app_hrd_date = date('Y-m-d H:i:s');
                $model->leave_app_pic_status = 1;
                $model->leave_app_pic_note = $this->leave_note;
                $model->leave_app_pic_date = date('Y-m-d H:i:s');
                $model->leave_request = 1;
                $model->leave_status = 2;
            }
            elseif($this->leave_approval==3){
                $model->leave_app_hrd_status = 3;
                $model->leave_app_hrd_note = $this->leave_note;
                $model->leave_app_hrd_date = date('Y-m-d H:i:s');
                $model->leave_app_pic_status = 3;
                $model->leave_app_pic_note = $this->leave_note;
                $model->leave_app_pic_date = date('Y-m-d H:i:s');
                $model->leave_request = 1;
                $model->leave_status = 12;
            }
            elseif($this->leave_approval==4){
                $model->leave_app_hrd_status = 1;
                $model->leave_app_hrd_note = $this->leave_note;
                $model->leave_app_hrd_date = date('Y-m-d H:i:s');
                $model->leave_request = 2;
                $model->leave_status = 3;
            }
            elseif($this->leave_approval==5){
                $model->leave_app_hrd_status = 3;
                $model->leave_app_hrd_note = $this->leave_note;
                $model->leave_app_hrd_date = date('Y-m-d H:i:s');
                $model->leave_request = 2;
                $model->leave_status = 13;
            }
            $model->leave_updated_by = Yii::$app->user->getId();
            $model->leave_updated_date = date('Y-m-d H:i:s');
            $model->update();
            
            //update for status
            if(($this->leave_approval==1) || ($this->leave_approval==3) ){
                //check approval partner & hrd
                if($this->leave_approval==1) $approval = 3;
                if($this->leave_approval==3) $approval = 13;
                $status = \app\models\LeaveLog::getSaveData($query->leave_id,$this->getStringStatus($approval).' : '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note);
                
                //approval / reject by partner
                if($this->leave_approval==1) $approval = 2;
                if($this->leave_approval==3) $approval = 12;
                //$this->status = 1; // status 
                $status = \app\models\LeaveLog::getSaveData($query->leave_id,$this->getStringStatus($approval).' : '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note);
            }
            
            if(($this->leave_approval==4) || ($this->leave_approval==5) ){
                //check approval status 
                if($this->leave_approval==4) $approval = 3;
                if($this->leave_approval==5) $approval = 13;
                
                $status = \app\models\LeaveLog::getSaveData($query->leave_id,$this->getStringStatus($approval).' : '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note);
                $request = \app\models\LeaveLog::getSaveData($query->leave_id,'Request to',$this->getStringRequest(2));
            }
            return true;
        }
        return false;
    }
    
    public function getLeaveManagement($params){
        $query = Leaves::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','e.employeefirstname','l.leave_range',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description','leave_approved',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total','l.leave_status'])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id');
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', "CONCAT(e.EmployeeFirstname,' ',e.EmployeeMiddleName,' ',EmployeeLastName)",  $this->employee_name]);
        if($this->leave_status) $query->andWhere(['leave_status'=>$this->leave_status]);
        if($this->leave_date_from) $query->andFilterWhere(['>=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_from]);
        if($this->leave_date_from) $query->andFilterWhere(['<=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_to]);
        if($this->leave_approved) $query->andWhere(['leave_approved'=>$this->leave_approved]);
        
        return $dataProvider;
    }
    
    public function getLeaveEmployee($params){
        $query = Leaves::find()
        ->select(['leave_id','DATE_FORMAT(leave_date,\'%d/%m/%Y\') leave_date','leave_description','leave_range',
                  'leave_total','leave_status','leave_request'])
        ->from('leaves')
        ->where(['employee_id' => Yii::$app->user->getId()]);
        /*->select(['leaves.leave_id','DATE_FORMAT(leaves.leave_date,\'%d/%m/%Y\') as leave_date','employee.employeeid',
                  'employee.employeefirstname','leaves.leave_range',
                  'DATE_FORMAT(leaves.leave_date_from,\'%d/%m/%Y\') as leave_date_from',
                  'leaves.leave_description','leaves.leave_request',
                  'DATE_FORMAT(leaves.leave_date_to,\'%d/%m/%Y\') as leave_date_to','leaves.leave_total','leaves.leave_status'])
        ->join('left join','employee','employee.employee_id = leaves.employee_id')
        */
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
        
        if($this->leave_status) $query->andWhere(['leave_status'=>$this->leave_status]);
        if($this->leave_date_from) $query->andFilterWhere(['>=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_from]);
        if($this->leave_date_from) $query->andFilterWhere(['<=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_to]);
        
        return $dataProvider;
    }
    
    public function getLeaveApproval($params){
        $query = Leaves::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','e.employeefirstname','l.leave_range',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total','l.leave_status'])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id')
        ->orWhere(['l.leave_app_user1' => Yii::$app->user->getId()])
        ->orWhere(['l.leave_app_user2' => Yii::$app->user->getId()])
        ->orWhere(['l.leave_app_hrd' => Yii::$app->user->getId()])
        ->orWhere(['l.leave_app_pic' => Yii::$app->user->getId()]);
        
        if(Yii::$app->user->identity->project_title=='01')
            $query->andWhere(['l.leave_request' => 2]);
        
        if(Yii::$app->user->identity->project_title=='03')
            $query->andWhere(['l.leave_request' => 4]);
        
        if(Yii::$app->user->identity->project_title=='041')
            $query->andWhere(['l.leave_request' => 5]);
     
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
    
        $query->andFilterWhere(['like', "CONCAT(e.EmployeeFirstname,' ',e.EmployeeMiddleName,' ',EmployeeLastName)",  $this->employee_name]);
        if($this->leave_status) $query->andWhere(['leave_status'=>$this->leave_status]);
        if($this->leave_date_from) $query->andFilterWhere(['>=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_from]);
        if($this->leave_date_from) $query->andFilterWhere(['<=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_to]);
        
        return $dataProvider;
    }
    
    public function getLeaveApprovalData(){
        $query = Leaves::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','e.employeefirstname','l.leave_range',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total','l.leave_status','leave_created_date'])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id')
        ->orWhere(['l.leave_app_user1' => Yii::$app->user->getId()])
        ->orWhere(['l.leave_app_user2' => Yii::$app->user->getId()])
        ->orWhere(['l.leave_app_hrd' => Yii::$app->user->getId()])
        ->orWhere(['l.leave_app_pic' => Yii::$app->user->getId()]);
        
        //approval partner
        if((Yii::$app->user->identity->project_title=='01') && (!\app\models\Employee::isHR()) )
            $query->andWhere(['l.leave_request' => 2]);
        
        //approval hrd manager/hrd staff
        if(\app\models\Employee::isHR())
            $query->andWhere(['l.leave_request' => 3]);    
        
        //approval manager
        if((Yii::$app->user->identity->project_title=='03') && (!\app\models\Employee::isHR()) )
            $query->andWhere(['l.leave_request' => 4]);
        
        //approval staff
        if((Yii::$app->user->identity->project_title=='041') && (!\app\models\Employee::isHR()) )
            $query->andWhere(['l.leave_request' => 5]);
            
        
            
        return $query->all();   
     
    }
    
    public function getLeaveHRDApproval($params){
        $query = Leaves::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','e.employeefirstname','l.leave_range',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description','leave_request',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total','l.leave_status'])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id')
        ->andWhere(['l.leave_app_hrd' => Yii::$app->user->getId()])
        ->andWhere(['l.leave_request' => 3]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', "CONCAT(e.EmployeeFirstname,' ',e.EmployeeMiddleName,' ',EmployeeLastName)",  $this->employee_name]);
        if($this->leave_date_from) $query->andFilterWhere(['>=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_from]);
        if($this->leave_date_from) $query->andFilterWhere(['<=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_to]);
        
        return $dataProvider;
    }
    
    public function getLeaveSingleData($id){
        return Leaves::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','CONCAT(e.employeefirstname,\' \',e.employeemiddlename,\' \',e.employeelastname) as employee_name',
                  'l.leave_range','l.leave_type','l.leave_address','d.department','leave_status','leave_request',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total',
                  'e.EmployeeLeaveTotal','e.EmployeeLeaveUse','(e.EmployeeLeaveTotal-e.EmployeeLeaveUse) as leave_over',
                  'leave_app_user1','leave_app_user2','leave_app_hrd','leave_app_pic','l.employee_id',
                  'CONCAT(u1.employeefirstname,\' \',u1.employeemiddlename,\' \',u1.employeelastname) as user1_name',
                  'CONCAT(u2.employeefirstname,\' \',u2.employeemiddlename,\' \',u2.employeelastname) as user2_name',
                  'CONCAT(hr.employeefirstname,\' \',hr.employeemiddlename,\' \',hr.employeelastname) as hrd_name',
                  'CONCAT(pic.employeefirstname,\' \',pic.employeemiddlename,\' \',pic.employeelastname) as pic_name',
                  'leave_app_user1_status','leave_app_user2_status','leave_app_hrd_status','leave_app_pic_status',
                  'DATE_FORMAT(l.leave_app_user1_date,\'%d/%m/%Y\')  leave_app_user1_date',
                  'DATE_FORMAT(l.leave_app_user2_date,\'%d/%m/%Y\')  leave_app_user2_date',
                  'DATE_FORMAT(l.leave_app_hrd_date,\'%d/%m/%Y\')  leave_app_hrd_date',
                  'DATE_FORMAT(l.leave_app_pic_date,\'%d/%m/%Y\')  leave_app_pic_date',
                  'leave_app_user1_note','leave_app_user2_note','leave_app_hrd_note','leave_app_pic_note'
                ])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id')
        ->join('left join','department d','d.department_id = e.department_id')
        ->join('left join','employee u1','u1.employee_id = l.leave_app_user1')
        ->join('left join','employee u2','u2.employee_id = l.leave_app_user2')
        ->join('left join','employee hr','hr.employee_id = l.leave_app_hrd')
        ->join('left join','employee pic','pic.employee_id = l.leave_app_pic')
        ->where(['l.leave_id'=>$id])
        ->one();
    }
    
    public function getLeaveSingleDataByEmployeeID($employee_id){
        return Leaves::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','CONCAT(e.employeefirstname,\' \',e.employeemiddlename,\' \',e.employeelastname) as employee_name',
                  'l.leave_range','l.leave_type','l.leave_address','d.department','leave_status',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total',
                  'e.EmployeeLeaveTotal','e.EmployeeLeaveUse','(e.EmployeeLeaveTotal-e.EmployeeLeaveUse) as leave_over',
                  'leave_app_user1','leave_app_user2','leave_app_hrd','leave_app_pic',
                  'CONCAT(u1.employeefirstname,\' \',u1.employeemiddlename,\' \',u1.employeelastname) as user1_name',
                  'CONCAT(u2.employeefirstname,\' \',u2.employeemiddlename,\' \',u2.employeelastname) as user2_name',
                  'CONCAT(hr.employeefirstname,\' \',hr.employeemiddlename,\' \',hr.employeelastname) as hrd_name',
                  'CONCAT(pic.employeefirstname,\' \',pic.employeemiddlename,\' \',pic.employeelastname) as pic_name',
                  'leave_app_user1_status','leave_app_user2_status','leave_app_hrd_status','leave_app_pic_status',
                  'DATE_FORMAT(l.leave_app_user1_date,\'%d/%m/%Y\')  leave_app_user1_date',
                  'DATE_FORMAT(l.leave_app_user2_date,\'%d/%m/%Y\')  leave_app_user2_date',
                  'DATE_FORMAT(l.leave_app_hrd_date,\'%d/%m/%Y\')  leave_app_hrd_date',
                  'DATE_FORMAT(l.leave_app_pic_date,\'%d/%m/%Y\')  leave_app_pic_date',
                  'leave_app_user1_note','leave_app_user2_note','leave_app_hrd_note','leave_app_pic_note'
                ])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id')
        ->join('left join','department d','d.department_id = e.department_id')
        ->join('left join','employee u1','u1.employee_id = l.leave_app_user1')
        ->join('left join','employee u2','u2.employee_id = l.leave_app_user2')
        ->join('left join','employee hr','hr.employee_id = l.leave_app_hrd')
        ->join('left join','employee pic','pic.employee_id = l.leave_app_pic')
        ->where(['l.employee_id'=>$employee_id])
        ->orderBy('l.leave_id DESC')
        ->one();
    }
    
    public function getLeavDataByEmployee($employee_id,$params){
        $query = Leaves::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','e.employeefirstname','l.leave_range',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total','l.leave_status'])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id')
        ->where(['e.employee_id'=>$employee_id]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
        
        if($this->leave_date_from) $query->andFilterWhere(['>=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_from]);
        if($this->leave_date_from) $query->andFilterWhere(['<=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_to]);
        if($this->leave_status) $query->andWhere(['leave_status'=>$this->leave_status]);
        
        return $dataProvider;
    }
    
    public function getLeaveSingleDataByEmployee($employee_id){
        return Leaves::find()
        ->select(['l.leave_id','l.leave_date','e.employeeid','CONCAT(e.employeefirstname,\' \',e.employeemiddlename,\' \',e.employeelastname) as employee_name',
                  'l.leave_range','l.leave_type','l.leave_address','leave_request',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total'])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id')
        ->where(['l.employee_id'=>$employee_id])
        ->orderBy('l.leave_id DESC')
        ->one();
    }
}    