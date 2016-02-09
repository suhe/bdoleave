<?php
/**
 * Class MyLeave Controller
 * List History of My Leave
 */
namespace app\controllers;

use yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\Role;
use app\models\Employee;
use app\models\Leaves;
use app\models\LeaveLog;



class MyLeaveController extends Controller {
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
            	'only' => ['index', 'form', 'detail-view','balanced-card'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'form','detail-view','balanced-card'],
                        'roles' => [
                        	Employee::ROLE_ASSISTANT,
                        	Employee::ROLE_SENIOR_1,
                        	Employee::ROLE_SENIOR_2,
                        	Employee::ROLE_MANAGER,
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
	
	/**
	 * This command echoes what you have email.
	 * @param email
	 * //cron job php -q /home/k0455101/public_html/devleave/yii app-leave/email
	 */
	public function actionEmail() {
		if(Yii::$app->params['send_email'] == true) {
			$apps = Leaves::getAppLeave();
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
						
					$employee_id = 10406;
						
					$employee = Employee::findOne($employee_id);
					if($employee) {
						$email_receiver = $employee ? $employee->EmployeeEmail : "";
						if($email_receiver) {
							$mail[]  = Yii::$app->mailer->compose('leave_form',['data' => $app])
							->setFrom(Yii::$app->params['mail_user'])
							->setTo("hendarsyahss@gmail.com")
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
	
}