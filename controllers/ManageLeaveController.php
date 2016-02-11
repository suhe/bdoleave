<?php
/**
 * Class AppLeave Controller
* Approved/InApproved by Manager or Partner
*/

namespace app\controllers;

use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\Role;
use app\models\Employee;
use app\models\Leaves;
use app\models\LeaveBalance;
use app\models\LeaveLog;

class ManageLeaveController extends Controller {
	/**
	 * Behaviour Function
	 * Control Access Control Rule
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'ruleConfig' => [
					'class' => Role::className(),
				],
				'only' => ['index', 'form', 'detail-view','form-leave','form-balance','load-single-employee','approved'],
				'rules' => [
					[
						'allow' => true,
						'actions' => ['index', 'form', 'detail-view','form-leave','form-balance','load-single-employee','approved'],
						'roles' => [
							Employee::ROLE_SENIOR_HRD,
							Employee::ROLE_MANAGER_HRD
						],
					],
				],
				'denyCallback' => function ($rule, $action) {
					echo ('You are not allowed to access this page');
				},
			],
		];
	}
	
	/**
	 *  Action My Leave
	 * @return string
	 */
	public function actionIndex() {
		$app = Leaves::getApprovedTodayLeave();
		$model = new \app\models\Leaves(['scenario' => 'search']);
		if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){

		}
		return $this->render('index',[
				'title' => Yii::t('app','outstanding leave'),
				'model' => $model,
				'dataProvider' => $model->getAppManageLeaveDataProvider(Yii::$app->request->queryParams)
		]);
	}

	/**
	 * Action App Leave Form
	 * Proses to Form CRUD
	 * @return \yii\web\Response|string
	 */
	public function actionForm($id) {
		$model = new Leaves(['scenario' => 'app-completed']);
		$app_model = $model->getDetailView($id);
		if($model->load(Yii::$app->request->post()) && $model->getCompletedLeaveRequest($id)){
			Yii::$app->session->setFlash('message','<div class="alert alert-success"> <strong>'.Yii::t('app','success').'! </strong>'.Yii::t('app/message','msg request has been completed').'</strong></div>');
			return $this->redirect(['app-leave/index'],301);
		}

		return $this->render('form',[
				'id' => $id,
				'title' => Yii::t('app','leave form'),
				'leave' => $model->getDetailView($id),
				'employee' => Employee::getEmployee($app_model->employee_id),
				'model' => $model,
				'logDataProvider' => LeaveLog::getLogLeaveDataProvider($id),
		]);
	}
	
	/**
	 * Leave Details View
	 * @param unknown $id
	 * @return string
	 */
	public function actionDetailView($id) {
		$model = new \app\models\Leaves();
		$app_model = $model->getDetailView($id);
		
		if($model->load(Yii::$app->request->post()) && $model->getCompletedLeaveRequest($id)){
			Yii::$app->session->setFlash('message','<div class="alert alert-success"> <strong>'.Yii::t('app','success').'! </strong>'.Yii::t('app/message','msg request has been completed').'</strong></div>');
			return $this->redirect(['manage-leave/index'],301);
		}
		
		return $this->render('detail-view',[
				'title' => Yii::t('app','manage leave'),
				'id' => $id,
				'model' => $model,
				'employee' => Employee::getEmployee($app_model->employee_id),
				'leave' => $app_model,
				'logDataProvider' => LeaveLog::getLogLeaveDataProvider($id),
		]);
	}
	
	/**
	 * Action Single Employee Formulir
	 * Proses to Form CRUD
	 * @return \yii\web\Response|string
	 */
	public function actionFormLeave() {
		$model = new \app\models\Leaves(['scenario' => 'add_leave']);
		if($model->load(Yii::$app->request->post()) && $model->getSaveLeaveRequest()){
			Yii::$app->session->setFlash('message','<div class="alert alert-success"> <strong>'.Yii::t('app','success').'! </strong>'.Yii::t('app/message','msg request has been created').'</strong></div>');
			return $this->redirect(['manage-leave/index'],301);
		}
	
		return $this->render('form-single-leave',[
				'title' => Yii::t('app','leave form'),
				'employee' => Employee::getEmployee(Yii::$app->user->getId()),
				'model' => $model,
		]);
	}
	
	/**
	 * Action Single Employee Formulir
	 * Proses to Form CRUD
	 * @return \yii\web\Response|string
	 */
	public function actionFormBalance() {
		$model = new \app\models\LeaveBalance(['scenario' => 'save']);
		if($model->load(Yii::$app->request->post()) && $model->getSaveBalanceRequest()){
			Yii::$app->session->setFlash('message','<div class="alert alert-success"> <strong>'.Yii::t('app','success').'! </strong>'.Yii::t('app/message','msg request has been created').'</strong></div>');
			return $this->redirect(['manage-leave/index'],301);
		}
	
		return $this->render('form-single-balance',[
				'title' => Yii::t('app','leave balance form'),
				'employee' => Employee::getEmployee(Yii::$app->user->getId()),
				'model' => $model,
		]);
	}
	
	/**
	 * Load Single Employee Data
	 * return $data
	 */
	public function actionLoadSingleEmployee() {
		$employee_id = Yii::$app->request->post('employee_id');
		$data = Employee::getEmployee($employee_id);
		if($data) {
			$result = [
					'EmployeeID' => $data->EmployeeID,
					'EmployeeName' => $data->EmployeeFirstName.' '.$data->EmployeeMiddleName.' '.$data->EmployeeLastName,	
					'EmployeeTitle' => $data->EmployeeTitle,
					'EmployeeHireDate' => $data->EmployeeHireDate,
					'EmployeeManagerApproval' => $data->manager_approval,
					'EmployeeManagerEmail' => $data->manager_email,
					'EmployeeHRDApproval' => $data->hrd_approval,
					'EmployeeHRDEmail' => $data->hrd_email,
					'EmployeePartnerApproval' => $data->partner_approval,
					'EmployeePartnerEmail' => $data->partner_email,
					'EmployeeSaldoBalance' => ($data->EmployeeLeaveTotal - $data->EmployeeLeaveUse) ,
			];
		}
		return json_encode($result);
	}
	
	/**
	 * This command echoes what you have email.
	 * @param email
	 * //cron job php -q /home/k0455101/public_html/devleave/yii app-leave/email
	 * php -q /home/k0455101/public_html/devleave/yii app-leave/approved
	 */
	public function actionApproved() {
		echo 'x';
		if(Yii::$app->params['send_email'] == true) {
			$apps = Leaves::getApprovedTodayLeave();
			if($apps) {
				$mail = [];
				foreach($apps as  $app) {
					echo $app->leave_date_from;
					$email_receiver = $app->EmployeeEmail;
					//if($email_receiver) {
					$mail[]  = Yii::$app->mailer->compose('leave_form_approved',['data' => $app])
					->setFrom(Yii::$app->params['mail_user'])
					->setTo("hendarsyahss@gmail.com")
					->setSubject(Yii::t('app/message','msg result approved leave form'));
					//}
				}
				//send multiple email
				Yii::$app->mailer->sendMultiple($mail);
			}
		}
		/** end of send email **/
		return false;
	}

}