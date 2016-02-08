<?php
namespace app\controllers;
use Yii;
use app\models\Employee;
use app\models\LeaveLog;
use app\models\Leaves;
use yii\web\Controller;

class ReportController extends Controller {
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
    		
    	}
    	return $this->render('balanced-card',[
    			'title' => Yii::t('app','balanced card'),
    			'model' => $model,
    			'dataProvider' => $model->getBalanceLeaveCardDataProvider(Yii::$app->request->queryParams)
    	]);
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