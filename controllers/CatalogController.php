<?php
namespace backend\controllers;
use yii;
use yii\web\Controller;
use yii\data\Pagination;
use backend\models\Menu;
use backend\models\Menu1;
use backend\components\TableDataWidget;
use backend\models\Page;
use backend\models\Category;
use backend\components\Auth;

class CatalogController extends Controller{
    
    public $class;
    public $function;
    public $title;
    public $addButton=['caption'=>'','url'=>'','class'=>''];
    
    public function actions(){
        if(Yii::$app->user->isGuest) $this->redirect(['site/login'],302);
        $this->class = Yii::t('app/backend','catalog');
    }
    
    public function actionMenu(){
        if(!Auth::access('catalog/menu')) $this->redirect([Yii::$app->params['module_default']],302);
        $this->function = Yii::t('app/backend','menus');
        $this->title = Yii::t('app/backend','list');
        $menu = new Menu();
        if ($menu->load(Yii::$app->request->post()) && $menu->getMenuData())
            $model = $menu->getMenuData();
        else 
            $model = $menu->getMenuData();
        
        $countQuery = clone $model;
            $pages = new Pagination([
                'totalCount' => $countQuery->count(), // total data
                'pageSizeLimit' => [Yii::$app->params['start_page'],Yii::$app->params['per_page']] //set limit data from - to
             ]);
            $model = $model->offset($pages->offset) //get offset
            ->limit($pages->limit) // get limit
            ->all(); //show all tabel
    
        $table = TableDataWidget::widget([
            'model' => $model,
            'table_attr' => 'class="table table-bordered table-hover tc-table"',
            'checkbox' => true,
            'caption' => ['Label'=>'class="col-md-7"',
                          'Url'=>'class="col-md-3"',
                          'Order'=>'style="width:10px"',
                          'Pos'=>'class="col-md-1"'
                          ],
            'primary_key' => 'menu_id',
            'data' =>    ['menu_structure'=>'class=""',
                          'menu_url'=>'class=""',
                          'menu_order'=>'style=""',
                          'menu_position'=>'class=""'
            ],
            'actionForm'=> 'catalog/menu_bulk',
            'url'    => [
                'edit' => Auth::access('catalog/menu_edit')?'catalog/menu_edit':'',
                'delete'=>Auth::access('catalog/menu_delete')?'catalog/menu_delete':'',
            ],
        ]);
        
        //auth for add button
        if(Auth::access('catalog/menu_add')){
            $this->addButton = ['caption'=> Yii::t('app/backend','add new'),
                                'url' => 'catalog/menu_add',
                                'class' => 'btn btn-default btn-small'
            ];
        }
        return $this->render('menu',[
            'model' => $menu,
            'table' => $table,
            'pages' => $pages
        ]);
        
    }
    
    public function actionMenu_add(){
        if(!Auth::access('catalog/menu_add')) $this->redirect([Yii::$app->params['module_default']],302);
        $this->function = Yii::t('app/backend','menus');
        $this->title = Yii::t('app/backend','add new');
        $menu = new Menu();
        $page = new Page();
        $category = new Category();
        /** Save Data **/
        $menu->setScenario('save');
        if (($menu->load(Yii::$app->request->post())) && ($menu->getSave()))
            $this->redirect(['catalog/menu']);
        $this->addButton = ['caption'=> Yii::t('app/backend','go back'),
                            'url' => 'catalog/menu',
                            'class' => 'btn btn-default btn-small'
        ];
        return $this->render('menu_form',[
            'model' => $menu,
            'query' => 0,
            'dropdownPage' => $page->GetDropdownListData(),
            'dropdownCategory' => $category->GetDropdownListData(0,1),
            'dropdownMenu' => $menu->GetDropdownListData(0,1),
        ]
        );
    }
    
    public function actionMenu_edit($key){
        if(!Auth::access('catalog/menu_edit')) $this->redirect([Yii::$app->params['module_default']],302);
        $this->function = Yii::t('app/backend','menus');
        $this->title = Yii::t('app/backend','edit');
        $menu = new Menu();
        $page = new Page();
        $category = new Category();
        /** Save Data **/
        $menu->setScenario('save');
        if (($menu->load(Yii::$app->request->post())) && ($menu->getUpdate($key)))
            $this->redirect(['catalog/menu']);
        //get data from tabel menu
        $query = $menu->findOne($key);
        $this->addButton = ['caption'=> Yii::t('app/backend','go back'),
                            'url' => 'catalog/menu',
                            'class' => 'btn btn-default btn-small'
        ];
        return $this->render('menu_form',[
            'model' => $menu,
            'query' => $query,
            'dropdownPage' => $page->GetDropdownListData(),
            'dropdownCategory' => $category->GetDropdownListData(0,1),
            'dropdownMenu' => $menu->GetDropdownListData(0,1),
        ]
        );
    }
    
    public function actionMenu_delete($key){
        if(Auth::access('catalog/menu_delete')){ 
            $exists = Menu::findOne($key);
            if($exists){
                Menu::deleteAll('menu_id = :menu_id',[':menu_id' => $key]);
                Yii::$app->session->setFlash('msg',Yii::t('app/message','msg delete success'));
            }else{
                Yii::$app->session->setFlash('msg',Yii::t('app/message','msg data not valid'));
            }
            $this->redirect(['catalog/menu']);
        } else {
            $this->redirect([Yii::$app->params['module_default']],302);
        }
    }
    
    
}
