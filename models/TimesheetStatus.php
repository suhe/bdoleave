<?php
namespace app\models;
use yii;

class TimesheetStatus extends \yii\db\ActiveRecord {
    
    public static function tableName() {
        return 'timesheet_status';
    }
    
    //  checkTimesheetWeek
	/*-------------------------------------------------------------------------------------*/
	public  function checkTimesheetWeek($timesheet) {
		$sql = "
			select timesheet_status_id
			from timesheet_status ts
			where ts.timesheet_approval = 4
			and ts.employee_id=".$timesheet['employee_id']." 
			and ts.week =".$timesheet['week']."
            and ts.year=".$timesheet['year'];
		return Timesheet::findBySql($sql)->one();
	}
    
    //  insertTimesheetWeekly
	/*-------------------------------------------------------------------------------------*/
	public function insertTimesheetWeekly($timesheet)  {
		$model = new TimesheetStatus();
        $model->week = $timesheet['week'];
        $model->year = $timesheet['year'];
        $model->timesheet_approval= $timesheet['approval'];
        $model->employee_id = $timesheet['employee_id'];
        $model->sysdate = date('Y-m-d H:i:s');
        $model->sysuser = Yii::$app->user->getId();
        $model->save();
        return $model->timesheet_status_id;
	}
    
}