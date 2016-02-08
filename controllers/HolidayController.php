<?php
namespace app\controllers;
use yii;
use app\models\Holiday;


class HolidayController extends \yii\web\Controller {
    /**
     * List Index of Holiday List
     * return dataProvider
     */
	public function actionIndex(){
        $model = new Holiday(['scenario' => 'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('index',[
        		'title' => Yii::t('app','holiday list'),
	            'model' => $model,
	            'dataProvider' => $model->getAllDataProvider(Yii::$app->request->queryParams)
        ]); 
    }
    
    /**
     * Action Form
     * for CRUD Database Binding
     * @param number $id
     */
    public function actionForm($id = 0){
        $model = new \app\models\Holiday(['scenario' => $id ? 'update' : 'save']);
        $query = $model->findOne($id);
        if($model->load(Yii::$app->request->post()) && $model->getSaveRequest($id)){
        	Yii::$app->session->setFlash('message','<div class="alert alert-success"> <strong>'.Yii::t('app','success').'! </strong>'.Yii::t('app/message','msg request has been created').'</strong></div>');
            return $this->redirect(['holiday/index'],301);
        }
    	
        $model->holiday_date = $query ?  Yii::$app->formatter->asDatetime($query->holiday_date, "php:d/m/Y") : "";
        $model->holiday_type = $query ? $query->holiday_type : "";
        $model->holiday_desc = $query ? $query->holiday_desc : "";
        return $this->render('form',[
            'title' => Yii::t('app','holiday form'),
            'model' => $model,
            'query' => $query,
        ]);    
    }
    
    public function actionView($id){
        $model = new \app\models\Holiday();
        return $this->render('holiday_view',[
            'title' => Yii::t('app','holiday view'),
            'model' => $model->findOne($id)
        ]);    
    }
    
    
    public function actionEdit($id){
        $model = new \app\models\Holiday(['scenario' => 'update']);
        if($model->load(Yii::$app->request->post()) && $model->getUpdateData($id)){
            return $this->redirect(['holiday/index'],301);
        }
        $query = $model->findOne($id);
        $model->holiday_date = $query->holiday_date;
        $model->holiday_desc = $query->holiday_desc; 
        return $this->render('holiday_form',[
            'title' => Yii::t('app','holiday form'),
            'model' => $model,
            'query' => $query,
        ]);    
    }
    
    public function actionDelete($id){
        $exists = \app\models\Holiday::findOne($id);
        if($exists){
            \app\models\Holiday::deleteAll('holiday_id = :id',[':id' => $id]);
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg delete success'));
        }else{
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg data not valid'));
        }
        return $this->redirect(['holiday/index'],301);
    }
    
    
    
}