<?php
namespace app\models;
use yii;


class Report extends \yii\base\Model{
    
    public static function getHelpdeskList(){
        $query = \app\models\Helpdesk::find()
        ->select(["*","CONCAT(e.EmployeeFirstName,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) as employee_name"])
        ->from('helpdesk h')
        ->join('inner join','employee e','e.employee_id = h.employee_id')
        ->where(['NOT IN', 'role_id', [1]])
        ->orderBy('e.EmployeeFirstName')
	->all();
	
	$arr=[];
	foreach($query as $row){
	    $arr[]=$row->employee_name;
	}
	return $arr;
    }
    
    public static function getHelpdeskChartData($status_from,$status_to,$params){
        $model = new Ticket(['scenario'=>'report']);
	$sqldetails = "(SELECT COUNT(ticket_id) FROM ticket t
	WHERE t.ticket_helpdesk=h.`employee_id`
	AND (ticket_status>=$status_from AND ticket_status<=$status_to)) AS total";
	if (($model->load($params))) {
	    $sqldetails = "(SELECT COUNT(ticket_id) FROM ticket t
	    WHERE t.ticket_helpdesk=h.`employee_id`
	    AND (ticket_status>=$status_from AND ticket_status<=$status_to)
	    AND (ticket_date>='".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$model->ticket_from_date)."'
	    AND ticket_date<='".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$model->ticket_to_date)."')
	    ) AS total ";
	}
	
	$sql="
        SELECT
        $sqldetails
        FROM helpdesk h
        INNER JOIN employee e ON e.`employee_id`=h.`employee_id`
        WHERE h.`role_id`=2
        GROUP BY h.`employee_id`
        ORDER BY CONCAT(e.EmployeeFirstName,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) ASC
        ";
        $query = \app\models\Ticket::findBySql($sql)->all();
        $arr=[];
    
        foreach($query as $val){
                $arr[] = (int)$val->total;
        }
        return $arr;
    }
    
}