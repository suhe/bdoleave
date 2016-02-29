<?php
/**
* Class Employee Controller
* List Master Data of Employee
* Beginning Balance
* Approval Setting
*/
namespace app\controllers;


use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\Role;
use app\models\Employee;


class EmployeeController extends Controller {
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
				'only' => ['index', 'form','form-approval'],
					'rules' => [
						[
							'allow' => true,
							'actions' => ['index', 'form','form-approval'],
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
	 * Action Index List of Employee
	 */
	public function actionIndex(){
		$model = new \app\models\Employee(['scenario'=>'search']);
		if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
			//process here
		}
		return $this->render('index',[
				'title' => Yii::t('app','employee profile'),
				'model' => $model,
				'dataProvider' => $model->getActiveEmployeeDataProvider(Yii::$app->request->queryParams)
		]);
	}
	
	/**
	 * Form Personal
	 * @param unknown $id
	 * @return \yii\web\Response|string
	 */
	public function actionForm($id){
		$model = new Employee(['scenario'=>'update_personal']);
		if($model->load(Yii::$app->request->post()) && $model->getUpdateProfile($id) ){
			Yii::$app->session->setFlash('message','<div class="notice bg-success marker-on-left">'.Yii::t('app/message','msg update account has been successfully').'</div>');
			return $this->redirect(['employee/form','id'=>$id],301);
		}
		//$query = $model->findOne($id);
		$data = $model->getEmployee($id);
		/** 
		 * Active Form Load
		 */
		$model->EmployeeID = $data->EmployeeID;
		$model->EmployeeFirstName = $data->EmployeeFirstName;
		$model->EmployeeMiddleName = $data->EmployeeMiddleName;
		$model->EmployeeLastName = $data->EmployeeLastName;
		$model->EmployeeEmail = $data->EmployeeEmail;
		$model->EmployeeHireDate = $data->EmployeeHireDate;  
		$model->EmployeeHandPhone = $data->EmployeeHandPhone;
		return $this->render('form-personal',[
				'title' => Yii::t('app','personal'),
				'model' => $model,
				'query' => $data,
		]);
	}
	
	/**
	 * Form Personal
	 * @param unknown $id
	 * @return \yii\web\Response|string
	 */
	public function actionFormApproval($id){
		$model = new Employee(['scenario'=>'update_personal_approval']);
		$data = $model->getEmployee($id);
		
		if($model->load(Yii::$app->request->post()) && $model->getUpdateProfileApproval($id) ){
			Yii::$app->session->setFlash('message','<div class="notice bg-success marker-on-left">'.Yii::t('app/message','msg update account has been successfully').'</div>');
			return $this->redirect(['employee/form-approval','id'=>$id],301);
		}
		
		/**
		 * Active Form Load
		 */
		$model->EmployeeID = $data->EmployeeID;
		$model->EmployeeLeaveManager = $data->EmployeeLeaveManager;
		$model->EmployeeLeaveHRD = $data->EmployeeLeaveHRD;
		$model->EmployeeLeavePartner = $data->EmployeeLeavePartner;
		return $this->render('form-approval',[
				'title' => Yii::t('app','employee approval'),
				'model' => $model,
				'query' => $data,
		]);
	}
	
	
}