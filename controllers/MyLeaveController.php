<?php
/**
 * Class MyLeave Controller
 * List History of My Leave
 */
namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Employee;
use app\models\Leaves;
use app\models\LeaveLog;

class MyLeaveController extends Controller {
	
	/**
	 *  Action My Leave
	 * @return string
	 */
	public function actionIndex() {
		$model = new Leaves(['scenario' => 'search']);
		if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
	
		}
		return $this->render('index',[
				'title' => Yii::t('app','my leave'),
				'model' => $model,
				'dataProvider' => $model->getMyLeaveDataProvider(Yii::$app->request->queryParams)
		]);
	}
	
	/**
	 * Action My Leave Formulir
	 * Proses to Form CRUD
	 * @return \yii\web\Response|string
	 */
	public function actionForm() {
		$model = new Leaves(['scenario' => 'add_myleave']);
		if($model->load(Yii::$app->request->post()) && $model->getSaveLeaveRequest(Yii::$app->user->getId())){
			Yii::$app->session->setFlash('message','<div class="alert alert-success"> <strong>'.Yii::t('app','success').'! </strong>'.Yii::t('app/message','msg request has been created').'</strong></div>');
			return $this->redirect(['my-leave/index'],301);
		}
		
		return $this->render('form',[
				'title' => Yii::t('app','leave form'),
				'employee' => Employee::getEmployee(Yii::$app->user->getId()),
				'model' => $model,
		]);
	}
	
	/**
	 * Leave Details View
	 * @param unknown $id
	 * @return string
	 */
	public function actionDetailView($id) {
		$model = new Leaves();
		$app_model = $model->getDetailView($id);
		return $this->render('detail-view',[
				'title' => Yii::t('app','my leave'),
				'id' => $id,
				'employee' => Employee::getEmployee(Yii::$app->user->getId()),
				'leave' => $app_model,
				'logDataProvider' => LeaveLog::getLogLeaveDataProvider($id),
		]);
	}
	
	/**
	 *  Action Balanced Card
	 * @return string
	 */
	public function actionBalancedCard() {
		$model = new Leaves();
		return $this->render('balanced-card',[
				'title' => Yii::t('app','balanced card'),
				'model' => $model,
				'dataProvider' => $model->getMyBalanceLeaveCardDataProvider()
		]);
	}
	
}