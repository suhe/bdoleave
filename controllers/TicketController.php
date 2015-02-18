<?php
namespace app\controllers;
use yii;
use yii\web\Controller;
use app\models\Ticket;
use app\models\TicketLog;
use app\models\Helpdesk;
use app\models\Employee;
/**
 * Site controller
 */
class TicketController extends Controller {
    
    public $class='Ticket';
    
    public function ___construct(){
        parent::___construct();
        if(!Yii::$app->user->getId())
            return $this->redirect(['site/login']);
    }
    
    public function actions(){
        if(Yii::$app->user->isGuest){
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg you must login'));
            return $this->redirect(['site/login']);
        }
    }
    
    public function actionIndex(){
        if(Yii::$app->session->get('helpdesk')) return $this->redirect(['ticket/myjob']); 
        $model = new Ticket(['scenario'=>'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('ticket_index',[
            'model' => $model,
            'dataProvider' => $model->getTicketData(Yii::$app->request->queryParams,Yii::$app->user->getId())
        ]); 
    }
    
    public function actionNew(){
        if(Yii::$app->session->get('helpdesk')) return $this->redirect(['ticket/order']);
        $email = Employee::getEmployeeEmailById(Yii::$app->user->getId());
        if(!$email){
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg please fill email'));
            return $this->redirect(['administration/general'],301);
        }
        $model = new Ticket(['scenario' => 'new_ticket']);
        if($model->load(Yii::$app->request->post()) && $model->getSave()){
            $ticket_att = new \app\models\TicketAttachment();
            $ticket_att = $ticket_att->find()
            ->where(['ticket_id' => 0 , 'ticket_md5'=>md5(Yii::$app->user->getId())])
            ->one();
            if(count($ticket_att)>0){
                $ticket_att->ticket_id = $model->getRelationId();
                $ticket_att->update();
            }
            
            //register email for user login
            $users[] = $email;
            //create mail
            $query = Helpdesk::find()
            ->select(['employee_id'])
            ->where(['role_id'=>2])
            ->all();
    
            foreach($query as $val){
                $users[] = Employee::getEmployeeEmailById($val->employee_id);
            }
            
            $mail = [];
            $ticket = new Ticket();
            foreach ($users as $user) {
            $mail[] = Yii::$app->mailer->compose('ticket-open',['data' => $ticket->getTicketSingleData($model->getRelationId())]) 
                ->setFrom(Yii::$app->params['mail_user'])
                ->setTo($user)
                ->setSubject(Yii::t('app/message','msg create a new ticket').' '.$model->getRelationId());
            }
            Yii::$app->mailer->sendMultiple($mail);
            
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg ticket has been insert'));
            $this->redirect(['ticket/index'],301);
        }
        return $this->render('ticket_form',[
            'model' => $model,
        ]);    
    }
    
    public function actionView($id){
        if(Yii::$app->session->get('helpdesk')) return $this->redirect(['ticket/open','id'=>$id]);
        if(!Ticket::getCheckId($id)) return $this->redirect(['ticket/index']);
        $model = new TicketLog();
        $query = Ticket::findOne($id);
        $logQuery = $model->getTicketLogData($id);
        if($model->load(Yii::$app->request->post()) && $model->getSave($id)){
            //create mail
            $users[] = Employee::getEmployeeEmail($id);
            $ticket = new Ticket();
            $query = $ticket->getTicketSingleData($id);
            $mail = [];
            if(count($users)>0){
                foreach ($users as $user) {
                $mail[] = Yii::$app->mailer->compose('ticket-reply',[
                            'title' => $query->ticket_subject,
                            'ticket_id' => $query->ticket_id,
                            'employee_name' => Yii::$app->user->identity->EmployeeFirstName.' '.Yii::$app->user->identity->EmployeeMiddleName.' '.Yii::$app->user->identity->EmployeeLastName, 
                            'date' => date('d/m/Y H:m:s'),
                            'message' => $model->log_desc,
                        ])
                    ->setFrom(Yii::$app->params['mail_user'])
                    ->setTo($user)
                    ->setSubject(Yii::t('app/message','msg ticket reply').' #'.$id);
                }
            }
            Yii::$app->mailer->sendMultiple($mail);
            $model->refresh();
            Yii::$app->response->format = 'json';
            return ['success' => true,'comment' => $model->log_desc,'date' => date('d/m/Y H:i:s'),'employee'=>Yii::$app->user->identity->EmployeeFirstName];
            
        }
        
        //change unread to read in ticket log
        TicketLog::updateAll(['ticket_read' => 0],['to_employee_id'=>Yii::$app->user->getId(),'ticket_id'=>$id]);
    
        return $this->render('ticket_view',[
            'model' => $model,
            'query' => $query,
            'logQuery' => $logQuery
        ]);
    }
    
