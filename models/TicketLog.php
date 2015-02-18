<?php
namespace app\models;
use yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use app\models\Ticket;

class TicketLog extends ActiveRecord {
    
    public $ticket_bulk;
    public $EmployeeFirstName;
    public $log_status;
    public $log_odate;

    public static function tableName(){
        return 'ticket_log';
    }
    
    public function rules(){
        return [
            [['log_desc'],'required'],
            [['log_status'],'safe']
        ];
    }
    
    public function attributeLabels(){
        return [
	   'log_status' => Yii::$app->session->get('helpdesk')?Yii::t('app','closed'):Yii::t('app','finish'),
        ];
    }
    
    public function getSave($id,$helpdesk=false){
        if($this->validate()){
            
            //find employee_id or ticket_helpdesk
            $user  = Ticket::findOne($id);
            if(!Yii::$app->session->get('helpdesk')){
                $user_id = $user->ticket_helpdesk;
                $closed_status = 1;
            }
            else{
                $user_id = $user->employee_id;
                $closed_status = 0;
            }
            
            
            $model = new TicketLog();
            $model->ticket_id = $id;
            $model->employee_id = Yii::$app->user->identity->getId();
            $model->to_employee_id = $user_id;
            $model->log_date = date('Y-m-d H:i:s');
            $model->log_desc = $this->log_desc;
            $model->ticket_read = 1;
            $model->insert();
            
            /*if($helpdesk==true){
                $ticket = new Ticket();
                $ticket = $ticket->findOne($id);
                if($this->log_status) $ticket->ticket_status = 1;
                else $ticket->ticket_status = 2;
                $ticket->update();
            }*/
            
            //update usercomment
            if(Yii::$app->session->get('helpdesk'))
                $comment  = Yii::t('app/message','msg support reply');
            else
                $comment  = Yii::t('app/message','msg user reply');
            
            if($this->log_status) $status = $closed_status;
            else $status=2;
            
            Ticket::updateAll(['ticket_usercomment'=>$comment,'ticket_status'=>$status],['ticket_id'=>$id]);
           
            return true;
        }
    }
    
    public function getTicketLogData($id){
        return TicketLog::find()
        ->select(['*',"DATE_FORMAT(log_date,'%d/%m/%Y %H:%m:%s') as log_date","DATE_FORMAT(log_date,'%Y/%m/%d %H:%m:%s') as log_odate"])
        ->from('ticket_log')
        ->join('left join','employee','employee.employee_id=ticket_log.employee_id')
        ->where(['ticket_id'=>$id])
        ->orderBy('log_date')
        ->all();
    }
    
    public static function getNewTicketLogData($limit=0){
        $query = TicketLog::find()
        ->select(['*'])
        ->from('ticket_log tl')
        ->join('inner join','ticket t','t.ticket_id=tl.ticket_id')
        ->join('inner join','employee e','e.employee_id=tl.employee_id')
        ->where(['ticket_read'=>1,'to_employee_id'=>Yii::$app->user->getId()])
        ->orderBy('tl.log_date')
        ->all();
        
        if($limit)
           $query = $query->limit($limit);
        return $query;
    }
}