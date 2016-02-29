<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\Role;
use app\models\Employee;
use app\models\Leaves;
use app\models\LeaveBalance;
use yii\base\Model;

class ToolController extends Controller {
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
				'only' => ['index', 'beginning-balance'],
				'rules' => [
					[
						'allow' => true,
						'actions' => ['beginning-balance'],
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
	 *  Action Beginning Balance
	 * @return string
	 */
	public function actionBeginningBalance() {
		$users = Employee::getAllEmployee();
		$multipleModel = new Model();
		//$model = new \app\models\Leaves(['scenario' => 'search']);
		if (($multipleModel->loadMultiple($users, Yii::$app->request->post())) && ($multipleModel->validateMultiple($users))) {
			//only checklist will update
			if(isset($user->employee_id)) {
				$count = 0;
				$active_date =  preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$user->employee_date_from);
				$balanced = new LeaveBalance();
				$balanced->leave_balance_date = $active_date;
				$balanced->leave_balance_total = $user->EmployeeLeaveTotal;
				$balanced->leave_balance_description = "Saldo Awal Per Tanggal " .$user->employee_date_from;
				
				$updated = $balanced->findOne(['employee_id' => $user->employee_id,'leave_balance_date' => $active_date]);
				if($update) {
					$balanced = $updated;
					$balanced->update();
				} else {
					$balanced->insert();
				}
				$count++;
			}
			Yii::$app->session->setFlash('message','<div class="alert alert-success"> <strong>'.Yii::t('app','success').'! </strong>'.Yii::t('app/message','msg request has been saved').'</strong></div>');
			return $this->redirect("report/balanced-card",301);
			
		}
		
		return $this->render('beginning-balance',[
				'title' => Yii::t('app','bulk beginning balance'),
				'users' => $users,
				//'model' => $model,
				//'dataProvider' => $model->getOutstandingLeaveDataProvider(Yii::$app->request->queryParams)
		]);
	}

	


}