<?php
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;



class Helpdesk extends ActiveRecord {
    
    public $user_created;
    public $user_bulk;
    public $user_password_confirm;
    public $remember_me;
    private $_user=false;
    public $EmployeeFirstName;
    public $EmployeeMiddleName;
    public $EmployeeLastName;
    public $EmployeeID;
    public $employee_name;
    public $role;
    
    
    public static function tableName(){
        return 'helpdesk';
    }

    public function rules(){
        return [
	    [['EmployeeFirstName','role_id'],'safe','on'=>['search']],
	    [['employee_id','role_id'],'required','on'=>['insert']],
	    [['employee_id'],'validateEmployee','on'=>['insert']]
        ];    
    }
    
    public function attributeLabels(){
        return [
           'EmployeeID' => Yii::t('app','nik'),
	   'employee_id' => Yii::t('app','employee'),
	   'role_id' => Yii::t('app','role'),
	   'EmployeeFirstName' => Yii::t('app','name'),
	   'Role' => Yii::t('app','role'),
	   
        ];
    }
    
    public function  getHelpdeskRole($all=false){
	$role = [
	    1 => Yii::t('app','leader'),
	    2 => Yii::t('app','staff'),
	];
	
	if($all==true)
	$role[0] = Yii::t('app','all');
	return $role;
    }
   
    public function getHelpdeskData($params)
    {
        $query = Helpdesk::find()
        ->select(['*',"IF(role_id=1,'Leader','Staff') as role"])
        ->from('helpdesk h')
        ->join('left join','employee e','e.employee_id = h.employee_id');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);

        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'helpdesk_id' => $this->helpdesk_id,
        ]);
        $query->andFilterWhere(['like', 'e.EmployeeFirstname',  $this->EmployeeFirstName]);
        $query->andFilterWhere(['like', 'h.role_id', $this->role_id]);
       
        return $dataProvider;
    }

    
    public function getSave(){
       if($this->validate()){
	$model = new Helpdesk();
	$model->employee_id  = $this->employee_id;
	$model->role_id  = $this->role_id;
	$model->insert();
	return true;
       }
    }
    
    public function findByID($employee_id){
	return Helpdesk::find()
        ->where(['employee_id' => $employee_id])
	->one();
    }
    
    public function validateEmployee($attribute, $params){
        if (!$this->hasErrors()) {
            $user = $this->findByID($this->employee_id);
            if ($user) {
                $this->addError($attribute, Yii::t('app/message','msg employee has been use'));
            }
        }
    }
    
    public static function getDropdownListData($all=true){
	$query = Helpdesk::find()
        ->select(['*',"IF(role_id=1,'Leader','Staff') as role"])
        ->from('helpdesk h')
        ->join('inner join','employee e','e.employee_id = h.employee_id')
	->all();
	
	$arr=[];
	foreach($query as $row){
	    $arr[$row->employee_id] = $row->EmployeeFirstName.' '.$row->EmployeeMiddleName.' '.$row->EmployeeLastName;
	}
	if($all==true)
	$arr[0] = Yii::t('app','all');
	return $arr;
    }
}
