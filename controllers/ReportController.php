<?php
namespace app\controllers;
use yii;
use yii\web\Controller;

class ReportController extends Controller {
    
    public $class='Report';
    
    public function actions(){
        if(!Yii::$app->session->get('helpdesk')) return $this->redirect(['ticket_index']);
    }
    
    public function actionChart(){
        $model = new \app\models\Ticket(['scenario'=>'report']);
        if($model->load(Yii::$app->request->queryParams)){
            $model->refresh();
        }
        return $this->render('report_chart',[
            'model' =>  $model,
            'params'=> Yii::$app->request->queryParams,
        ]); 
    }
    
    
}