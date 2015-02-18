<?php
namespace backend\models;
use yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use backend\models\RoleAuth;

class Role extends ActiveRecord{
    
    public $role_created;
    public $role_bulk;
    public $user_name;
    public $role_module;
    
    public static function tableName(){
        return 'role';
    }
    
    public function rules(){
        return [
            [['role_id','role_bulk','role_created_by','role_updated_by'],'integer'],
            [['role_name','role_created','role_created_date','role_updated_date'],'string'],
            [['role_name',],'required','on'=>'save'],
            [['role_name'],'validateRoleName','on'=>'save'],
            [['role_name'],'safe']
        ];
    }
    
   public function attributeLabels(){
        return [
            'role_id' => 'Role ID',
            'role_name' => Yii::t('app/backend','role'),
            'role_created_by' => Yii::t('app/backend','created by'),
            'role_created_date' => Yii::t('app/backend','created date'),
            'role_updated_by' => Yii::t('app/backend','updated by'),
            'role_updated_date' => Yii::t('app/backend','update date'),
        ];
    }
    
    public function getUser(){
        return $this->hasOne(User::className(),['user_id'=>'role_created_by']);
    }
    
    public function getRoleData($params){
        $query = Role::find()
        ->select(['r.role_id','r.role_name','u.user_name as role_created','r.role_created_date'])
        ->from('role r')
        ->join('left join','user u','u.user_id = r.role_created_by');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        //sorting role
        $dataProvider->setSort([
            'attributes' => [
                'role_name',
                'role_created' => [
                    'asc'   => ['u.user_name' => SORT_ASC],
                    'desc'  => ['u.user_name' => SORT_DESC],
                    'label' => Yii::t('app/backend','created by'),
                    'default' => SORT_ASC
                ],
                
            ]
        ]);

        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'r.role_name',  $this->role_name]);
        $query->andFilterWhere(['like', 'u.user_name',  $this->role_created]);
        return $dataProvider;
    }
    
    public static function findByRoleName($name){
        return static::findOne(['role_name' => $name]);
    }
    
    public function validateRoleName($attribute, $params){
        $user = Role::findByRoleName($this->role_name);
        if($user)
            $this->addError($attribute,Yii::t('app/message','msg role name registered'));
    }
    
    public function getSave(){
        $role = new Role();
        $role->role_id = $this->role_id;
        $role->role_name = $this->role_name;
        $role->role_created_by = Yii::$app->user->getId();
        $role->role_created_date = date('Y-m-d H:m:s');
        $role->save();
        
        //save RoleAuth
        $post = Yii::$app->request->post();
        $total = count($post);
        for($i=0;$i<$total;$i++){
            $auth = new RoleAuth();
            $auth->role_id = $role->role_id;
            $auth->module_name = $post['Role']['role_module'][$i];
            $auth->save();
        }
        return true;
    }
    
    public function getUpdate($key){
        $role = new Role();
        $role = $role->findOne($key);
        $role->role_updated_by = Yii::$app->user->getId();
        $role->role_updated_date = date('Y-m-d H:m:s');
        $role->update();
        
        //delete Role Auth
        RoleAuth::deleteAll('role_id = :role_id',[':role_id' => $key]);
        //save RoleAuth
        $post = Yii::$app->request->post();
        $total = count($post);
        for($i=0;$i<$total;$i++){
            $auth = new RoleAuth();
            $auth->role_id = $key;
            $auth->module_name = $post['Role']['role_module'][$i];
            $auth->save();
        }
        return true;
    }
    
}