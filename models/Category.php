<?php
namespace backend\models;
use yii;
use yii\db\ActiveRecord;

class Category extends ActiveRecord {
    
    public $listData=[];
    
    public static function tableName(){
        return 'category';
    }
    
    public static function GetListData($parent){
        return $query = Category::find()
	->where(['category.category_parent_id' => $parent])
        ->orderBy('category.category_parent_id,category.category_id')
        ->asArray()
        ->all();
    }
    
    public  function GetDropdownListData($parent,$level){
       $query =  static::GetListData($parent);
       $nbsp='';
       $this->listData[0] = Yii::t('app/backend','no item');
       foreach ($query as $row){
	    for($i=1;$i<$level;$i++) $nbsp.='  -  ';
	    $this->listData[$row['category_id']] = $nbsp.$row['category_name'];
	    $this->GetDropdownListData($row['category_id'],$level+1);
        }
        return $this->listData;
        
    }
    
    
}