    public function actionManagement(){
        if(!Yii::$app->session->get('helpdesk')) return $this->redirect(['ticket/index','id'=>$id]);
        $model = new Ticket(['scenario'=>'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('ticket_management',[
            'model' => $model,
            'dataProvider' => $model->getTicketData(Yii::$app->request->queryParams,'',4)
        ]); 
    }
    
    public function actionSet($id){
        if(!Yii::$app->session->get('helpdesk')) return $this->redirect(['ticket/index']);
        if(!Ticket::getCheckId($id)) return $this->redirect(['ticket/management']);
        $model = new Ticket(['scenario'=>'set_helpdesk']);
        if($model->load(Yii::$app->request->post()) && $model->getSetHelpdesk($id)){
            //create mail
            $query = Ticket::findOne($id);
            $users[] = Employee::getEmployeeEmailById($query->employee_id);
            $users[] = Employee::getEmployeeEmailById($query->ticket_helpdesk);
            $ticket = new Ticket();
            $mail = [];
            foreach ($users as $user) {
            $mail[] = Yii::$app->mailer->compose('ticket-setHelpdesk',[
                        'data' => $ticket->getTicketSingleData($id)
                    ])
                ->setFrom(Yii::$app->params['mail_user'])
                ->setTo($user)
                ->setSubject(Yii::t('app/message','msg ticket set helpdesk').' #'.$id);
            }
            Yii::$app->mailer->sendMultiple($mail);
            
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg set helpdesk successfully'));
            return $this->redirect(['ticket/management']);
        }
        
        $ticket = $model->find()
        ->select(['*',"CONCAT(e.EmployeeFirstName,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) as employee_name ","DATE_FORMAT(ticket_date,'%d/%m/%Y') as ticket_date"])
        ->from('ticket t')
        ->join('inner join','employee e','e.employee_id=t.employee_id')
        ->where(['t.ticket_id'=>$id])
        ->one();
        $queryAtt = new \app\models\TicketAttachment();
        return $this->render('ticket_set',[
            'model' => $ticket,
            'queryatt' => $queryAtt->find()->where(['ticket_id'=>$id])->all(),
            'dropDownHelpdesk' => Helpdesk::getDropdownListData(false),
        ]); 
    }
    
    public function actionMyjob(){
        if(!Yii::$app->session->get('helpdesk')) return $this->redirect(['ticket/index']);
        $model = new Ticket(['scenario'=>'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('ticket_job',[
            'model' => $model,
            'dataProvider' => $model->getTicketData(Yii::$app->request->queryParams,'','',Yii::$app->user->getId())
        ]); 
    }
    
    public function actionOpen($id){
        if(!Yii::$app->session->get('helpdesk')) return $this->redirect(['ticket/view','id'=>$id]);
        if(!Ticket::getCheckId($id)) return $this->redirect(['ticket/myjob']);
        $model = new TicketLog();
        $query = Ticket::find()
        ->select(['*'])
        ->join('inner join','employee','employee.employee_id=ticket.employee_id')
        ->where(['ticket.ticket_id'=>$id])
        ->one();
        $queryAtt = new \app\models\TicketAttachment();
        $logQuery = $model->getTicketLogData($id);
        
        //change unread to read in ticket log
        TicketLog::updateAll(['ticket_read' => 0],['to_employee_id'=>Yii::$app->user->getId(),'ticket_id'=>$id]);
        
        if($model->load(Yii::$app->request->post()) && $model->getSave($id,true)){
            //create mail
            $users[] = Employee::getEmployeeEmail($id);
            $ticket = new Ticket();
            $query = $ticket->getTicketSingleData($id);
            $mail = [];
            foreach ($users as $user) {
            $mail[] = Yii::$app->mailer->compose('ticket-reply',[
                        'title' => $query->ticket_subject,
                        'ticket_id' => $query->ticket_id,
                        'employee_name' => Yii::$app->user->identity->EmployeeFirstName.' '.Yii::$app->user->identity->EmployeeMiddleName.' '.Yii::$app->user->identity->EmployeeLastName, 
                        'date' => date('d/m/Y H:m:s'),
                        'message' => $model->log_desc,
                    ])
                ->setFrom(Yii::$app->params['mail_user'])
                ->setTo($user)
                ->setSubject(Yii::t('app/message','msg ticket reply').' #'.$id);
            }
            Yii::$app->mailer->sendMultiple($mail);
            $model->refresh();
            Yii::$app->response->format = 'json';
            return ['success' => true,'comment' => $model->log_desc,'date' => date('d/m/Y H:i:s'),'employee'=>Yii::$app->user->identity->EmployeeFirstName];
        }
        return $this->render('ticket_open',[
            'model' => $model,
            'queryatt' => $queryAtt->find()->where(['ticket_id'=>$id])->all(),
            'query' => $query,
            'logQuery' => $logQuery
        ]);
    }
    
