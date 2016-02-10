<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\components\Role;
use app\models\Employee;
use app\models\Leaves;
use app\models\LeaveBalance;

class ReportController extends Controller {
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
				'only' => ['index', 'form','form-approval','remove-balanced-card'],
					'rules' => [
						[
							'allow' => true,
							'actions' => ['index', 'form','form-approval','remove-balanced-card'],
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
	
    public function actionIndex() {
        return $this->render('index',[
        		'title' => Yii::t('app','reports')
        	
        ]); 
    }
    
    /**
     *  Action Outstanding Leave
     * @return string
     */
    public function actionOutstanding() {
    	$model = new \app\models\Leaves(['scenario' => 'search']);
    	if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])) {
    		
    	}
    	return $this->render('outstanding',[
    			'title' => Yii::t('app','outstanding report'),
    			'model' => $model,
    			'dataProvider' => $model->getOutstandingLeaveDataProvider(Yii::$app->request->queryParams)
    	]);
    }
    
    /**
     * Outstanding Leave Details View
     * @param unknown $id
     * @return string
     */
    public function actionOutstandingView($id) {
    	$model = new \app\models\Leaves();
    	$app_model = $model->getDetailView($id);
    	return $this->render('outstanding-view',[
    			'title' => Yii::t('app','view leave'),
    			'id' => $id,
    			'employee' => Employee::getEmployee($app_model->employee_id),
    			'leave' => $app_model,
    			'logDataProvider' => LeaveLog::getLogLeaveDataProvider($id),
    	]);
    }
    
    /**
     *  Action Balanced Card 
     * @return string
     */
    public function actionBalancedCard() {
    	$model = new Leaves(['scenario' => 'search']);
    	if($model->validate() && $model->load(Yii::$app->request->queryParams)){
    		//search leave balance saldo
    		Yii::$app->getRequest()->getQueryParam($model->employee_id);
    		
    	}
    	return $this->render('balanced-card',[
    			'title' => Yii::t('app','balanced card'),
    			'model' => $model,
    			'dataProvider' => $model->getBalanceLeaveCardDataProvider(Yii::$app->request->queryParams)
    	]);
    }
    
    /** 
     * Delete Balanced Card\
     */
    public function actionRemoveBalancedCard($id) {
    	$del_command = LeaveBalance::findOne($id);
    	$del_command->delete();
    	if($del_command) {
    		Yii::$app->session->setFlash('message','<div class="alert alert-success"> <strong>'.Yii::t('app','success').'! </strong>'.Yii::t('app/message','msg request has been deleted').'</strong></div>');
    		return $this->redirect(Yii::$app->request->referrer,301);
    	}
    }
    
    /**
     * Action Balanced Summary
     */
    public function actionBalancedSummary(){
    	$model = new Employee(['scenario'=>'search']);
    	if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
    		//process here
    	}
    	return $this->render('balanced-summary',[
    			'title' => Yii::t('app','balanced summary'),
    			'model' => $model,
    			'dataProvider' => $model->getActiveEmployeeDataProvider(Yii::$app->request->queryParams)
    	]);
    }
    
    
}