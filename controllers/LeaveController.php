<?php
namespace app\controllers;
use yii;


class LeaveController extends \yii\web\Controller {
    public $class='Leave';
    
    public function actions(){
        if(Yii::$app->user->isGuest){
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg you must login'));
            return $this->redirect(['site/login']);
        }
    }
    
    public function actionIndex(){
        $model = new \app\models\Leaves(['scenario' => 'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        //default to date if empty
        return $this->render('leave_index',[
            'model' => $model,
            'dataProvider' => $model->getLeaveEmployee(Yii::$app->request->queryParams)
        ]); 
    }
    
    public function actionForm(){
        /** Check Approval **/
        $approval = \app\models\Employee::findOne(Yii::$app->user->getId());
        if((!$approval->EmployeeLeaveHRD) && (!$approval->EmployeeLeavePartner)){
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg please set the default approval'));
            return $this->redirect(['administration/approval'],301);
        }
        //CHECK EMAIL
        $users=[];
        $email = \app\models\Employee::getEmployeeEmailById(Yii::$app->user->getId());
        if(!$email){
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg please fill email'));
            return $this->redirect(['administration/general'],301);
        }
        
        $model = new \app\models\Leaves(['scenario' => 'add_myleave']);
        if($model->load(Yii::$app->request->post()) && $model->getSaveMyRequest()){
            /** 
             * Send Email
             */
            if(Yii::$app->params['send_email'] == true) { // setting for email is send true for the config params
                $users[] = $email; //register email for self
                $approval = Yii::$app->user->identity->EmployeeLeaveSenior; // check if senior 
                
                if(!$approval)
                    $approval = Yii::$app->user->identity->EmployeeLeaveManager;
                
                if(!$approval)
                    $approval = Yii::$app->user->identity->EmployeeLeaveHRD;
                
                if(!$approval)
                    $approval = Yii::$app->user->identity->EmployeeLeavePartner;
                   
                $email = \app\models\Employee::getEmployeeEmailById($approval);   
                $users[] = $email;
                $mail = [];  //create mail
                foreach ($users as $user) {
                    $mail[] = Yii::$app->mailer->compose('leave_form',['data' => $model->getLeaveSingleDataByEmployeeID(Yii::$app->user->getId())]) 
                    ->setFrom(Yii::$app->params['mail_user'])
                    ->setTo($user)
                    ->setSubject(Yii::t('app/message','msg create a new request form'));
                }
                Yii::$app->mailer->sendMultiple($mail);
            }
            /** Send Email End **/
            
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg request has been created'));
            return $this->redirect(['leave/index'],301);
        }
        
        $employee = \app\models\Employee::findOne(Yii::$app->user->getId());
        $model->leave_over = ($employee->EmployeeLeaveTotal-$employee->EmployeeLeaveUse).' '.Yii::t('app','days');
        return $this->render('leave_myform',[
            'title' => Yii::t('app','leave form'),
            'model' => $model,
            'dropDownEmployee'=> \app\models\Employee::getEmployeeDropdownList(),
        ]);    
    }
    
    public function actionMybalance(){
        $id = Yii::$app->user->getId();
        $model = new \app\models\LeaveBalance(['scenario' => 'search']);
        $query = \app\models\Employee::findOne($id);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('leave_balance',[
            'title' => Yii::t('app','end balance'),
            'model' => $model,
            'query' => $query,
            'dataProvider' => $model->getLeaveBalanceDataByEmployee($id,Yii::$app->request->queryParams),
        ]); 
    }
    
    public function actionMybalance_export(){
        $export = \app\models\Document::getPdfLeaveUser(Yii::$app->user->getId(),Yii::$app->request->queryParams);
    }
    
    public function actionApproval(){
        $model = new \app\models\Leaves(['scenario' => 'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('leave_approval',[
            'model' => $model,
            'dataProvider' => $model->getLeaveApproval(Yii::$app->request->queryParams)
        ]); 
    }
    
    public function actionApprovallist(){
        $model = new \app\models\Leaves(['scenario' => 'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('leave_approval_list',[
            'model' => $model,
            'dataProvider' => $model->getLeaveApprovalList(Yii::$app->request->queryParams)
        ]); 
    }
    
    public function actionApprovalform($id){
        $model = new \app\models\Leaves(['scenario'=>'approval']);
        $query = $model->getLeaveSingleData($id);
        
        if($query->leave_request == 3)  //redirect if request to hrd
            return $this->redirect(['leave/hrdapprovalform','id'=>$id],301);
        
        if($model->load(Yii::$app->request->post()) && $model->getApprovalRequest($id)){
            /** 
             * Send Email
             */
            if(Yii::$app->params['send_email'] == true) { // setting for email is send true for the config params
                //$model->refresh();
                $query = $model->getLeaveSingleData($id);
                //CHECK EMAIL
                $users=[];
                $approval = $query->employee_id;
                $email = \app\models\Employee::getEmployeeEmailById($approval);   
                $users[] = $email;
                //to manager
                if($query->leave_request==4){
                    $email = \app\models\Employee::getEmployeeEmailById($query->leave_app_user1);
                    $users[] = isset($email)?$email:'';
                    $email = \app\models\Employee::getEmployeeEmailById($query->leave_app_user2);
                    $users[] = isset($email)?$email:'';
                    $email = \app\models\Employee::getEmployeeEmailById($query->leave_app_hrd);
                    $users[] = isset($email)?$email:'';
                }
                //to hrd
                elseif($query->leave_request==3){
                    $email = \app\models\Employee::getEmployeeEmailById($query->leave_app_hrd);
                    $users[] = isset($email)?$email:'';
                    
                    $email = \app\models\Employee::getEmployeeEmailById($query->leave_app_user1);
                    $users[] = isset($email)?$email:'';
                    
                    $email = \app\models\Employee::getEmployeeEmailById($query->leave_app_user2);
                    $users[] = isset($email)?$email:'';
                }
                // else if not one choice
                else {
                    $email = \app\models\Employee::getEmployeeEmailById($query->leave_app_hrd);
                    $users[] = isset($email)?$email:'';
                }
            
                $mail = [];  //create mail
                foreach ($users as $user) {
                    $mail[] = Yii::$app->mailer->compose('leave_form_approval',['data' => $query]) 
                    ->setFrom(Yii::$app->params['mail_user'])
                    ->setTo($user)
                    ->setSubject(Yii::t('app/message','msg continue approval request form'));
                }
                Yii::$app->mailer->sendMultiple($mail);
            }
            
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg approval has been approved'));
            return $this->redirect(['leave/approval'],301);
        } 
        return $this->render('leave_approval_form',[
            'model' => $query,
            'dataViewProvider' => \app\models\LeaveLog::getLeaveLogData($id)
        ]); 
    }
    
    
    public function actionApprovalview($id){
        $model = new \app\models\Leaves(['scenario'=>'approval']);
        $query = $model->getLeaveSingleData($id);

        return $this->render('leave_approval_view',[
            'model' => $query,
            'dataViewProvider' => \app\models\LeaveLog::getLeaveLogData($id)
        ]); 
    }
    
    public function actionHrdapproval(){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
            
        $model = new \app\models\Leaves(['scenario' => 'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('leave_hrdapproval',[
            'model' => $model,
            'dataProvider' => $model->getLeaveHRDApproval(Yii::$app->request->queryParams)
        ]); 
    }
    
    public function actionHrdapprovalform($id){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        $model = new \app\models\Leaves(['scenario'=>'approval']);
        $query = $model->getLeaveSingleData($id);
        
        if($query->leave_request == 2){
            return $this->redirect(['leave/partnerapprovalform','id'=>$id]);
        }
        
        if($model->load(Yii::$app->request->post()) && $model->getHRApprovalRequest($id)){
            /** 
             * Send Email
             */
            if(Yii::$app->params['send_email'] == true) {
                // setting for email is send true for the config params
                $query = $model->getLeaveSingleData($id);
                $users=[];
                $email = \app\models\Employee::getEmployeeEmailById($query->employee_id);   
                $users[] = $email;
                /** Send Mail */
                
                if($model->leave_approval==1){
                    $subject = Yii::t('app/message','msg result approval request form');
                    $email = \app\models\Employee::getEmployeeEmailById($query->leave_app_pic);
                    $users[] = $email?$email:'';
                    $email = \app\models\Employee::getEmployeeEmailById($query->leave_app_hrd);
                    $users[] = $email?$email:'';
                }
                else{
                    $subject = Yii::t('app/message','msg continue approval request form');
                    $email = \app\models\Employee::getEmployeeEmailById($query->leave_app_pic);
                    $users[] = $email?$email:'';
                    $email = \app\models\Employee::getEmployeeEmailById($query->leave_app_hrd);
                    $users[] = $email?$email:'';
                }
            
                $mail = [];  //create mail
                foreach ($users as $user) {
                    $mail[] = Yii::$app->mailer->compose('leave_form_approval',['data' => $query]) 
                    ->setFrom(Yii::$app->params['mail_user'])
                    ->setTo($user)
                    ->setSubject($subject);
                }
                Yii::$app->mailer->sendMultiple($mail);
            }
            /** End of Mail */
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg hrd approval has been finish'));
            return $this->redirect(['leave/hrdapproval'],301);
        }    
        return $this->render('leave_hrdapproval_form',[
            'model' => $query,
            'dataViewProvider' => \app\models\LeaveLog::getLeaveLogData($id)
        ]); 
    }
    
    public function actionPartnerapprovalform($id){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        $model = new \app\models\Leaves(['scenario'=>'approval']);
        $query = $model->getLeaveSingleData($id);
        
        if($query->leave_request!=2){
            return $this->redirect(['leave/hrdapproval','id'=>$id]);
        }
        
        if($model->load(Yii::$app->request->post()) && $model->getPartnerApprovalRequest($id)){
            //send email
            if(Yii::$app->params['send_email'] == true) {
                $query = $model->getLeaveSingleData($id);
                 
                //to email request
                $users=[]; // array to stored user
                
                $email = \app\models\Employee::getEmployeeEmailById($query->employee_id);   
                $users[] = $email?$email:'';
                
                //to email pic
                $email = \app\models\Employee::getEmployeeEmailById($query->leave_app_pic);
                $users[] = $email?$email:'';
                //to mail hrd
                $email = \app\models\Employee::getEmployeeEmailById($query->leave_app_hrd);
                $users[] = $email?$email:'';
                
                $subject = Yii::t('app/message','msg finish requested leave form');
                $mail = [];  //create mail
                foreach ($users as $user) {
                    $mail[] = Yii::$app->mailer->compose('leave_form_approval',['data' => $query]) 
                    ->setFrom(Yii::$app->params['mail_user'])
                    ->setTo($user)
                    ->setSubject($subject);
                }
                Yii::$app->mailer->sendMultiple($mail);
                
            }
            
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg hrd approval has been finish'));
            return $this->redirect(['leave/hrdapproval'],301);
        }    
        return $this->render('leave_partnerapproval_form',[
            'model' => $query,
            'dataViewProvider' => \app\models\LeaveLog::getLeaveLogData($id)
        ]); 
    }
    
    public function actionAdd_management(){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        $email = \app\models\Employee::getEmployeeEmailById(Yii::$app->user->getId());
        if(!$email){
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg please fill email'));
            return $this->redirect(['administration/general'],301);
        }
        
        $model = new \app\models\Leaves(['scenario' => 'add_leave']);
        if($model->load(Yii::$app->request->post()) && $model->getSaveRequest()){
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg request has been completed'));
            return $this->redirect(['leave/management'],301);
        }
        $employee = new \app\models\Employee();
        return $this->render('leave_form',[
            'title' => Yii::t('app','leave form'),
            'model' => $model,
            'dropDownEmployee'=> $employee->getEmployeeDropdownList(),
        ]);    
    }
    
    public function actionManagement(){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        $model = new \app\models\Leaves(['scenario' => 'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('leave_management',[
            'model' => $model,
            'dataProvider' => $model->getLeaveManagement(Yii::$app->request->queryParams)
        ]); 
    }
    
    public function actionExportform($id){
        $export = \app\models\Document::getPdfLeaveForm($id);
    }
    
    public function actionEmployee(){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        //load refresh employee
        $this->Balance();
        
        $model = new \app\models\Employee(['scenario'=>'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('leave_employee',[
            'model' => $model,
            'dataProvider' => $model->getEmployeeLeaveData(Yii::$app->request->queryParams)
        ]); 
    }
    
    public function actionEmployee_export($id=0){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        if(!$id)
            $export = \app\models\Document::getExcelLeaveUser(Yii::$app->request->queryParams);
        else
            $export = \app\models\Document::getPdfLeaveUser($id,Yii::$app->request->queryParams);
    }
    
    public function actionEmployee_bio($id){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        $model = new \app\models\Employee(['scenario'=>'update_account']);
        if($model->load(Yii::$app->request->post()) && $model->getUpdateProfile($id) ){
            
        }
        $query = $model->findOne($id);
        $model->EmployeeHireDate = $query->EmployeeHireDate;
        $model->EmployeeLeavePartner = $query->EmployeeLeavePartner;
        $model->EmployeeLeaveHRD = $query->EmployeeLeaveHRD;
        $model->EmployeeLeaveManager = $query->EmployeeLeaveManager;
        $model->EmployeeLeaveSenior = $query->EmployeeLeaveSenior;
        return $this->render('leave_employee_bio',[
            'title' => Yii::t('app','personal'),
            'model' => $model,
            'query' => $query,
            'dropDownEmployee'=> $model->getEmployeeDropdownList(),
        ]); 
    }
    
    public function actionEmployee_balance($id){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        $employee = \app\models\Employee::findOne($id);
        //check empty hire date
        if(!$employee->EmployeeHireDate){
            Yii::$app->session->setFlash('msg','<div class="notice bg-success marker-on-left">'.Yii::t('app/message','msg hire date must be fill !').'</div>');
            return $this->redirect(['leave/employee_bio','id'=>$id],301);
        }
        
        //check balance data if hire date > 364 days
        $balanced = \app\models\LeaveBalance::find()
        ->where(['employee_id' => $id])
        ->one();
        
        //range date
        $date_now = date('Y-m-d');
        $range = strtotime($date_now) -  strtotime($employee->EmployeeHireDate);
        $days = $range/(60*60*24);
        if((!$balanced) && ($days>364)){
            Yii::$app->session->setFlash('msg','<div class="notice bg-success marker-on-left">'.Yii::t('app/message','msg leave balance must be fill').'</div>');
        }
        
        
        $model = new \app\models\LeaveBalance(['scenario'=>'update_balance']);
        //cek balance with date
        //$model->getEmployeeLeaveBalance($id);
        if($model->load(Yii::$app->request->post()) && $model->getUpdateProfile($id) ){
            
        }
        $query = \app\models\Employee::findOne($id);
        return $this->render('leave_employee_balance',[
            'title' => Yii::t('app','balance'),
            'model' => $model,
            'query' => $query,
            'dataProvider' => $model->getLeaveBalanceData($id)
        ]); 
    }
    
    public function actionEmployee_balance_add($id){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        $model = new \app\models\LeaveBalance(['scenario'=>'save']);
        if($model->load(Yii::$app->request->post()) && $model->getSaveBalance($id) ){
            Yii::$app->session->setFlash('msg','<div class="notice bg-success marker-on-left">'.Yii::t('app/message','msg leave balance must be fill').'</div>');
            return $this->redirect(['leave/employee_balance','id'=>$id],301);
        }
        $query = \app\models\Employee::findOne($id);
        $model->leave_balance_date = $query->EmployeeHireDate;
        return $this->render('leave_employee_balance_form',[
            'title' => Yii::t('app','balance'),
            'model' => $model,
            'query' => $query,
        ]); 
    }
    
    public function actionEmployee_balance_delete($id){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        $findField = \app\models\LeaveBalance::findOne($id);
        $employee_id = $findField->employee_id;
        if($findField){
            \app\models\LeaveBalance::deleteAll('leave_balance_id = :id',[':id' => $id]);
            Yii::$app->session->setFlash('msg','<div class="notice bg-success marker-on-left">'.Yii::t('app/message','msg leave balance has been deleted').'</div>');
            return $this->redirect(['leave/employee_balance','id'=>$employee_id],301);
        } else {
            return false;
        }
    }
    
    public function actionEmployee_activity($id){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        $model = new \app\models\Leaves(['scenario'=>'search']);
        $query = \app\models\Employee::findOne($id);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('leave_employee_activity',[
            'title' => Yii::t('app','activity'),
            'model' => $model,
            'query' => $query,
            'dataProvider' => $model->getLeavDataByEmployee($id,Yii::$app->request->queryParams),
        ]); 
    }
    
    public function actionEmployee_endbalance($id){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        $model = new \app\models\LeaveBalance(['scenario' => 'search']);
        $query = \app\models\Employee::findOne($id);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('leave_employee_endbalance',[
            'title' => Yii::t('app','end balance'),
            'model' => $model,
            'query' => $query,
            'dataProvider' => $model->getLeaveBalanceDataByEmployee($id,Yii::$app->request->queryParams),
        ]); 
    }
    
    public function actionDetail($id){
        $model = new \app\models\Leaves();
        return $this->render('leave_detail',[
            'model' => $model->getLeaveSingleData($id),
            'dataViewProvider' => \app\models\LeaveLog::getLeaveLogData($id),
            'id' => $id
        ]); 
    }
    
    //balanced
    public function Balance(){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        $employeeModel = new \app\models\Employee();
        $employee = $employeeModel->find()
        ->join('inner join','sys_user','sys_user.employee_id=employee.employee_id')
        ->where(['sys_user.user_active'=>1])
        ->orderBy('employee_id')
        ->all();
        
        foreach($employee as $row){
            //select balance
            $balanced = \app\models\LeaveBalance::find()
            ->select('SUM(leave_balance_total) balance')
            ->where(['employee_id'=>$row->employee_id])
            ->one();
            $balance = $balanced?$balanced->balance:0;
            
            //select to leave use
            $leave = \app\models\Leaves::find()
            ->select('SUM(leave_total) balance')
            ->where(['employee_id'=>$row->employee_id,'leave_status'=>1])
            ->one();
            $total = $leave?$leave->balance:0;
            
            $employeeModel = new \app\models\Employee();
            $employeeModel = $employeeModel->findOne($row->employee_id);
            $employeeModel->EmployeeLeaveTotal = $balance;
            $employeeModel->EmployeeLeaveUse = $total;
            $employeeModel->update();
        }
        /*$date_now = date('Y-m-d');
        $employees = \app\models\Employee::find()
        ->orderBy('employee_id')
        ->all();
        
        foreach($employees as $employee){
            if($employee->EmployeeHireDate){
                $range = strtotime($date_now) -  strtotime($employee->EmployeeHireDate);
                $days = $range/(60*60*24);
                if($days>364){
                    $leave_date =  date('Y').'-'.substr($employee->EmployeeHireDate,5,5); //2014-01-02
                    $range = strtotime($date_now) -  strtotime($leave_date);
                    if($range<0)
                        $leave_date = (date('Y')-1).'-'.substr($employee->EmployeeHireDate,5,5); //2014-01-02 
                }
                
            } else {
                $leave_date = '0000-00-00';
            }
            
            $update = \app\models\LeaveBalance::updateAll(['leave_balance_date' => $leave_date,'leave_balance_description' => 'Saldo Awal per '.$leave_date],['employee_id'=>$employee->employee_id]);
            
            //search balance    
            $balanced = \app\models\LeaveBalance::find()
            ->where(['employee_id'=>$employee->employee_id])
            ->one();
            
            if($balanced){
                $update = \app\models\Employee::updateAll(['EmployeeLeaveTotal' => $balanced->leave_balance_total,'EmployeeLeaveDate'=>$leave_date],['employee_id'=>$employee->employee_id]);
            }
        }*/
        
    }
    
    
    public function actionRequest_complete($id){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        $status  = 1;
        //check status for approved/reject
        $query = \app\models\Leaves::findOne($id);
        if($query->leave_status==2){
            $employee = \app\models\Employee::findOne($query->employee_id);
            if(!$employee->EmployeeLeaveUse){
                $update = \app\models\Employee::updateAll(['EmployeeLeaveUse' => 0],['employee_id'=>$query->employee_id]);
                $query = \app\models\Leaves::findOne($id);    
            }
            //update counter for use
            \app\models\Employee::updateAllCounters(['EmployeeLeaveUse' => $query->leave_total],['employee_id' => $query->employee_id]);
            $status_approval = 1;
            //log - leave balance
            if($query->leave_type<=2)
                $str = Yii::t('app','minus');
            else
                $str = Yii::t('app','plus');
                
            $status = \app\models\LeaveLog::getSaveData($id,Yii::t('app','leave balance'),Yii::t('app','leave balance').' '.$str.' '.$query->leave_total);
        }
        else {
            $status_approval = 2;
        }
        
        $update = \app\models\Leaves::updateAll(['leave_status' => $status,'leave_approved' => $status_approval],['leave_id'=>$id]);
        $status = \app\models\LeaveLog::getSaveData($id,\app\models\Leave::getStringStatus($status),\app\models\Leave::getStringStatus($status).' By '.Yii::$app->user->identity->EmployeeFirstName);
        // return to management
        return $this->redirect(['leave/management'],301);
    }
    
    public function actionDelete($id){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        $model = new \app\models\Leaves();
        $query = $model->findOne($id);
        if(!$query){
            Yii::$app->session->setFlash('msg','<div class="notice bg-success marker-on-left">'.Yii::t('app/message','leave can not delete').'</div>');
            return $this->redirect(['leave/management'],301);
        }
        
        if($query->leave_status==1){
            $total = $query->leave_total;
            $update = \app\models\Employee::updateAllCounters(['EmployeeLeaveUse' => -$total],['employee_id'=>$query->employee_id]);
        }
        
        $model->deleteAll('leave_id = :id', [':id' => $id]);
        Yii::$app->session->setFlash('msg','<div class="notice bg-success marker-on-left">'.Yii::t('app/message','leave has been successfuly deleted').'</div>');
        return $this->redirect(['leave/management'],301);
        
    }
    
    
}