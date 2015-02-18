<?php
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
//security login
use yii\web\IdentityInterface;
use yii\base\NotSupportedException; //if not supported exception


class User extends ActiveRecord implements IdentityInterface {
    
    public $user_created;
    public $user_bulk;
    public $user_password_confirm;
    public $passtext_confirm;
    public $passtext_new;
    public $passtext_new_confirm;
    public $remember_me;
    private $_user=false;
    
    public static function tableName(){
        return 'sys_user';
    }

    public function rules(){
        return [
            [['passtext','passtext_confirm','passtext_new','passtext_new_confirm'],'required','on'=>'update_password'],
            [['passtext_confirm'], 'validatePasswordOldConfirm','on'=>'update_password'],
        ];
    }
    
    public function scenarios(){
	$scenarios = parent::scenarios();
	$scenarios['user_add'] = ['user_name','user_password'];//Scenario Values Only Accepted
	return $scenarios;
    }

    public function attributeLabels(){
        return [
            'passtext' => Yii::t('app','password old'),
            'passtext_confirm' => Yii::t('app','confirm password'),
	    'passtext_new' => Yii::t('app','new password'),
	    'passtext_new_confirm' => Yii::t('app','confirm password'),
        ];
    }
    
   
    public function getUserData($params)
    {
        $query = User::find()
        ->select(['u.user_id','u.user_name','u.user_email','u2.user_name as user_created','u.user_created_date'])
        ->from('user u')
        ->join('left join','user u2','u2.user_id = u.user_created_by');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        $dataProvider->setSort([
            'attributes' => [
                'user_name',
                'user_email',
                'user_created' => [
                    'asc' => ['u2.user_name' => SORT_ASC],
                    'desc' => ['u2.user_name' => SORT_DESC],
                    'label' => Yii::t('app/backend','created by'),
                    'default' => SORT_ASC
                ],
                
            ]
        ]);

        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'user_id' => $this->user_id,
        ]);
        $query->andFilterWhere(['like', 'u.user_name',  $this->user_name]);
        $query->andFilterWhere(['like', 'u.user_email', $this->user_email]);
        $query->andFilterWhere(['like', 'u2.user_name', $this->user_created]);
        return $dataProvider;
    }
    
    public function validatePasswordConfirm($attribute,$params){
        if($this->user_password_confirm!=$this->user_password)
            return $this->addError($attribute,Yii::t('app/message','msg not password same'));
    }
    
    public static function findByUsername($username){
        return static::findOne(['user_name' => $username]);
    }
    
    public function validateUsername($attribute,$params){
        if(static::findByUsername($this->user_name))
            return $this->addError($attribute,Yii::t('app/message','msg username has already'));
    }
    
    public function getSave(){
       $user = new User();
       $user->user_name  = $this->user_name;
       $user->user_email = $this->user_email;
       $user->user_password = Yii::$app->security->generatePasswordHash($this->user_password);
       $user->user_created_by = Yii::$app->user->getId();
       $user->user_created_date = date('Y-m-d H:m:s');
       $user->save();
       return true;
    }
    
    public function getUpdate($key){
       $user = new User();
       $user = $user->findOne($key);
       $user->user_email = $this->user_email;
       $user->user_updated_by = Yii::$app->user->getId();
       $user->user_updated_date = date('Y-m-d H:m:s');
       $user->update();
       return true;
    }
    
    public function getUpdatePassword($key){
       if($this->validate()){
	    $user = new User();
	    $user = $user->find()->where(['employee_id'=>$key])->one();
	    $user->passtext = $this->passtext_new;
	    $user->sysuser = Yii::$app->user->getId();
	    $user->sysdate = date('Y-m-d H:m:s');
	    $user->update();
	    return true;
       }
    }
    
    /**
     * Section Under Login Security
     **/
    public function getLogin(){
        if($this->validate())
            return Yii::$app->user->login($this->getUser(),$this->remember_me ? 3600 * 24 * 30 : 0);
        else return false;
    }
    
    /**
     * Finds user by [[user_name]]
     * @return User|null
     * getUser from getLogin
     */
    public function getUser(){
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->user_name);
        }
        return $this->_user;
    }
    
    public function validatePasswordOldConfirm($attribute,$params){
        if (!$this->hasErrors()) {
            if ($this->passtext != $this->passtext_confirm) {
                $this->addError($attribute, Yii::t('app/message','msg password old confirm not same'));
            }
        }
    }
    
    public function validatePasswordNewConfirm($attribute,$params){
        if (!$this->hasErrors()) {
            if ($this->passtext_new != $this->passtext_new_confirm) {
                $this->addError($attribute, Yii::t('app/message','msg password new confirm not same'));
            }
        }
    }
    
    
    /**
     * abstract model identity interface $app->user->login
     * findIdentity , findIdentityByAccessToken , getId , getAuthKey , validateAuthKey
     */
    
    public static function findIdentity($id){
        return static::findOne(['user_id' => $id]);
    }
    
    public static function findIdentityByAccessToken($token, $type = null){
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    public function getId(){
        return $this->getPrimaryKey();
    }
    
    public function getAuthKey(){
        return $this->user_auth_key;
    }
    
    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }
    
    /**
     * Validate Login Password
     * */
    
    public function validatePassword($password){
        return Yii::$app->security->validatePassword($password, $this->user_password);
    }
}
