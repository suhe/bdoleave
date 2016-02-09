<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Employee;

class AdministrationController extends Controller {
	public function action() {
		if(Yii::$app->user->isGuest) 
			return $this->redirect(['site/'],301);
	}
	
    public function actionGeneral(){
        $id = Yii::$app->user->getId();
        $query = Employee::findOne($id);
        $model = new Employee(['scenario'=>'update_myprofile']);
        if ($model->load(Yii::$app->getRequest()->post()) && $model->getUpdateMyProfile($id)){
            $query = Employee::findOne($id);
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg update account successfully'));
        }
        return $this->render('setting',[
            'model' => $model,
            'query' => $query,
            'tabPage' => 'setting_general'
        ]);
    }
    
    public function actionPassword(){
        $id = Yii::$app->user->getId();
        $model = new \app\models\User(['scenario'=>'update_password']);
        if ($model->load(Yii::$app->getRequest()->post()) && $model->getUpdatePassword($id)){
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg update password successfully'));
        }
        return $this->render('setting',[
            'model' => $model,
            'query' => 0,
            'tabPage' => 'setting_password'
        ]);
    }
    
    public function actionApproval(){
        $id = Yii::$app->user->getId();
        $query = Employee::findOne($id);
        $model = new Employee(['scenario'=>'update_myaccount']);
        if ($model->load(Yii::$app->getRequest()->post()) && $model->getUpdateMyApproval($id)){
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg update approval successfully'));
        }
        $query = Employee::findOne($id);
        //edit form
        $model->EmployeeLeaveSenior = $query->EmployeeLeaveSenior;
        $model->EmployeeLeaveManager = $query->EmployeeLeaveManager;
        $model->EmployeeLeaveHRD = $query->EmployeeLeaveHRD;
        $model->EmployeeLeavePartner = $query->EmployeeLeavePartner;
        //edit form
        return $this->render('setting',[
            'model' => $model,
            'query' => $query,
            'tabPage' => 'setting_approval'
        ]);
    }
}
