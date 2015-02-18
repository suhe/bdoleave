<?php
namespace app\models;
use yii\db\ActiveRecord;

class TicketAttachment extends ActiveRecord {
    
    public $ticket_bulk;
    public $EmployeeFirstName;
    public $log_status;

    public static function tableName(){
        return 'ticket_attachment';
    }
    
}