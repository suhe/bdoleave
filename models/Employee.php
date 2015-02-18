<?php
namespace app\models;
use yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Employee extends ActiveRecord implements IdentityInterface {
    
    public $passtext;
    public $remember_me;
    public $EmployeeName;
    public $_user=false;
    public $employee_language;
    public $employee_date_from;
    public $employee_date_to;
    public $entitlement_date;
    public $EmployeeLeaveOver;
    public $leave_status;
    
    public static function tableName(){
        return 'employee';
    }
    
    public function rules(){
        return [
            [['EmployeeID','passtext'],'string'],
            [['EmployeeID'],'required','message'=>Yii::t('app/message','msg identity not empty'),'on'=>'login'],
            [['passtext'],'required','message'=>Yii::t('app/message','msg password not empty'),'on'=>'login'],
            [['passtext'],'validatePassword','on'=>'login'],
            [['EmployeeFirstName'],'required','on'=>['update_account','update_myprofile']],
            [['EmployeeMiddleName'],'safe','on'=>['update_account','update_myprofile']],
            [['EmployeeLastName'],'safe','on'=>['update_account','update_myprofile']],
            [['EmployeeEmail'],'required','on'=>['update_account','update_myprofile']],
            [['EmployeeEmail'],'email','on'=>['update_account','update_myprofile']],
            [['EmployeeName'],'safe','on'=>'search'],
            [['employee_date_from'],'safe','on'=>'search'],
            [['employee_date_to'],'safe','on'=>'search'],
            [['EmployeeHireDate'],'safe','on'=>'update_account'],
            [['EmployeeLeavePartner'],'required','on'=>['update_account','update_myaccount']],
            [['EmployeeLeaveHRD'],'required','on'=>['update_account','update_myaccount']],
            [['EmployeeLeaveManager'],'safe','on'=>['update_account','update_myaccount']],
            [['EmployeeLeaveSenior'],'safe','on'=>['update_account','update_myaccount']],
            [['leave_status'],'safe','on'=>['search']]
        ];
    }
    
    public function attributeLabels(){
        return [
            'EmployeeID' => Yii::t('app','nik'),
            'EmployeeFirstName' => Yii::t('app','first name'),
            'EmployeeMiddleName' => Yii::t('app','middle name'),
            'EmployeeLastName' => Yii::t('app','last name'),
            'EmployeeEmail' => Yii::t('app','email'),
            'passtext' => Yii::t('app','password'),
            'EmployeeHireDate' => Yii::t('app','hire date'),
            'EmployeeTitle' => Yii::t('app','title'),
            'EmployeeLeaveDate' => Yii::t('app','entitlement date'),
            'EmployeeLeaveTotal' => Yii::t('app','Entitlement'),
            'EmployeeLeaveUse' => Yii::t('app','leave'),
            'EmployeeLeaveOver' => Yii::t('app','over'),
            'EmployeeLeaveSenior' => Yii::t('app','senior approval'),
            'EmployeeLeaveManager' => Yii::t('app','manager approval'),
            'EmployeeLeaveHRD' => Yii::t('app','hrd approval'),
            'EmployeeLeavePartner' => Yii::t('app','partner approval'),
            'leave_status' => Yii::t('app','Status'),
        ];
    }
    
    public static function getStringStatus($int){
        switch($int){
            case 0 : $string = 'Permanent';break;
            case 1 : $string = 'Kontrak';break;
            case 2 : $string = 'Outsource';    
        }
        return $string;
    }

    /**
     * Relations with User
     */
    public function getUser(){
        return $this->hasOne(User::className(),['employee_id'=>'employee_id']);
    }
    
    /**
     * Relations with Department
     */
    public function getDepartment(){
        return $this->hasOne(Department::className(),['department_id'=>'department_id']);
    }
    
    
    public static function getAllEmployee(){
        $Employee = Employee::find()
        ->select(["E.employee_id"])
        ->from('employee E')
        ->join('inner join','sys_user U','U.employee_id=E.employee_id')
        ->join('inner join','department D','D.department_id=E.department_id')
        ->where(['U.user_active'=>1])
        ->orderBy("E.EmployeeFirstName,E.EmployeeMiddleName,E.EmployeeLastName")
        ->all();
        return $Employee;
    }
    
    public function getEmployeeLeaveData($params){
        $query = Employee::find()
        ->select(["e.employee_id","CONCAT(e.EmployeeFirstname,' ',e.EmployeeMiddleName,' ',EmployeeLastName) as EmployeeName",
                  "e.EmployeeID","DATE_FORMAT(e.EmployeeHireDate,'%d/%m/%Y') as EmployeeHireDate","e.EmployeeTitle",
                  "DATE_FORMAT(EmployeeLeaveDate,'%d/%m/%Y') as EmployeeLeaveDate","IF(TO_DAYS(CURDATE())-TO_DAYS(EmployeeLeaveDate)>364,'Must Update','Updated') as leave_status",
                  "e.EmployeeLeaveTotal","e.EmployeeLeaveUse","(e.EmployeeLeaveTotal-e.EmployeeLeaveUse) as EmployeeLeaveOver"])
        ->from('employee e')
        ->join('inner join','sys_user u','u.employee_id=e.employee_id')
        //->orderBy('e.EmployeeFirstname,e.EmployeeMiddleName,e.EmployeeLastName')
        ->where(['u.user_active'=>1]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        
        //sorting role
        $dataProvider->setSort([
            'defaultOrder' => ['EmployeeName'=>SORT_ASC],
            'attributes' => [
                'EmployeeName' => [
                    'asc'   => ['EmployeeName' => SORT_ASC],
                    'desc'  => ['EmployeeName' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'leave_status' => [
                    'asc'   => ['leave_status' => SORT_ASC],
                    'desc'  => ['leave_status' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'EmployeeID',
                'EmployeeHireDate',
                'EmployeeLeaveDate',
                'EmployeeTitle',
                'EmployeeLeaveTotal',
                'EmployeeLeaveUse',
                'EmployeeLeaveOver',
            ]
        ]);
        
        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
        
        if($this->leave_status==1)
            $query->andFilterWhere(['>','(TO_DAYS(CURDATE())-TO_DAYS(EmployeeLeaveDate))',364]);
        elseif($this->leave_status==2)
            $query->andFilterWhere(['<','(TO_DAYS(CURDATE())-TO_DAYS(EmployeeLeaveDate))',365]);
        
        $query->andFilterWhere(['like', "CONCAT(e.EmployeeFirstname,' ',e.EmployeeMiddleName,' ',EmployeeLastName)",  $this->EmployeeName]);
        
        return $dataProvider;
        
    }
    
    
    
    /**
     * abstract model identity interface $app->user->login
     * findIdentity , findIdentityByAccessToken , getId , getAuthKey , validateAuthKey
    */
    
    public static function findIdentity($id){
        return static::findOne(['employee_id' => $id]);
    }
    
    public static function findIdentityByAccessToken($token, $type = null){
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    public function getId(){
        return $this->getPrimaryKey();
    }
    
    public function getAuthKey(){
        return $this->EmployeeTitle;
    }
    
    public function validateAuthKey($authKey){
        return $true;
    }
    
    public static function isHR(){
        $field = isset(Yii::$app->user->identity->employee_id)?Yii::$app->user->identity->employee_id:0;
        switch($field){
            case 10444 : $hrd = $field;break;
            case 10509 : $hrd = $field;break;
            default : $hrd = 0;break;    
        }
        if($hrd>0) return true;
        else return false;
    }
    
    /** End Of Identity
    */
    public function getLogin(){
        if($this->validate())
            return Yii::$app->user->login($this->getUserSingleData(),$this->remember_me ? 3600 * 24 * 30 : 0);
        else return false;
    }
    
    public function getUserSingleData(){
        if ($this->_user === false) {
            $this->_user = Employee::findByIdentity($this->EmployeeID,$this->passtext);
        }
        return $this->_user;
    }
    
    public static function findByIdentity($identity,$password){
        return Employee::find()
        ->select(["E.employee_id,E.EmployeeID,CONCAT(E.EmployeeFirstName,' ',E.EmployeeMiddleName,' ',E.EmployeeLastName) as EmployeeName"])
        ->from('employee E')
        ->join('inner join','sys_user su','su.employee_id=E.employee_id')
        ->where(['E.EmployeeID' => $identity,'su.passtext'=>$password])
        ->one();
    }
    
    
    public function validatePassword($attribute, $params){
        if (!$this->hasErrors()) {
            $user = $this->findByIdentity($this->EmployeeID,$this->passtext);
            if (!$user) {
                $this->addError($attribute,Yii::t('app/message','msg incorrect username or password'));
            }
        }
    }
    
    public static function getEmployeeDropdownList(){
        $arr = [];
        $data = Employee::find()
        ->from('employee e')
        ->join('inner join','sys_user su','su.employee_id=e.employee_id')
        ->where(['su.user_active'=>1])
        ->orderBy('e.EmployeeFirstname,e.EmployeeMiddleName,e.EmployeeLastName')
        ->all();
        
        $arr[0] = Yii::t('app','all employee');
        foreach($data as $row){
            $arr[$row->employee_id] = $row->EmployeeID.'-'.$row->EmployeeFirstName.' '.$row->EmployeeMiddleName.' '.$row->EmployeeLastName;
        }
        return $arr;
    }
    
    public static function getEmployeeGroupDropdownList($department_id=0,$acl){
        $arr = [];
        $query = Employee::find()
        ->from('employee e')
        ->join('inner join','sys_user su','su.employee_id=e.employee_id')
        ->where(['su.user_active'=>1])
        ->orderBy('e.EmployeeFirstname,e.EmployeeMiddleName,e.EmployeeLastName');
        
        if($acl=='041')
            $query->orWhere(['e.project_title' => '03','e.project_title' => '041']);
        else
            $query->andWhere(['e.project_title' => $acl]);
            
        $query = $query->all();
        
        $arr[0] = Yii::t('app','not set');
        foreach($query as $row){
            $arr[$row->employee_id] = $row->EmployeeFirstName.' '.$row->EmployeeMiddleName.' '.$row->EmployeeLastName;
        }
        return $arr;
    }
    
    public function getUpdateProfile($id){
        if($this->validate()){
            $model = new Employee();
            $model = $model->findOne($id);
            $model->EmployeeFirstName = $this->EmployeeFirstName;
            $model->EmployeeMiddleName = $this->EmployeeMiddleName;
            $model->EmployeeLastName = $this->EmployeeLastName;
            $model->EmployeeEmail = $this->EmployeeEmail;
            if($this->EmployeeHireDate)
               $model->EmployeeHireDate = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->EmployeeHireDate);
            $model->EmployeeLeaveSenior = $this->EmployeeLeaveSenior;
            $model->EmployeeLeaveManager = $this->EmployeeLeaveManager;
            $model->EmployeeLeaveHRD = $this->EmployeeLeaveHRD;
            $model->EmployeeLeavePartner = $this->EmployeeLeavePartner;
            $model->update();
            return true;
        }
    }
    
    public function getUpdateMyProfile($id){
        if($this->validate()){
            $model = new Employee();
            $model = $model->findOne($id);
            $model->EmployeeFirstName = $this->EmployeeFirstName;
            $model->EmployeeMiddleName = $this->EmployeeMiddleName;
            $model->EmployeeLastName = $this->EmployeeLastName;
            $model->EmployeeEmail = $this->EmployeeEmail;
            $model->update();
            return true;
        }
    }
    
     public function getUpdateMyApproval($id){
        if($this->validate()){
            $model = new Employee();
            $model = $model->findOne($id);
            $model->EmployeeLeaveSenior = $this->EmployeeLeaveSenior;
            $model->EmployeeLeaveManager = $this->EmployeeLeaveManager;
            $model->EmployeeLeaveHRD = $this->EmployeeLeaveHRD;
            $model->EmployeeLeavePartner = $this->EmployeeLeavePartner;
            $model->update();
            return true;
        }
    }
    
    public static function getEmployeeEmail($ticket_id){
        $query = \app\models\Ticket::findOne($ticket_id);
        if(!Yii::$app->session->get('helpdesk')){
            $user_id = $query->ticket_helpdesk;
        } else {
            $user_id = $query->employee_id;
        }
        
        $query2 = Employee::findOne($user_id);
        return $query2->EmployeeEmail;
    }
    
    public static function getEmployeeEmailById($id){
        $query = Employee::findOne($id);
        $email = $query->EmployeeEmail;
        if(!$email) return 'ssuhendar@bdo.co.id';
        return $email;
    }
    
    public static function getDropDownLeaveStatus($status=TRUE){
        $data[0] = Yii::t('app','all');
        $data[1] = Yii::t('app','must update');
        $data[2] = Yii::t('app','updated');
        return $data;
    }
    
}