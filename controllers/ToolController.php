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
		$tbl = new Employee(['scenario' => 'bulk_save']);
		$users = $tbl->getAllEmployee();
		$multipleModel = new Model();
		if (($multipleModel->loadMultiple($users, Yii::$app->request->post())) && ($multipleModel->validateMultiple($users))) {
			$count = 0;
			foreach($users as $user) {
				//only checklist will update
				if($user->employee_id == 1 ) {
					$active_date =  $user->employee_date_from ?  preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$user->employee_date_from) : date("Y-m-d");
					$display_date = $user->employee_date_from ? $user->employee_date_from : date('d/m/Y');
					$balanced = new LeaveBalance(['bulk_save']);
					$balanced->leave_balance_date = $active_date;
					$balanced->leave_balance_total = $user->EmployeeLeaveTotal;
					$balanced->leave_balance_description = "Saldo Awal Per Tanggal " .$display_date;
					//$updated = $balanced->findOne(['employee_id' => $user->employee_id,'leave_balance_date' => $active_date]);
					//if($updated) {
						//$balanced = $updated;
						//$balanced->update();
					//} else {
						$balanced->insert();
					//}
				}
				$count++;
			}
			
			//Yii::$app->session->setFlash('message','<div class="alert alert-success"> <strong>'.Yii::t('app','success').'! </strong>'.Yii::t('app/message','msg request has been saved').'</strong></div>');
			//return $this->redirect("report/balanced-card",301);
		}
		
		return $this->render('beginning-balance',[
				'title' => Yii::t('app','bulk beginning balance'),
				'users' => $users,
				//'model' => $model,
				//'dataProvider' => $model->getOutstandingLeaveDataProvider(Yii::$app->request->queryParams)
		]);
	}
	


}