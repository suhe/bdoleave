<?php
namespace app\models;
use yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * Ticket Status
 * 0 = Closed , 1 = Finish , 2 = Proses , 3 = Open , 4 = New
 **/

class Ticket extends ActiveRecord {
    public $id;
    public $ticket_bulk;
    public $closed=0;
    public $finish=1;
    public $process=2;
    public $open=3;
    public $new=4;
    public $ticket_from_date;
    public $ticket_to_date;
    public $ticket_status_string;
    public $helpdesk_name;
    public $employee_name;
    public $EmployeeFirstName;
    public $total;

    public static function tableName(){
        return 'ticket';
    }
    
    public function rules(){
        return [
          [['ticket_id'],'safe'],
          [['ticket_subject','ticket_type','ticket_note'],'required','on'=>['new_ticket']],
          [['ticket_status_string'],'string'],
          [['ticket_from_date','ticket_to_date','ticket_status'],'safe','on'=>'search'],
          [['ticket_helpdesk','ticket_handling'],'required','on'=>['set_helpdesk']],
          [['ticket_subject','ticket_type','ticket_note','employee_id','ticket_helpdesk','ticket_handling'],'required','on'=>['order_ticket']],
          [['total'],'integer'],
          [['ticket_from_date','ticket_to_date'],'safe','on'=>'report'],
        ];
    }
    
    public function attributeLabels(){
        return [
            'ticket_id' => Yii::t('app','id'),
            'ticket_date' => Yii::t('app','date'),
            'ticket_subject' => Yii::t('app','subject'),
            'ticket_status' => Yii::t('app','status'),
            'ticket_status_string' => Yii::t('app','status'),
            'ticket_helpdesk' => Yii::t('app','helpdesk'),
            'ticket_handling' => Yii::t('app','handling'),
            'ticket_type' => Yii::t('app','type'),
            'ticket_note' => Yii::t('app','note'),
            'helpdesk_name' => Yii::t('app','support'),
            'employee_id' => Yii::t('app','employee'),
            'employee_name' => Yii::t('app','name'),
            'ticket_usercomment' => Yii::t('app','replies'),
            'ticket_from_date' => Yii::t('app','from'),
            'ticket_to_date' => Yii::t('app','to'),
        ];
    }
    
    public static function getCheckId($id){
        $query = Ticket::findOne($id);
        if(!$query)
           return false;
        return true;
    }
    
    public function getRelationId(){
        $query = Ticket::find()
        ->select(['MAX(ABS(ticket_id)) as id'])
        ->where(['ticket_cid'=>Yii::$app->user->getId()])
        ->one();
        if($query)
          $id = $query->id;
        else
           $id = 0;
        return $id;   
    }
    
    public function getSave($status=false){
        if($this->validate()){
            $model = new Ticket();
            $model->ticket_date = date('Y-m-d');
            
            if($status==true){
                $employee = $this->employee_id;
                $model->ticket_helpdesk = $this->ticket_helpdesk;
                $model->ticket_handling = $this->ticket_handling;
                $model->ticket_status = $this->open;
            }    
            else {
                $employee = Yii::$app->user->getId();
                $model->ticket_status = $this->new;
            }
            
            
            $model->employee_id = $employee;
            $model->ticket_usercomment = Yii::t('app/message','msg new ticket');
            $model->ticket_subject = $this->ticket_subject;
            $model->ticket_type = $this->ticket_type;
            $model->ticket_note = $this->ticket_note;
            $model->ticket_cdate = date('Y-m-d H:i:s');
            $model->ticket_cid = Yii::$app->user->getId();
            $model->insert();
            return true;
        }
    }
    
