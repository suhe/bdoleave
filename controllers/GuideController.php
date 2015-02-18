<?php
namespace app\controllers;
use yii;


class GuideController extends \yii\web\Controller {
    public $class='Guide';
    
    public function actions(){
        if(Yii::$app->user->isGuest){
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg you must login'));
            return $this->redirect(['site/login']);
        }
    }
    
    public function actionIndex(){
        return $this->render('guide_index',[
        ]); 
    }
}