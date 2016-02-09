<?php
namespace app\commands;
use Yii;
use yii\console\Controller;
use app\models\Leaves;

class AppController extends Controller  {
	/**
	 * This command echoes what you have email.
	 * @param email
	 * //cron job php -q /home/k0455101/public_html/devleave/yii app/ema
	 */
	public function actionIndex() {
		if(Yii::$app->params['send_email'] == true) { 
			$apps = Leaves::getAppLeave();
			if($apps) {
				foreach($apps as  $app) {
					$mail = [];  //create mail
					$employee_id = 0;
					if($app->leave_app_user1_status == Leaves::$approval_progress) {
						$employee_id = $app->leave_app_user1;
					} else if($app->leave_app_hrd_status == Leaves::$approval_progress) {
						$employee_id = $app->leave_app_hrd;
					} else if($app->leave_app_pic_status == Leaves::$approval_progress) {
						$employee_id = $app->leave_app_pic;
					}
					
					$employee_id = 10406;
					
					$employee = Employee::findOne($employee_id);
					if($employee) {
						$email_receiver = $employee ? $employee->EmployeeEmail : "";
						if($email_sender) {
							$mail[] = Yii::$app->mailer->compose('leave_form',['data' => $app])
							->setFrom(Yii::$app->params['mail_user'])
							->setTo($email_receiver)
							->setSubject(Yii::t('app/message','msg request approved leave form'))
							->send();
						}
					}
					return false;
				}
			}
		}
		/** end of send email **/
		return false;
	}
}


