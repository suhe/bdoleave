<?php
namespace backend\models;
use Yii;
use yii\db\ActiveRecord;
use common\helpers\Seo;
use backend\models\Page;
use backend\models\Category;

class Menu extends ActiveRecord {
    
    public $menu_default;
    public $listData = [];
    public $structmenu;
   
    /**
     * table name in database 
     */
    public static function tableName() {
        return 'menu';
    }

    public function rules(){
        return [
            [['menu_label','menu_order','menu_position'], 'required','on'=>'save'],
            [['menu_label','menu_position'], 'string'],
            [['menu_order','menu_default','page_id','category_id','menu_parent_id'],'number'],
            [['category_id'],'validateCategory'], // if default == category
            [['page_id'],'validatePage'], // if default == page
        ];
    }
    
    public function attributeLabels(){
        return [
          'menu_label' => Yii::t('app/backend','label'),
          'menu_url'   => Yii::t('app/backend','url'),
          'menu_order' => Yii::t('app/backend','order'),
          'menu_parent_id' => Yii::t('app/backend','parent'),
          'category_id' => Yii::t('app/backend','category'),
          'page_id'     => Yii::t('app/backend','page'),
          'menu_default' => Yii::t('app/backend','default'),
          'menu_position' => Yii::t('app/backend','position'),
        ];
    }
    
    public function getMenuData(){
        $model = Menu::find();
        if($this->menu_label)
        $model = $model->where(['like','menu_label',$this->menu_label]);
        if($this->menu_url)
        $model = $model->where(['like','menu_url',$this->menu_url]);
        return $model; 
    }
    
    public static function GetListData($parent){
        return $query = Menu::find()
	->where(['menu.menu_parent_id' => $parent])
        ->orderBy('menu.menu_parent_id,menu.menu_order')
        ->asArray()
        ->all();
    }
    
    public function GetDropdownListData($parent,$level){
       $query =  static::GetListData($parent);
       $nbsp='';
       $this->listData[0] = Yii::t('app/backend','root');
       foreach ($query as $row){
	    for($i=1;$i<$level;$i++) $nbsp.='  -  ';
	    $this->listData[$row['menu_id']] = $nbsp.$row['menu_label'];
	    $this->GetDropdownListData($row['menu_id'],$level+1);
        }
        return $this->listData;
        
    }
    
    public function GetStructureParentSingleData($parent,$level=1){
        if($level==1) $this->structmenu = $this->menu_label;
        $row = Menu::find()->where(['menu_id' => $parent])->one();
        if($row){
            $this->structmenu = $row->menu_label.' / '.$this->structmenu;
            $this->GetStructureParentSingleData($row['menu_parent_id'],$level+1);
        }
        return $this->structmenu;
    }
    
    public function getSave(){
	if($this->validate()){
            //for menu url from category url / page url 
            if($this->category_id){
                $row = Category::find()->where(['category_id'=>$this->category_id])->one();
                $menu_url = $row['category_url'];
            }
            else if($this->page_id){
                $row = Page::find()->where(['page_id'=>$this->page_id])->one();
                $menu_url = $row['page_url'];
            }
            //for menu url from category url / page url 
            $menu = new Menu();
            $menu->menu_label = $this->menu_label;
            $menu->menu_order = $this->menu_order;
            $menu->menu_created_by =Yii::$app->user->getId();
            $menu->menu_created_date = date('Y-m-d H:m:s');
            $menu->page_id = $this->page_id?$this->page_id:0;
            $menu->category_id= $this->category_id?$this->category_id:0;
            $menu->menu_url = $menu_url;
            $menu->menu_position = $this->menu_position;
            $menu->menu_parent_id = $this->menu_parent_id;
            $menu->menu_slug = Seo::slug($this->menu_label);
            $menu->menu_structure = $this->GetStructureParentSingleData($this->menu_parent_id);
            $menu->save();
            return true;
       }
    }
    
    public function getUpdate($key){
	if($this->validate()){
            //for menu url from category url / page url 
            if($this->category_id){
                $row = Category::find()->where(['category_id'=>$this->category_id])->one();
                $menu_url = $row['category_url'];
            }
            else if($this->page_id){
                $row = Page::find()->where(['page_id'=>$this->page_id])->one();
                $menu_url = $row['page_url'];
            }
            //for menu url from category url / page url 
            $menu = new Menu();
            $menu = $menu->findOne($key);
            $menu->menu_label = $this->menu_label;
            $menu->menu_order = $this->menu_order;
            $menu->menu_updated_by = Yii::$app->user->getId();
            $menu->menu_updated_date = date('Y-m-d H:m:s');
            $menu->page_id = $this->page_id?$this->page_id:0;
            $menu->category_id= $this->category_id?$this->category_id:0;
            $menu->menu_url = $menu_url;
            $menu->menu_position = $this->menu_position;
            $menu->menu_parent_id = $this->menu_parent_id;
            $menu->menu_slug = Seo::slug($this->menu_label);
            $menu->menu_structure = $this->GetStructureParentSingleData($this->menu_parent_id);
            $menu->update();
            return true;
       }
    }
    
    
    public function validatePage(){
        if($this->menu_default==1){
            if(!$this->page_id)
            return $this->addError('page_id','Page cannot blank where choose default to page');
        }
    }
    
    public function validateCategory(){
        if($this->menu_default==2){
            if(!$this->category_id )
            return $this->addError('category_id','Category cannot blank where choose default to category');
        }
    }
    
    
    
}
