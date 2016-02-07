<?php
/**
 * Class AppLeave Controller
 * Approved/InApproved by Manager or Partner
 */

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Employee;
use app\models\Leaves;
use app\models\LeaveLog;

class AppLeaveController extends Controller {
	/**
	 *  Action My Leave
	 * @return string
	 */
	public function actionIndex() {
		$model = new \app\models\Leaves(['scenario' => 'search']);
		if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
	
		}
		return $this->render('index',[
				'title' => Yii::t('app','to be approval'),
				'model' => $model,
				'dataProvider' => $model->getAppLeaveDataProvider(Yii::$app->request->queryParams)
		]);
	}
	
	/**
	 * Action App Leave Form
	 * Proses to Form CRUD
	 * @return \yii\web\Response|string
	 */
	public function actionForm($id) {
		$model = new Leaves(['scenario' => 'approval']);
		$app_model = $model->getDetailView($id);
		if($model->load(Yii::$app->request->post()) && $model->getApprovalLeaveRequest($id)){
			Yii::$app->session->setFlash('message','<div class="alert alert-success"> <strong>'.Yii::t('app','success').'! </strong>'.Yii::t('app/message','msg request has been approved').'</strong></div>');
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
	
}