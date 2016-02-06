<?php
namespace app\controllers;
use yii;


class HolidayController extends \yii\web\Controller {
    public $class='Holiday';
    
    public function actions(){
        
    }
    
    public function actionIndex(){
        $model = new \app\models\Holiday(['scenario' => 'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('holiday_index',[
            'model' => $model,
            'dataProvider' => $model->getHolidayData(Yii::$app->request->queryParams)
        ]); 
    }
    
    public function actionNew(){
        $model = new \app\models\Holiday(['scenario' => 'save']);
        if($model->load(Yii::$app->request->post()) && $model->getSaveData()){
            return $this->redirect(['holiday/index'],301);
        }
    
        return $this->render('holiday_form',[
            'title' => Yii::t('app','holiday form'),
            'model' => $model,
            'query' => 0
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