    public function getTicketData($params,$employee_id=null,$status=null,$helpdesk=null){
        $sql=" SELECT ticket_id,DATE_FORMAT(ticket_date,'%d/%m/%Y') as ticket_date,ticket_subject,
        CONCAT(hh.EmployeeFirstName,' ',hh.EmployeeMiddleName,' ',hh.EmployeeLastName) as helpdesk_name,ticket_usercomment,
        CONCAT(e.EmployeeFirstName,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) as employee_name,
        CASE ticket_status
            WHEN 4 THEN 'Baru'
            WHEN 3 THEN 'Buka'
            WHEN 2 THEN 'Proses'
            WHEN 1 THEN 'Selesai'
            WHEN 0 THEN 'Tutup'
        END ticket_status_string,ticket_helpdesk
        FROM ticket t
        LEFT JOIN helpdesk h on h.employee_id = t.ticket_helpdesk
        LEFT JOIN employee hh on hh.employee_id = h.employee_id
        INNER JOIN employee e on e.employee_id = t.employee_id
        WHERE ticket_id>0 ";
        
        if($employee_id) $sql.= " AND t.employee_id=".$employee_id;
        if($status) $sql.= " AND ticket_status=".$status;
        if($helpdesk) $sql.= " AND ticket_helpdesk=".$helpdesk;
        
        if ($this->load($params) && $this->ticket_status)
            $sql.=" AND ticket_status = ".$this->ticket_status; 
        
        if ($this->load($params) && $this->ticket_from_date)
            $sql.=" AND ticket_date >= '".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_from_date)."'";
        
        if ($this->load($params) && $this->ticket_to_date)
            $sql.=" AND ticket_date <= '".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_to_date)."'";
        
        $sql.=" ORDER BY ABS(ticket_id) DESC ";    
        $query = Ticket::findBySql($sql);
        $countQuery = count($query->all());
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'totalCount' => $countQuery,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page'],
            ]    
        ]);
        return $dataProvider;
    }
    
    public function getTicketSingleData($id){
        $sql=" SELECT ticket_id,DATE_FORMAT(ticket_date,'%d/%m/%Y') as ticket_date,ticket_subject,
        CONCAT(hh.EmployeeFirstName,' ',hh.EmployeeMiddleName,' ',hh.EmployeeLastName) as helpdesk_name,ticket_usercomment,
        CONCAT(e.EmployeeFirstName,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) as employee_name,
        CASE ticket_status
            WHEN 4 THEN 'Baru'
            WHEN 3 THEN 'Buka'
            WHEN 2 THEN 'Proses'
            WHEN 1 THEN 'Selesai'
            WHEN 0 THEN 'Tutup'
        END ticket_status_string,ticket_helpdesk,ticket_note,ticket_type,ticket_handling
        FROM ticket t
        LEFT JOIN helpdesk h on h.employee_id = t.ticket_helpdesk
        LEFT JOIN employee hh on hh.employee_id = h.employee_id
        INNER JOIN employee e on e.employee_id = t.employee_id
        WHERE ticket_id=".$id;
        $query = Ticket::findBySql($sql)->one();
        return $query;
    }
    
    public function getSetHelpdesk($id){
        $model = new Ticket(['scenario'=>'set_helpdesk']);
        if($this->validate()){
            $model = $model->findOne($id);
            $model->ticket_helpdesk = $this->ticket_helpdesk;
            $model->ticket_handling = $this->ticket_handling;
            $model->ticket_status = $this->open;
            $model->ticket_usercomment = Yii::t('app/message','msg ticket set helpdesk');
            $model->ticket_uid = Yii::$app->user->getId();
            $model->ticket_udate = date('Y-m-d H:i:s');
            $model->update();
            
            //comment for update
            $relation = new \app\models\TicketLog();
            $relation->ticket_id = $id;
            $relation->employee_id = Yii::$app->user->getId();
            $relation->to_employee_id = $this->ticket_helpdesk;
            $relation->log_date = date('Y-m-d H:i:s');
            $relation->log_desc = $this->ticket_handling;
            $relation->insert();
            
            return true;
        }
    }
    
    public static function getHelpdeskNewTicketData($status){
        $ticket = new Ticket();
        $query = $ticket->find()
        ->select(['*'])
        ->join('inner join','employee','employee.employee_id=ticket.employee_id')
        ->where(['ticket_status'=>$status])
        ->all();
        return $query;
    }
    
    public static function getHelpdeskTicketData($status){
        $ticket = new Ticket();
        $query = $ticket->find()
        ->select(['*'])
        ->join('inner join','employee','employee.employee_id=ticket.employee_id')
        ->where(['ticket_status'=>$status,'ticket_helpdesk'=>Yii::$app->user->getId()])
        ->all();
        return $query;
    }
}