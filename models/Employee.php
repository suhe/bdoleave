<?php
namespace app\models;
use yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Employee extends ActiveRecord implements IdentityInterface {
    public $employee_name;
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
    public $manager_approval;
    public $manager_email;
    public $hrd_approval;
    public $hrd_email;
    public $partner_approval;
    public $partner_email;
    public $checklist;
    
    /** 
     * Costantata Employee Role
     * @return cost
     * KAP TSFR
     */
    const ROLE_ASSISTANT = "Assistant";
    const ROLE_SENIOR_2 = "Senior-2";
    const ROLE_SENIOR_1 = "Senior-1";
    const ROLE_MANAGER = "Manager";
    const ROLE_PARTNER = "Partner";
    
    
    /**
     * Costantata Employee Role
     * @return cost
     * PT BDO KI
     */
    const ROLE_CONSULTANT = "Consultant";
    const ROLE_ASS_CONSULTANT = "Associate Consultant";
    const ROLE_ASS_SENIOR_CONSULTANT = "Sr Associate Consultant";
    const ROLE_SENIOR_CONSULTANT = "Senior Consultant";
    const ROLE_MANAGER_ADVISORY = "Manager Advisory";
    
    
    /**
     * Costantata Employee Role
     * @return cost
     * Support & BDO Manajemen Indonesia
     */
    const ROLE_SENIOR = "Senior";
    const ROLE_SUPERVISOR = "Supervisor";
    const ROLE_MANAGER_HRD = "Manager HRD";
    const ROLE_SENIOR_HRD = "Senior HRD";
    
    public static function tableName(){
        return 'employee';
    }
    
    public function rules(){
        return [
            [['EmployeeID','passtext'],'string'],
            [['EmployeeID'],'required','message'=>Yii::t('app/message','msg identity not empty'),'on'=>'login'],
            [['passtext'],'required','message'=>Yii::t('app/message','msg password not empty'),'on'=>'login'],
            [['passtext'],'validatePassword','on'=>'login'],
            [['EmployeeFirstName'],'required','on'=>['update_personal','update_myprofile']],
            [['EmployeeMiddleName'],'safe','on'=>['update_personal','update_myprofile']],
            [['EmployeeLastName'],'safe','on'=>['update_personal','update_myprofile']],
            [['EmployeeEmail'],'required','on'=>['update_personal','update_myprofile']],
            [['EmployeeEmail'],'email','on'=>['update_personal','update_myprofile']],
        	[['EmployeeHandPhone'],'required','on'=>['update_personal']],
            [['employee_name'],'safe','on'=>'search'],
            [['employee_date_from'],'safe','on'=>['search','bulk_save']],
            [['employee_date_to'],'safe','on'=>'search'],
            [['EmployeeHireDate'],'required','on'=>'update_personal'],
            [['EmployeeLeavePartner'],'required','on'=>['update_personal_approval','update_myaccount']],
            [['EmployeeLeaveHRD'],'required','on'=>['update_personal_approval','update_myaccount']],
            [['EmployeeLeaveManager'],'safe','on'=>['update_personal_approval','update_myaccount']],
        	[['EmployeeLeaveTotal'],'required','on'=>['bulk_save']],
        	[['checklist'],'safe','on'=>['bulk_save']],
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
        	'EmployeeHandPhone' => Yii::t('app','handphone'),
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
    
    /** 
     * Get Dropdown List
     * 
     */
    
    /**
     * Get Employee List from Dropdown
     * @param array $data
     */
    public static function getEmployeeList($data = array()){
    	$lists = [];
    	$models = Employee::find()
    	->from('employee e')
    	->join('inner join','sys_user su','su.employee_id=e.employee_id')
    	->orderBy('e.EmployeeFirstname ASC,e.EmployeeMiddleName ASC,e.EmployeeLastName ASC')
    	->where(['su.user_active'=>1]);
    	
    	if(isset($data['position']))
    		$models = $models->where(['EmployeeTitle' => $data['position']]);
    	$models = $models->all();
    	
    	
    	if(isset($data['label'])) 
    		$lists[0] = $data['label'];
    	
    	if(isset($data['all_employee']))
    		$lists[1] = Yii::t('app','all employee');
    	
    	foreach($models as $row){
    		$lists[$row->employee_id] = $row->EmployeeFirstName.' '.$row->EmployeeMiddleName.' '.$row->EmployeeLastName.' '.$row->EmployeeID;
    	}
    	return $lists;
    }
    
    /**
     * End of Dropdown List
     *
     */
    
    
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
        ->select(["E.employee_id","E.EmployeeID","DATE_FORMAT(E.EmployeeHireDate,'%d/%m/%Y') EmployeeHireDate","E.EmployeeLeaveDate",
        	"CONCAT(E.EmployeeFirstName,' ',EmployeeMiddleName,' ',EmployeeLastName) as employee_name","E.EmployeeTitle"
        ])
        ->from('employee E')
        ->join('inner join','sys_user U','U.employee_id=E.employee_id')
        ->where("U.user_active = 1 AND E.EmployeeLeaveIgnore = 0 ")
        ->orderBy("E.EmployeeFirstName,E.EmployeeMiddleName,E.EmployeeLastName")
        //->limit(5)
        ->all();
        return $Employee;
    }
    
    /**
     * Get Single Data From Employee 
     * @param unknown $employee_id
     * @return boolean
     */
    public static function getEmployee($employee_id){
    	$Employee = Employee::find()
    	->select(["e.*","CONCAT(em.EmployeeFirstName,' ',em.EmployeeLastName) as manager_approval","em.EmployeeEmail as manager_email",
    	"DATE_FORMAT(e.EmployeeHireDate,'%d/%m/%Y') as EmployeeHireDate",		
    	"CONCAT(eh.EmployeeFirstName,' ',eh.EmployeeLastName) as hrd_approval","eh.EmployeeEmail as hrd_email",	
    	"CONCAT(ep.EmployeeFirstName,' ',ep.EmployeeLastName) as partner_approval","ep.EmployeeEmail as partner_email"])
    	->from('employee e')
    	->join('left join','employee em','em.employee_id=e.EmployeeLeaveManager')
    	->join('left join','employee eh','eh.employee_id=e.EmployeeLeaveHRD')
    	->join('left join','employee ep','ep.employee_id=e.EmployeeLeavePartner')
    	->where(['e.employee_id' => $employee_id])
    	->one();
    	return $Employee;
    }
    
    /**
     *  Get Employee and set to One Parameter from Field
     * @param unknown $employee_id
     * @param unknown $field_name
     * @return boolean
     */
    public static function getField($employee_id,$field_name) {
    	$employee = Employee::findOne($employee_id);
    	if($employee)
    		return $employee->$field_name;
    	else 
    		return false;
    }
    
    /**
     * Active Employee Data Provider
     * @param unknown $params
     * @return \yii\data\ActiveDataProvider
     */
    public function getActiveEmployeeDataProvider($params){
        $query = Employee::find()
        ->select(["e.employee_id","CONCAT(e.EmployeeFirstname,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) as EmployeeName",
                  "e.EmployeeID","DATE_FORMAT(e.EmployeeHireDate,'%d/%m/%Y') as EmployeeHireDate","e.EmployeeTitle",
                  "DATE_FORMAT(e.EmployeeLeaveDate,'%d/%m/%Y') as EmployeeLeaveDate",
                  "e.EmployeeLeaveTotal","e.EmployeeLeaveUse","(e.EmployeeLeaveTotal - e.EmployeeLeaveUse) as EmployeeLeaveOver",
        		"CONCAT(em.EmployeeFirstName,' ',em.EmployeeLastName) as manager_approval",
        		"CONCAT(eh.EmployeeFirstName,' ',eh.EmployeeLastName) as hrd_approval",	
    			"CONCAT(ep.EmployeeFirstName,' ',ep.EmployeeLastName) as partner_approval"
        ])
        ->from('employee e')
        ->join('inner join','sys_user u','u.employee_id=e.employee_id')
        ->join('left join','employee em','em.employee_id=e.EmployeeLeaveManager')
        ->join('left join','employee eh','eh.employee_id=e.EmployeeLeaveHRD')
        ->join('left join','employee ep','ep.employee_id=e.EmployeeLeavePartner')
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
        
        if($this->employee_date_from && $this->employee_date_to)
        	$query->andWhere("EmployeeHireDate between STR_TO_DATE('".$this->employee_date_from."', '%d/%m/%Y') and STR_TO_DATE('".$this->$this->employee_date_to."', '%d/%m/%Y') ");
        if($this->employee_name) $query->andFilterWhere(['like', "CONCAT(e.EmployeeID,' ',e.EmployeeFirstname,' ',e.EmployeeMiddleName,' ',EmployeeLastName)",  $this->employee_name]);
        
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
            case 10768 : $hrd = $field;break;    
            case 10509 : $hrd = $field;break;
			case 10872 : $hrd = $field;break;
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
        else 
        	return false;
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
    
    /**
     *  Save Employee Profile
     * @param unknown $id
     */
    public function getUpdateProfile($id) {
        if($this->validate()){
            $model = new Employee();
            $model = $model->findOne($id);
            $model->EmployeeFirstName = $this->EmployeeFirstName;
            $model->EmployeeMiddleName = $this->EmployeeMiddleName;
            $model->EmployeeLastName = $this->EmployeeLastName;
            $model->EmployeeEmail = $this->EmployeeEmail;
            $model->EmployeeHireDate = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->EmployeeHireDate);
            $model->EmployeeHandPhone = $this->EmployeeHandPhone;
            $model->update();
            return true;
        }
    }
    
    /**
     *  Save Employee Profile Approval
     * @param unknown $id
     */
    public function getUpdateProfileApproval($id) {
    	if($this->validate()){
    		$model = new Employee();
    		$model = $model->findOne($id);
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
            //$model->EmployeeLeaveSenior = $this->EmployeeLeaveSenior;
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
        $email = isset($query)?$query->EmployeeEmail:'ssuhendar@bdo.co.id';
        return $email;
    }
    
    public static function getDropDownLeaveStatus($status=TRUE){
        $data[0] = Yii::t('app','all');
        $data[1] = Yii::t('app','must update');
        $data[2] = Yii::t('app','updated');
        return $data;
    }
    
    /**
     *Identity to Login
     */
    
    public static function role() {
    	return 1;
    }
    
    public static function isAuditorAssistant(){
        $position = isset(Yii::$app->user->identity->EmployeeTitle)?Yii::$app->user->identity->EmployeeTitle:0;
        switch($position){
            case 'Assistant' : $status=true;break;
            case 'Senior-2'  : $status=true;break;
            case 'Associate Consultant' : $status=true;break;
            case 'Sr Associate Consultant' : $status=true;break;
            case 'Ass HRD' : $status=true;break;      
            default : $status=false;break;   
        }
        return $status;
    }
    
    public static function isAuditorSenior(){
        $position = isset(Yii::$app->user->identity->EmployeeTitle)?Yii::$app->user->identity->EmployeeTitle:0;
        switch($position){
            case 'Senior' : $status=true;break;
            case 'Senior-1'  : $status=true;break;
            case 'Supervisor' : $status=true;break;
            case 'Assistant Manager' : $status=true;break;    
            case 'Consultant' : $status=true;break;
            case 'Senior Consultant' : $status=true;break;
            case 'Senior HRD' : $status=true;break;        
            default : $status=false;break;   
        }
        return $status;
    }
    
    public static function isAuditorManager(){
        $position = isset(Yii::$app->user->identity->EmployeeTitle)?Yii::$app->user->identity->EmployeeTitle:0;
        switch($position){
			case 'Senior Supervisor' : $status=true;break;
            case 'Manager' : $status=true;break;
            case 'Manager HRD' : $status=true;break;    
            case 'Senior Manager'  : $status=true;break;
            case 'Manager Advisory' : $status=true;break;
            default : $status=false;break;   
        }
        return $status;
    }
    
    public static function isAuditorPartner(){
        $position = isset(Yii::$app->user->identity->EmployeeTitle)?Yii::$app->user->identity->EmployeeTitle:0;
        switch($position){
            case 'Partner' : $status=true;break;
            case 'Associate Director' : $status=true;break;
            case 'Director' : $status=true;break;    
            default : $status=false;break;   
        }
        return $status;
    }
    
    public static function isHRD(){
        $position = isset(Yii::$app->user->identity->EmployeeTitle)?Yii::$app->user->identity->EmployeeTitle:0;
        switch($position){
            case 'Manager HRD' : $status=true;break;
			case 'Assistant Manager HRD' : $status=true;break;   
            case 'Senior HRD' : $status=true;break;   
            default : $status=false;break;   
        }
        return $status;
    }
    
    public static function isNotifyDate(){
        $hire_date = Yii::$app->user->identity->EmployeeHireDate;
    }
    
    public static function findOneEmployee($id)
    {
        return static::find()
        ->select(['employee_id','EmployeeID','CONCAT(EmployeeFirstName,\' \',EmployeeMiddleName,\' \',EmployeeLastName) as EmployeeName',
            'DATE_FORMAT(EmployeeHireDate,\'%d/%m/%Y\')  EmployeeHireDate','EmployeeStatus'
        ])
        ->where(['employee_id' => $id])
        ->one();
    }
    
}