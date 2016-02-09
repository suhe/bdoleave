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
				'only' => ['index', 'form', 'detail-view','form-leave','load-single-employee'],
				'rules' => [
					[
						'allow' => true,
						'actions' => ['index', 'form', 'detail-view','form-leave','load-single-employee'],
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
				'title' => Yii::t('app','my leave'),
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
			return $this->redirect(['my-leave/index'],301);
		}
	
		return $this->render('form-single-leave',[
				'title' => Yii::t('app','leave form'),
				'employee' => Employee::getEmployee(Yii::$app->user->getId()),
				'model' => $model,
		]);
	}
	
	
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

}