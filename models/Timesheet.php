<?php
namespace app\models;
use yii;

class Timesheet extends \yii\db\ActiveRecord {
    
    public static function tableName(){
        return 'timesheet';
    }
    
    public function saveTimesheet($timesheet,$statusID){
        $model = new Timesheet();
        $model->timesheet_status_id = $statusID;
        $model->project_id = $timesheet['project_id'];
        $model->job_id = $timesheet['job_id'];
        $model->employee_id = $timesheet['employee_id'];
        $model->week = $timesheet['week'];
        $model->year = $timesheet['year'];
        $model->hour = $timesheet['hour'];
        $model->overtime = $timesheet['overtime'];
        $model->cost = $timesheet['cost'];
        $model->transport_type = $timesheet['transport_type'];
        $model->transport_cost = $timesheet['cost'];
        $model->transport_paid = 1;
        $model->notes = $timesheet['notes'];
        $model->source = Yii::$app->params['source_number'];
        $model->timesheetdate = $timesheet['date'];
        $model->timesheet_approval = $timesheet['approval'];
        $model->sysdate = date('Y-m-d H:i:s');
        $model->sysuser = Yii::$app->user->getId();
        $model->save();
    }
    
    //  checkTimesheetWeek
	/*-------------------------------------------------------------------------------------*/
	public  function checkTimesheetByEmployee($timesheet) {
	    $sql = "
		select *
		from timesheet t
		where t.timesheetdate = '".$timesheet['date']."'
                and t.employee_id ='".$timesheet['employee_id']."'
		and t.project_id = 1 
		and t.job_id in (10,11,12)";
	    return Timesheet::findBySql($sql)->one();
	}
    
    //  checkTimesheetWeek
	/*-------------------------------------------------------------------------------------*/
	public  function checkTimesheetByApproval($timesheet) {
	    $sql = "
		select *
		from timesheet t
		where t.timesheetdate = '".$timesheet['date']."'
                and t.employee_id=".$timesheet['employee_id']." 
		and t.project_id=".$timesheet['project_id']." 
		and t.job_id =".$timesheet['job_id'];
	    return Timesheet::findBySql($sql)->one();
	}
    
    
}