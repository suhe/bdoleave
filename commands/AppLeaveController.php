<?php
namespace app\commands;
use Yii;
use yii\console\Controller;
use app\models\Leaves;
use app\models\Employee;

class AppLeaveController extends Controller  {
	/**
	 * This command echoes what you have email.
	 * @param email
	 * //cron job php -q /home/k0455101/public_html/devleave/yii app-leave/email
	 * php -q /home/k0455101/public_html/devleave/yii app-leave/approval
	 */
	public function actionApproval() {
		if(Yii::$app->params['send_email'] == true) {
			$apps = Leaves::getAppAllLeave();
			if($apps) {
				$mail = [];
				foreach($apps as  $app) {
					$employee_id = 0;
					if($app->leave_app_user1_status == Leaves::$approval_progress) {
						$employee_id = $app->leave_app_user1;
					} else if($app->leave_app_hrd_status == Leaves::$approval_progress) {
						$employee_id = $app->leave_app_hrd;
					} else if($app->leave_app_pic_status == Leaves::$approval_progress) {
						$employee_id = $app->leave_app_pic;
					}
						
					$employee = Employee::findOne($employee_id);
					if($employee) {
						$email_receiver = $employee ? $employee->EmployeeEmail : "";
						if($email_receiver) {
							$mail[]  = Yii::$app->mailer->compose('leave_form',['data' => $app])
							->setFrom(Yii::$app->params['mail_user'])
							->setTo($email_receiver)
							->setCCarray('addrs3@gmail.com')
							->setSubject(Yii::t('app/message','msg request approved leave form'));
						}
					}
				}
				//send multiple email
				Yii::$app->mailer->sendMultiple($mail);
			}
		}
		/** end of send email **/
		return false;
	}
	
	/**
	 * This command echoes what you have email.
	 * @param email
	 * //cron job php -q /home/k0455101/public_html/devleave/yii app-leave/email
	 * php -q /home/k0455101/public_html/devleave/yii app-leave/approved
	 */
	public function actionApproved() {
		if(Yii::$app->params['send_email'] == true) {
			$apps = Leaves::getApprovedTodayLeave();
			if($apps) {
				$mail = [];
				foreach($apps as  $app) {
					$email_receiver = $app->EmployeeEmail;
					if($email_receiver) {
						$mail[]  = Yii::$app->mailer->compose('leave_form_approved',['data' => $app])
						->setFrom(Yii::$app->params['mail_user'])
						->setTo($email_receiver)
						->setSubject(Yii::t('app/message','msg result approved leave form'));
					}
				}
				//send multiple email
				Yii::$app->mailer->sendMultiple($mail);
			}
		}
		/** end of send email **/
		return false;
	}	
}