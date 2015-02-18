<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Employee;


/**
 * Site controller
 */
class SiteController extends Controller
{
    public $class="Site";
   	
    public function actions(){
        
    }
	
    public function actionIndex(){
        return $this->redirect(['site/login']);
    }

    public function actionLogin(){
        
	$model = new Employee(['scenario'=>'login']);
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->getLogin()) {
	    $helpdesk = new \app\models\Helpdesk();
	    $helpdesk = $helpdesk->find()
	    ->where(['employee_id'=>Yii::$app->user->getId()])
	    ->one();
	    if(count($helpdesk)>0)
		Yii::$app->session->set('helpdesk',Yii::$app->user->getId()); 
	    return $this->redirect(['leave/index']);
        } 
        return $this->render('login',[
            'model' => $model,        ]);
    }
    
    public function actionLogout(){
        Yii::$app->user->logout();
	Yii::$app->session->set('helpdesk','');
        $this->redirect(['site/login'],301);
    }
    
    public function actionError(){
	echo 'ERROR 404';
    }
    
    public function actionTcpdf(){
        return $this->render('tcpdf');
    }
}
