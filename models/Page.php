<?php
namespace backend\models;
use yii;
use yii\db\ActiveRecord;

class Page extends ActiveRecord{
    
    public static function tableName(){
        return 'page';
    }
    
    public function GetDropdownListData(){
        $query = Page::find()
        ->asArray()
        ->all();
        $arr=[];
        $arr[0] = Yii::t('app/backend','no item');
        foreach($query as $row){
            $arr[$row['page_id']] = $row['page_name'];
        }
        return $arr;
    }
    
}
