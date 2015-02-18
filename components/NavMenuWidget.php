<?php
namespace app\components;
use yii;
use yii\helpers\Url;

class NavMenuWidget extends \yii\base\Widget{
    
    public $menu = [];
    
    public function init(){
        parent::init();
    }
    
    public function getMenu(){
        $result = '<ul id="side" class="nav navbar-nav side-nav">';
        foreach($this->menu as $nav){
	    if(isset($nav['sub'])){
                $result.='<li class="panel">';
		$result.='<a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle" data-target="#'.$nav['label'].'">';
		$result.='<i class="'.$nav['icon'].'"></i>'.$nav['label'].'<span class="fa arrow"></span>';
                $result.='</a>';
		$result.='<ul class="collapse in nav '.(Yii::$app->controller->class==$nav['label']?'in':'').'" id="'.$nav['label'].'">';
		foreach($nav['sub'] as $nav_sub){
		    $result.='<li><a '.(Yii::$app->request->url==Url::to([$nav_sub['url']])?'class="active"':'').' href="'.Url::to([$nav_sub['url']]).'"><i class="'.$nav_sub['icon'].'"></i>'.$nav_sub['label'].'</a></li>';						
		} 
                $result.='</ul>';	
		$result.='</li>';					   
	    } else { 
                $result.='<li><a '.(Yii::$app->request->url==Url::to([$nav['url']])?'class="active"':'').' href="'.Url::to([$nav['url']]).'"> <i class="'.$nav['icon'].'"></i> '.$nav['label'].'</a></li>';
	    }
        } 
        $result.='</ul>';
        return $result;
    }
    
    public function run(){
	return $this->getMenu();
    }
    
}