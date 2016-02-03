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
        $tanggal = $model->leave_date_from;
        return $this->render('leave_management',[
            'model' => $model,
            'tanggal' => $model->leave_date_from,
            'dataProvider' => $model->getLeaveManagement(Yii::$app->request->queryParams)
        ]); 
    }
    
    public function actionExportform($id){
        $export = \app\models\Document::getPdfLeaveForm($id);
    }
    
    //export all leave
    public function actionExportleave()
    {
        $export = \app\models\Document::getExcelLeave();
    }
    
    public function actionEmployee(){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        //load refresh employee
        //$this->Balance();
        
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
        //$model->EmployeeLeaveSenior = $query->EmployeeLeaveSenior;
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
           \Yii::$app->session->set('leave_date_from',$model->leave_date_from);
           \Yii::$app->session->set('leave_date_to', $model->leave_date_to);
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
    
    
    
    public function actionRequest_complete($id){
        if(!\app\models\Employee::isHR()) return $this->redirect([Yii::$app->params['default_page']]);
        
        //check status for approved/reject
        $query = \app\models\Leaves::findOne($id);
        
		if($query->leave_status == 2){
            $employee = \app\models\Employee::findOne($query->employee_id);
            
			/*if(!$employee->EmployeeLeaveUse){
                $update = \app\models\Employee::updateAll(['EmployeeLeaveUse' => 0],['employee_id'=>$query->employee_id]);
                //$query  = \app\models\Leaves::findOne($id);    
            }*/
            
            //update counter for use
            \app\models\Employee::updateAllCounters(['EmployeeLeaveUse' => $query->leave_total],['employee_id' => $query->employee_id]);
            $status_approval = 1; //approved
            //log - leave balance
			if($query->leave_type<=2)
                $str = Yii::t('app','minus');
            else
                $str = Yii::t('app','plus');
            
			$status_approval1 = 1; //reject;
            $status_app = 1;
			$status  = 1;	
			$update = \app\models\Leaves::updateAll(['leave_status' => $status,'leave_approved' => $status_approval1],['leave_id'=>$query->leave_id]);			
            $logsave = \app\models\LeaveLog::getSaveData($id,Yii::t('app','leave balance'),Yii::t('app','leave balance').' '.$str.' '.$query->leave_total);
        }
        elseif($query->leave_status == 12) {
            $status_approval2 = 2; //reject;
            $status_app = 2;
			$status  = 1;
			$update = \app\models\Leaves::updateAll(['leave_status' => $status,'leave_approved' => $status_approval2],['leave_id'=>$query->leave_id]);
        }
		else {
            $status_app = 0;
			$status  = 17;
			$status_approval2 = 22; 
		}
        
        /** Integaration BDO Timesheet**/
        if($query)
        {
            $date_from = $query->leave_date_from;
            $date_to = $query->leave_date_to;
            $leave_range = strtotime($date_to) -  strtotime($date_from);
            $total = ($leave_range/(60*60*24)) + 1;
            $newdate = $date_from;
            for($i=1;$i<=$total;$i++)
            {
                //check sunday & saturday only monday - friday
                if(date('N', strtotime($newdate))<6)
                {
                    //check holiday
                    $holiday = \app\models\Holiday::find()->where(['holiday_date' => $newdate])->one();
                    //if not holiday execution this leave
                    if(!$holiday)
                    {
                        //check this date leave on leave table
                        $Leaves = \app\models\Leaves::find()
                        ->andFilterWhere(['like', "leave_range", Yii::$app->formatter->asDatetime($newdate,"php:d/m/Y")])
                        ->andWhere(['employee_id' => $query->employee_id])
                        ->one();
                        
                        if($Leaves)
                        {
                            $timesheetModel = new \app\models\Timesheet();
                            $status_app = $status_app;
                            $timesheet['date'] = $newdate;
                            $timesheet['employee_id'] = $Leaves->employee_id;
                            $EmployeeTimesheet = $timesheetModel->checkTimesheetByEmployee($timesheet);
                            
                            if($EmployeeTimesheet)
                            {
                                switch($status_app)
                                {
                                    /**
                                     * Timesheet : 1 : Waiting , 2 : Approved , 3 : Reject
                                     * Leaves : 1 : Approved , 2 : Reject , 3 : Process , 4 : Leaves Waiting
                                    */
                                    case 1 : $tstatus  = 2;break;
                                    case 2 : $tstatus  = 3;break;
                                    default : $tstatus = 0;break;
                                }
                                
                                $timesheetUpdate = $timesheetModel->UpdateAll(['timesheet_approval' => $tstatus ],['employee_id'=>$query->employee_id,'timesheetdate'=>$newdate,'hour'=> 8]);
                                $timesheetModel = new \app\models\TimesheetStatus();
                            }
                        }
                        
                        
                    }
                }
                
                //counter next date
                $newdate = date('Y-m-d', strtotime('+1 days', strtotime($newdate)));
            }
            
        }
        /** Integaration Timesheet**/
        
        $logstatus = \app\models\LeaveLog::getSaveData($query->leave_id,\app\models\Leaves::getStringStatus($status),\app\models\Leaves::getStringStatus($status).' By '.Yii::$app->user->identity->EmployeeFirstName);
        // return to management
        
        /** Perhitungan Update Cuti **/
        //total cuti
        $sumTotal = \app\models\LeaveBalance::sumLastBalanceByEmployee($query->employee_id);
        
        if(count($sumTotal)>0)
            $xtotals = $sumTotal->total;
        else 
            $xtotals = 0;
            
        //total saldo cuti
        $sumUse = \app\models\Leaves::sumLastLeaveByEmployee($query->employee_id);
            
        if(count($sumUse->total)>0)
            $xuse = $sumUse->total;
        else 
            $xuse = 0;
                
        //update employee
        $update = \app\models\Employee::updateAll(['EmployeeLeaveTotal' => $xtotals,'EmployeeLeaveUse' => $xuse],['employee_id'=>$query->employee_id]);
            
        //update tanggal
        $lastDate = \app\models\LeaveBalance::lastDateSadloBalanceByEmployee($query->employee_id);
        if($lastDate)
            $updateDate = \app\models\Employee::updateAll(['EmployeeLeaveDate' => $lastDate->date],['employee_id'=>$query->employee_id]);
        /** Perhitungan Update Cuti **/
        
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
        
        /** Integaration BDO Timesheet**/
        $date_from = $query->leave_date_from;
        $date_to = $query->leave_date_to;
        $leave_range = strtotime($date_to) -  strtotime($date_from);
        $total = ($leave_range/(60*60*24)) + 1;
        $newdate = $date_from;
        
        for($i=1;$i<=$total;$i++)
        {
            $timesheetModel = new \app\models\Timesheet();
            $timesheet['date'] = $newdate;
            $timesheet['employee_id'] = $query->employee_id;
            $EmployeeTimesheet = $timesheetModel->checkTimesheetByEmployee($timesheet);
                            
            if($EmployeeTimesheet)
            {
                $DeleteTimesheet = \app\models\Timesheet::deleteAll('timesheetid = :id', [':id' => $EmployeeTimesheet->timesheetid]);
            }
            
            //counter next date
            $newdate = date('Y-m-d', strtotime('+1 days', strtotime($newdate)));
        }
            
        /** Integaration Timesheet**/
        
        $model->deleteAll('leave_id = :id', [':id' => $id]);
        
        /** Perhitungan Update Cuti **/
        //total cuti
        $sumTotal = \app\models\LeaveBalance::sumLastBalanceByEmployee($query->employee_id);
        
        if(count($sumTotal)>0)
            $xtotals = $sumTotal->total;
        else 
            $xtotals = 0;
            
        //total saldo cuti
        $sumUse = \app\models\Leaves::sumLastLeaveByEmployee($query->employee_id);
            
        if(count($sumUse->total)>0)
            $xuse = $sumUse->total;
        else 
            $xuse = 0;
                
        //update employee
        $update = \app\models\Employee::updateAll(['EmployeeLeaveTotal' => $xtotals,'EmployeeLeaveUse' => $xuse],['employee_id'=>$query->employee_id]);
            
        //update tanggal
        $lastDate = \app\models\LeaveBalance::lastDateSadloBalanceByEmployee($query->employee_id);
        if($lastDate)
            $updateDate = \app\models\Employee::updateAll(['EmployeeLeaveDate' => $lastDate->date],['employee_id'=>$query->employee_id]);
        /** Perhitungan Update Cuti **/
        
        
        
        Yii::$app->session->setFlash('msg','<div class="notice bg-success marker-on-left">'.Yii::t('app/message','leave has been successfuly deleted').'</div>');
        return $this->redirect(['leave/management'],301);
        
    }
	
    
    public function actionRefresh(){
        $Employee = \app\models\Employee::getAllEmployee();
        foreach($Employee as $row){
            if($row->EmployeeHireDate!='0000-00-00'){
                
                /** Variable Counter **/
                $ledate = '';
                    
                $models = new \app\models\LeaveBalance();
                $balances = $models->getLeaveBalanceByEmployee($row->employee_id);
                echo $row->EmployeeID."<br/>";
                echo '===========================<br/>';
                    
                if($balances){
                    
                    foreach($balances as $v){
                        echo "<br/>";
                        echo $v->leave_balance_date." / ";
                        echo $v->leave_balance_description." / ";
                        echo $v->leave_type." / ";
                        echo $v->leave_balance_total." / ";
                        echo $v->balance." = / ";
                    }
                    
                    echo "<br/>";
                    
                }
                    
                //update automatic leave
                $hire_date = $row->EmployeeHireDate;
                $now_date =  date('Y-m-d');
                
                echo "Tanggal Masuk :".$hire_date."<br/>";
                echo "Tanggal Hari ini :".$now_date."<br/>";
				
                $range1 = \app\components\Common::dateRange($hire_date,$now_date);
                
                echo "Selisih Tanggal Masuk :".$range1."<br/>";
				
                //if > 1 (one) year in executed hak cuti
                if($range1 >= 365) {
                    $lyear = date('Y') - 1;
                    $ldate = $lyear.substr($hire_date,4,6);
                    
                    // + 1 tahun untuk Expired
                    $exlyear = substr($ldate,0,4)+1;
                    $exldate = $exlyear.substr($ldate,4,6);
                    
                    $range2 = \app\components\Common::dateRange($now_date,$exldate);
                    if($range2>0) {
                        $ldate = $ldate;
                    }
                    else {
                        $ldate = $exldate;
                    }
                
                    echo "Tanggal Hak Cuti :".$ldate."<br/>";    
                    $leaves = \app\models\LeaveBalance::findOne(['employee_id' => $row->employee_id,'leave_balance_date' => $ldate,'leave_balance_stype' => 0]);
                    
                    if(!$leaves) {
                        $lbtotal = 12;
                        $ledate = $ldate;
                        $description = 'Hak Cuti Tahun ('.\app\components\Common::MysqlDateToString($ldate).') Dengan Jumlah '.$lbtotal;
                        echo $description."<br/>";
                        
                        $exyear = substr($ldate,0,4) + 1;
                        $exdate = $exyear.substr($ldate,4,6);
                        echo "Tanggal Expired Cuti : ".$exdate."<br/>";
                        /**save data update **/
                        $modelUpdate = new \app\models\LeaveBalance();
                        $modelUpdate->employee_id = $row->employee_id;
                        $modelUpdate->leave_balance_date = $ldate;
                        $modelUpdate->leave_balance_description = $description;
                        $modelUpdate->leave_balance_total = $lbtotal;
                                    $modelUpdate->leave_balance_stype = 0;
                        $modelUpdate->leave_balance_created_date =date('Y-m-d H:i:s');	
                        $modelUpdate->leave_balance_created_by = 0;
                        $modelUpdate->insert();
                        /** save data update **/
                        
                        $last_leave = \app\models\LeaveBalance::sumLastLeaveBalance($row->employee_id,$ldate);
                        
                        if($last_leave->total > 0)
                            $xtotal = $last_leave->total;
                        else
                            $xtotal = 0;
                        
                        //apabila ada sisa cuti + sebelum hak cuti maka kurangi
                        if($xtotal>0) {
                            $xtotalmin = $xtotal * -1;
                            $descriptionx = 'Hak Cuti Hangus Sebelum Tanggal '.\app\components\Common::MysqlDateToString($ldate).' Dengan Jumlah '.$xtotal;
                            echo $descriptionx."<br/>";
                            /**save data update **/
                            $modelUpdate = new \app\models\LeaveBalance();
                            $modelUpdate->employee_id = $row->employee_id;
                            $modelUpdate->leave_balance_date = $ldate;
                            $modelUpdate->leave_balance_description = $descriptionx;
                            $modelUpdate->leave_balance_total = $xtotalmin;
                            $modelUpdate->leave_balance_stype = 1;
                            $modelUpdate->leave_balance_created_date = date('Y-m-d H:i:s');	
                            $modelUpdate->leave_balance_created_by = 0;
                            $modelUpdate->insert();
                            /**save data update **/
                            
                        }
                        
                    }
                    
                }
                
                
                //search employee
                $sumTotal = \app\models\LeaveBalance::sumLastBalanceByEmployee($row->employee_id);
                
                if($sumTotal->total>0)
                    $xtotals = $sumTotal->total;
                else 
                    $xtotals = 0;
                
                $sumUse = \app\models\Leaves::sumLastLeaveByEmployee($row->employee_id);
                
                if($sumUse->total>0)
                    $xuse = $sumUse->total;
                else 
                    $xuse = 0;
                
                $xo = $xtotals - $xuse;    
                    
                echo "Total Hak Cuti : ".$xtotals."<br/>";
                echo "Total Cuti : ".$xuse."<br/>";
                echo "Sisa Cuti : ".$xo."<br/>";  
                
                //update employee
                $update = \app\models\Employee::updateAll(['EmployeeLeaveTotal' => $xtotals,'EmployeeLeaveUse' => $xuse],['employee_id'=>$row->employee_id]);
		
                if($ledate)
                    $update = \app\models\Employee::updateAll(['EmployeeLeaveDate' => $ledate],['employee_id'=>$row->employee_id]);
                
                echo "==================================================================== <br/>";
            }
        }
    }
    
    
    public function actionTimesheet()
    {
        $total = 30;
        //counter start day
        $newdate = '2015-07-13';
        
        for($i=1;$i<=$total;$i++)
        {
            //check sunday & saturday only monday - friday
            if(date('N', strtotime($newdate))<6)
            {
                //check holiday
                $holiday = \app\models\Holiday::find()->where(['holiday_date' => $newdate])->one();
                //if not holiday execution this leave
                if(!$holiday)
                {
                    //search for active employee
                    $employeeModel = new \app\models\Employee();
                    $employeeRecords = $employeeModel->getAllEmployee();
                    
                    foreach($employeeRecords as $user)
                    {
                        //check this date leave on leave table
                        $Leaves = \app\models\Leaves::find()
                        ->andFilterWhere(['like', "leave_range", Yii::$app->formatter->asDatetime($newdate,"php:d/m/Y")])
                        ->andWhere(['employee_id' => $user->employee_id])
                        ->one();
                    
                        // if ready execution / transfer to timesheet
                        if($Leaves)
                        {
                            $timesheetModel = new \app\models\Timesheet();
                            //check if ready on timesheet
                            $status = $Leaves->leave_approved;
                            $timesheet = [
                                'employee_id' => $Leaves->employee_id,
                                'date' => $newdate
                            ];
                            $EmployeeTimesheet = $timesheetModel->checkTimesheetByEmployee($timesheet);
                            //if not data on this date from timesheet, transfer leave to timesheet
                            if(!$EmployeeTimesheet)
                            {
                                $ddate = $newdate;
                                $date = new \DateTime($ddate);
                                
                                switch($status)
                                {
                                    /**
                                     * Timesheet : 1 : Waiting , 2 : Approved , 3 : Reject
                                     * Leaves : 1 : Approved , 2 : Reject , 3 : Process , 4 : Leaves Waiting
                                    */
                                    case 1 : $tstatus = 2;break;
                                    case 2 : $tstatus = 3;break;
                                    default : $tstatus = 4;break;
                                }
                                
                                $timesheet = [
                                    'week'  =>  $date->format("W"),
                                    'year'  =>  $date->format("Y"),
                                    'project_id' => 1,
                                    'job_id' => 11,
                                    'notes' => "Leave :".$Leaves->leave_description,
                                    'date' => $newdate,
                                    'employee_id' => $Leaves->employee_id,
                                    'hour' => 8, 
                                    'overtime' => 0,
                                    'transport_type' => 1,
                                    'cost' => 0,
                                    'approval' => $tstatus
                                ];
                                
                                //get timesheet status id 
                                $timesheetStatusModel = new \app\models\TimesheetStatus();
                                //counter for timesheet status id
                                $timesheet_status_id = 0;
                                $EmployeeWeek = $timesheetStatusModel->checkTimesheetWeek($timesheet);
                                if (!$EmployeeWeek)
                                    $timesheet_status_id = $timesheetStatusModel->insertTimesheetWeekly($timesheet);
                                else
                                    $timesheet_status_id = $EmployeeWeek->timesheet_status_id;
                                //insert timesheet by leave    
                                $insertTimesheet =  $timesheetModel->saveTimesheet($timesheet,$timesheet_status_id);    
                            }
                            
                            
                        }    
                    }
                    
                }
            }
            
            //counter for next day
            $newdate = date('Y-m-d', strtotime('+1 days', strtotime($newdate)));
        }
    }
    
    
   
    
    
}