    public function actionOrder(){
        if(!Yii::$app->session->get('helpdesk')) return $this->redirect(['ticket/new']);
        $email = Employee::getEmployeeEmailById(Yii::$app->user->getId());
        if(!$email){
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg please fill email'));
            return $this->redirect(['administration/general'],301);
        }
        $model = new Ticket(['scenario' => 'order_ticket']);
        //set unique session
        $user = md5(Yii::$app->user->getId());
       
        if($model->load(Yii::$app->request->post()) && $model->getSave(true)){
            $ticket_att = new \app\models\TicketAttachment();
            $ticket_att = $ticket_att->find()
            ->where(['ticket_id' => 0 , 'ticket_md5'=>$user])
            ->one();
            if(count($ticket_att)>0){
                $ticket_att->ticket_id = $model->getRelationId();
                $ticket_att->update();
            }
            
            //create mail
            $query = Ticket::findOne($model->getRelationId());
            $users[] = Employee::getEmployeeEmailById($query->ticket_helpdesk);
            $users[] = Employee::getEmployeeEmailById($query->employee_id);
            $ticket = new Ticket();
            $mail = [];
            foreach ($users as $user) {
            $mail[] = Yii::$app->mailer->compose('ticket-setHelpdesk',[
                        'data' => $ticket->getTicketSingleData($model->getRelationId())
                    ])
                ->setFrom(Yii::$app->params['mail_user'])
                ->setTo($user)
                ->setSubject(Yii::t('app/message','msg ticket set helpdesk').' #'.$model->getRelationId());
            }
            Yii::$app->mailer->sendMultiple($mail);
            
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg ticket has been insert'));
            return $this->redirect(['ticket/management'],301);
        }
        //delete attachtment
        \app\models\TicketAttachment::deleteAll('ticket_id = :id and ticket_md5 =:md5 ',[':id' => 0,':md5'=>$user]);
        $employee = new Employee();
        return $this->render('ticket_order',[
            'model' => $model,
            'dropDownEmployee' => $employee->getEmployeeDropdownList(),
            'dropDownHelpdesk' => Helpdesk::getDropdownListData(false),
        ]);    
    }
    
    public function actionUpload(){
        $fileName = 'file';
        $uploadPath = 'uploads';
        $user = Yii::$app->user->identity->EmployeeID;
        $userUploadPath = $uploadPath.'/'.$user;
        
        if (isset($_FILES[$fileName])) {
            yii\helpers\FileHelper::createDirectory($userUploadPath,777,true);
            
            $file = \yii\web\UploadedFile::getInstanceByName($fileName);
            if ($file->saveAs($userUploadPath . '/' . 'attachment'.date('Y-m-d-H-i-s').'.'.$file->extension)) {
                //Now save file data to database
                $ticket_att = new \app\models\TicketAttachment();
                $ticket_att->ticket_id = 0;
                $ticket_att->ticket_md5 = md5(Yii::$app->user->getId());
                $ticket_att->attachment_file = $userUploadPath . '/' . 'attachment'.date('Y-m-d-H-i-s').'.'.$file->extension;
                $ticket_att->insert();
                echo \yii\helpers\Json::encode($file);
            }
            return true;
        }
        return false;
    }
    
    public function actionList(){
        if(!Yii::$app->session->get('helpdesk')) return $this->redirect(['ticket/index']);
        $model = new Ticket(['scenario'=>'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('ticket_list',[
            'model' => $model,
            'dataProvider' => $model->getTicketData(Yii::$app->request->queryParams)
        ]); 
    }
}
    