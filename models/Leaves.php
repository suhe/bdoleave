<?php
namespace app\models;
use yii;
use yii\db\ActiveRecord;
use app\models\LeaveLog;
use app\models\Employee;
use app\models\Timesheet;
use app\models\TimesheetStatus;
 
class Leaves extends ActiveRecord {
    /**
     * Public Variabel
     * @var string,integer,object
     */
    public $EmployeeLeaveTotal;
    public $EmployeeLeaveUse;
    public $EmployeeLeaveOver;
    public $leave_approval;
    public $leave_note;
    //public $leave_saldo;
   
    /**
     * Status to Variabel
     * @var unknown
     */
    public static $request = 5;
    public static $approve_manager = 4;
    public static $inapprove_manager = 14;
    public static $approve_hrd = 3;
    public static $inapprove_hrd = 13;
    public static $approve_partner = 2;
    public static $inapprove_partner = 12;
    public static $completed = 1;
    public static $reject = 0;
    public static $timesheet_source = 1;
    public static $leave_source  = 2;
    public static $approval_progress = 2;
    public static $approval_no = 0;
    public static $approval_yes = 1;
    public static $cancel = -1;
    
    /**
     * Setting Leave Type 
     */
    public static $cuti_bersama = 1;
    public static $cuti_tahunan = 2;
    public static $cuti_tambahan = 3;
    public static $cuti_khusus = 4;
    public static $cuti_izin = 5;
    public static $beginning_balance = 6;
    
    /**
     * Setting to Component Field
     * @var unknown
     */
    public $leave_status_string = '';
    public $leave_source_string = '';
    public $leave_type_string = '';
    public $leave_app_user1_status_string = '';
    public $leave_app_hrd_status_string = '';
    public $leave_app_pic_status_string = '';
    
    /** 
     * Employee Variabel
     * var @string
     * **/
    public $employeeid;
    public $employee_name;
    public $EmployeeTitle;
    public $manager_approval;
    public $hrd_approval;
    public $partner_approval;
    public $EmployeeEmail;
    
    public static function tableName(){
        return 'leaves';
    }
    
    public function rules(){
        return [
            	[['employee_id'],'required','on'=>['add_leave']],
        		[['employee_id'],'safe','on'=>['search']],
            	[['leave_type'],'required','on'=>['add_leave','add_myleave']],
        		[['leave_status'],'required','on'=>['add_leave']],
	            [['leave_date_from'],'required','on'=>['add_leave','add_myleave']],
	            [['leave_date_to'],'required','on'=>['add_leave','add_myleave']],
	            [['leave_description'],'required','on'=>['add_leave','add_myleave']],
	            [['leave_address'],'required','on'=>['add_leave','add_myleave']],
        		[['leave_saldo_total'],'required','on'=>['add_leave','add_myleave']],
	            //[['leave_over'],'safe','on'=>['add_myleave']],
	            [['employee_name'],'safe','on'=>['search']],
	            [['leave_status'],'safe','on'=>['search']],
        		[['leave_source'],'safe','on'=>['search']],
	            [['leave_date_from'],'safe','on'=>['search']],
	            [['leave_date_to'],'safe','on'=>['search']],
	            //[['leave_date_type'],'safe','on'=>['search']],
	            [['leave_approval'],'required','on'=>['approval','app-completed']],
	            [['leave_approved'],'safe','on'=>['search','add_leave','add_myleave']],
	            [['leave_note'],'safe','on'=>['approval','app-completed']],
	        	//custom validate on use
        		[['leave_date_from','leave_date_to'],'isValidDateRange','on'=>['add_myleave','add_leave']],
        ];
    }
    
    public function attributeLabels(){
        return [
	            'leave_id' => Yii::t('app','id'),
	            'employee_id' => Yii::t('app','nik'),
	            'employeeid' => Yii::t('app','nik'),
	            'employee_name' => Yii::t('app','name'),
        		'leave_approval' => Yii::t('app','approval'),
	            'leave_date' => Yii::t('app','date of filing'),
	            'leave_type' => Yii::t('app','type'),
        		'leave_type_string' => Yii::t('app','type'),
	            'leave_status' => Yii::t('app','status'), 
        		'leave_status_string' => Yii::t('app','status'),
	            'leave_description' => Yii::t('app','necessary'),
	            'leave_date_from' => Yii::t('app','date from'),
	            'leave_date_to' => Yii::t('app','date to'),
	            'leave_range' => Yii::t('app','range'),
	            'leave_total' => Yii::t('app','days'),
        		'leave_saldo_balanced' => Yii::t('app','saldo'),
	            'leave_address' => Yii::t('app','address'),
        		'leave_note' => Yii::t('app','note'),
	            'employeefirstname' => Yii::t('app','name'),
	            'leave_over' => Yii::t('app','the amount of leave'),
	            'leave_pdf' => Yii::t('app','export pdf'),
	            'leave_approved' => Yii::t('app','approved'),
        		'leave_status_string' => Yii::t('app','status'),
        		'leave_source_string' => Yii::t('app','source'),
        ];
    }
    
    /**
     * Custom Validate Model
     * 
     * @return string[]
     */
    
    public function isValidDateRange($attribute, $params) {
    	$leave_date_from = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_from);
    	$leave_date_to = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_to);
    	$leave_range = strtotime($leave_date_to) -  strtotime($leave_date_from);
    	$deviation = ($leave_range / (60 * 60 * 24)) + 1;
    	 
    	if($deviation < 0)
    		$this->addError($attribute, Yii::t('app/msg','date range is not valid'));
    }
    
    /**
     * End Of Validate
     * 
     */
    
    /**
     * Custom List Dropdown 
     *
     * @return string[]
     */
    
    /**
     * List of Status Self Leave
     * @return string[]
     */
    public static function getListLeaveType($data  = array()) {
    	$lists = [];
    	if(isset($data['label']))
    		$lists[0] = $data['label'];
    	
    	if(isset($data['all']))
    		$lists[self::$cuti_bersama] = "Cuti Bersama" ;
    	$lists[self::$cuti_tahunan] = "Cuti Tahunan" ;
    	$lists[self::$cuti_tambahan] = "Cuti Tambahan" ;
    	$lists[self::$cuti_khusus] = "Cuti Khusus" ;
    	$lists[self::$cuti_izin] = "Izin diklasifikasikan Cuti";
    	//$lists[self::$beginning_balance] = "Saldo Awal";
    	
    	return $lists;
    }
    
    /**
     * List of Status Leave
     * @param unknown $label
     * @return string
     */
    public static function getListStatus($label = null) {
    	if($label)
    		$data = [0 => $label];
    	$data[self::$request] = Yii::t('app','request');    	
    	$data[self::$approve_manager] = Yii::t('app','approved by manager');
    	$data[self::$inapprove_manager] = Yii::t('app','inapproved by manager');
    	$data[self::$approve_hrd] = Yii::t('app','approved by hrd');
    	$data[self::$inapprove_hrd] = Yii::t('app','inapproved by hrd');
    	$data[self::$approve_partner] = Yii::t('app','approved by partner');
    	$data[self::$inapprove_partner] = Yii::t('app','inapproved by partner');
    	return $data;
    }
    
    /**
     * List of Source
     * @param unknown $label
     * @return string
     */
    public static function getListSource($label = null) {
    	if($label)
    		$data = [0 => $label];
    	$data[self::$timesheet_source] = Yii::t('app','timesheet');
    	$data[self::$leave_source] = Yii::t('app','leave');
    	return $data;
    }
    
    /**
     * List of Approval List
     * @param unknown $label
     * @return string
     */
    public static function getListApproval($label = null) {
    	if($label)
    		$data = [0 => $label];
    	$data[self::$approval_yes] = Yii::t('app','yes');
    	$data[self::$approval_no] = Yii::t('app','no');
    	return $data;
    }
    
    /**
     * List of Status after Approved/InApproved Partner
     * @param unknown $label
     * @return string
     */
    public static function getListStatusAfterApproved($leave_status,$label = null) {
    	if($label)
    		$data = [0 => $label];
    	
    	if($leave_status == self::$approve_partner) {
    		$data[self::$completed] = Yii::t('app','completed');
    		$data[self::$cancel] = Yii::t('app','cancel by request');
    	} else {
    		$data[self::$reject] = Yii::t('app','reject');
    	}
    		
    	
    	return $data;
    }
    
    /**
     * Get List Status 
     * @param unknown $label
     */
    public static function getListStatusByManagement($label = null){
    	if($label)
    		$data = [0 => $label];
    	
    	$data[self::$completed] = Yii::t('app','completed');
    	$data[self::$approve_partner] = Yii::t('app','approved by partner');
    	$data[self::$approve_hrd] = Yii::t('app','approved by hrd');
    	$data[self::$approve_manager] = Yii::t('app','approved by manager');
    	$data[self::$request] = Yii::t('app','request');
    	return $data;
    }
    
    /**
     * End Of List Dropdown
     *
     */
    
    
    /** 
     * Get DataProvider for MyLeave
     * return $model
     */
    public function getMyLeaveDataProvider($params){
    	$query = Leaves::find()
    	->select(['leave_id','DATE_FORMAT(leave_date,\'%d/%m/%Y\') leave_date','leave_description','leave_range',
    			'leave_total','leave_request','DATE_FORMAT(leave_date_from,\'%d/%m/%Y\') leave_date_from','leave_status',
    			'DATE_FORMAT(leave_date_to,\'%d/%m/%Y\') leave_date_to','leave_saldo_balanced','leave_total',
    			"(CASE WHEN leave_type = ".self::$cuti_bersama." THEN '".Yii::t('app','cuti bersama')."'
		    		WHEN leave_type = ".self::$cuti_tahunan." THEN '".Yii::t('app','cuti tahunan')."'
		    		WHEN leave_type = ".self::$cuti_tambahan." THEN '".Yii::t('app','cuti tambahan')."'
		    		WHEN leave_type = ".self::$cuti_khusus." THEN '".Yii::t('app','cuti khusus')."'
		    		WHEN leave_type = ".self::$cuti_izin." THEN '".Yii::t('app','izin diklasifikan Cuti')."'
    				WHEN leave_type = ".self::$beginning_balance." THEN '".Yii::t('app','saldo awal')."'
		    	END) as leave_type_string",
    			"(CASE WHEN leave_status = ".self::$request." THEN '".Yii::t('app','request')."'
    			WHEN leave_status = ".self::$approve_manager." THEN '".Yii::t('app','approved by manager')."'
    			WHEN leave_status = ".self::$inapprove_manager." THEN '".Yii::t('app','inapproved by manager')."'
    			WHEN leave_status = ".self::$approve_hrd." THEN '".Yii::t('app','approved by hrd')."'
    			WHEN leave_status = ".self::$inapprove_hrd." THEN '".Yii::t('app','inapproved by hrd')."'
    			WHEN leave_status = ".self::$approve_partner." THEN '".Yii::t('app','approved by partner')."'
    			WHEN leave_status = ".self::$inapprove_partner." THEN '".Yii::t('app','inapproved by partner')."'
    			WHEN leave_status = ".self::$completed." THEN '".Yii::t('app','completed')."'
    			END) as leave_status_string",
    			"(CASE WHEN leave_status = 1 THEN '".Yii::t('app','timesheet')."' ELSE '".Yii::t('app','leave')."' END) as leave_source_string"
    	])
    	->from('leaves')
    	->where(['employee_id' => Yii::$app->user->getId()]);
    
    	$dataProvider = new \yii\data\ActiveDataProvider([
    			'query' => $query,
    			'totalCount' => count ($query),
    			'pagination' =>[
    					'pageSize' => Yii::$app->params['per_page']
    			]
    	]);
    
    	if ((!$this->load($params)) && ($this->validate())) {
    		return $dataProvider;
    	}
    
    	if($this->leave_status) $query->andWhere(['leave_status'=>$this->leave_status]);
    	if($this->leave_source) $query->andWhere(['leave_source'=>$this->leave_source]);
    	if($this->leave_date_from && $this->leave_date_to)
    		$query->andWhere("leave_date between STR_TO_DATE('".$this->leave_date_from."', '%d/%m/%Y') and STR_TO_DATE('".$this->leave_date_to."', '%d/%m/%Y') ");
    	
    	return $dataProvider;
    }
    
    /**
     * Get DataProvider for History App Employee
     * return $model
     */
    public function getEmployeeLeaveDataProvider($employee_id,$not_allowed_leave_id){
    	$query = Leaves::find()
    	->select(['leave_id','DATE_FORMAT(leave_date,\'%d/%m/%Y\') leave_date','leave_description','leave_range',
    			'leave_total','leave_request','DATE_FORMAT(leave_date_from,\'%d/%m/%Y\') leave_date_from','leave_status',
    			'DATE_FORMAT(leave_date_to,\'%d/%m/%Y\') leave_date_to',
    			"(CASE WHEN leave_type = ".self::$cuti_bersama." THEN '".Yii::t('app','cuti bersama')."'
		    		WHEN leave_type = ".self::$cuti_tahunan." THEN '".Yii::t('app','cuti tahunan')."'
		    		WHEN leave_type = ".self::$cuti_tambahan." THEN '".Yii::t('app','cuti tambahan')."'
		    		WHEN leave_type = ".self::$cuti_khusus." THEN '".Yii::t('app','cuti khusus')."'
		    		WHEN leave_type = ".self::$cuti_izin." THEN '".Yii::t('app','izin diklasifikan Cuti')."'
		    		WHEN leave_type = ".self::$beginning_balance." THEN '".Yii::t('app','saldo awal')."'
    			END) as leave_type_string",
    			"(CASE WHEN leave_status = ".self::$request." THEN '".Yii::t('app','request')."'
    			WHEN leave_status = ".self::$approve_manager." THEN '".Yii::t('app','approved by manager')."'
    			WHEN leave_status = ".self::$inapprove_manager." THEN '".Yii::t('app','inapproved by manager')."'
    			WHEN leave_status = ".self::$approve_hrd." THEN '".Yii::t('app','approved by hrd')."'
    			WHEN leave_status = ".self::$inapprove_hrd." THEN '".Yii::t('app','inapproved by hrd')."'
    			WHEN leave_status = ".self::$approve_partner." THEN '".Yii::t('app','approved by partner')."'
    			WHEN leave_status = ".self::$inapprove_partner." THEN '".Yii::t('app','inapproved by partner')."'
    			END) as leave_status_string",
    			"(CASE WHEN leave_status = 1 THEN '".Yii::t('app','timesheet')."' ELSE '".Yii::t('app','leave')."' END) as leave_source_string"
    	])
    	->from('leaves')
    	->where(['not in','leave_id',[$not_allowed_leave_id]])
    	->andWhere(['employee_id' => $employee_id]);
    
    	$dataProvider = new \yii\data\ActiveDataProvider([
    			'query' => $query,
    			'totalCount' => count ($query),
    			'pagination' =>[
    					'pageSize' => Yii::$app->params['per_page']
    			]
    	]);
    
    	return $dataProvider;
    }
    
    /**
     * Get DataProvider for My Balance Employee
     *  return $params
     */
    public function getMyBalanceLeaveCardDataProvider() {
    	$sqlquery = "
	    	SELECT DATE_FORMAT(balanced.leave_date,'%d/%m/%Y') as leave_date,balanced.leave_type_string, balanced.leave_description,balanced.leave_total,
	    	balanced.leave_saldo,balanced.leave_source_string
	    	FROM
	    	(
	    	SELECT
	    	leave_date,leave_type_string,leave_balance_description as leave_description,leave_total,
	    	@saldo:=@saldo+leave_total AS leave_saldo,leave_source_string
	    	FROM
	    	(SELECT leave_balance_date as leave_date,'".Yii::t('app','beginning balance')."' as leave_type_string,
	    	leave_balance_description,+leave_balance_total as leave_total,'".Yii::t('app','leave')."' as leave_source_string
	    	FROM leave_balance
	    	WHERE employee_id = ".Yii::$app->user->getId()."
	    	UNION ALL
	    	SELECT leave_date,
	    	(CASE 
	    		WHEN leave_type = ".self::$cuti_bersama." THEN '".Yii::t('app','cuti bersama')."'
	    		WHEN leave_type = ".self::$cuti_tahunan." THEN '".Yii::t('app','cuti tahunan')."'
	    		WHEN leave_type = ".self::$cuti_tambahan." THEN '".Yii::t('app','cuti tambahan')."'
	    		WHEN leave_type = ".self::$cuti_khusus." THEN '".Yii::t('app','cuti khusus')."'
	    		WHEN leave_type = ".self::$cuti_izin." THEN '".Yii::t('app','izin diklasifikan Cuti')."'
	    		WHEN leave_type = ".self::$beginning_balance." THEN '".Yii::t('app','saldo awal')."'
	    	END) as leave_type_string,
	    	CONCAT(leave_description,' ',DATE_FORMAT(leave_date_from,'%d %M %Y'),' - ',DATE_FORMAT(leave_date_to,'%d %M %Y')) leave_description,
	    	-leave_total as leave_total,IF(leave_source = ".self::$timesheet_source.",'".Yii::t('app','timesheet')."','".Yii::t('app','leave')."') as leave_source_string
	    	FROM `leaves`
	    	WHERE employee_id = ".Yii::$app->user->getId()."
	    	) balance
	    	JOIN (SELECT @saldo:=0) a
	    	ORDER BY leave_date
	    	) AS balanced
	    	WHERE balanced.leave_date <> '' 
	    ";
    
    	$query = LeaveBalance::findBySql($sqlquery);
    
    	$dataProvider = new \yii\data\ActiveDataProvider([
    			'query' => $query,
    			'totalCount' => count($query),
    			'pagination' =>[
    					'pageSize' => Yii::$app->params['per_page']
    			]
    	]);
    	
    	 
    	return $dataProvider;
    }
    
    /**
     * Get DataProvider for App Balance Employee
     *  return $params
     */
    public function getBalanceLeaveCardDataProvider($employee_id = 0,$params = '') {	
    	$sqlquery = "
	    	SELECT leave_id,DATE_FORMAT(balanced.leave_date,'%d/%m/%Y') as leave_date,balanced.leave_type_string, balanced.leave_description,balanced.leave_total,
	    	balanced.leave_saldo,balanced.leave_source_string
	    	FROM
	    	(
	    	SELECT leave_id,
	    	leave_date,leave_type_string,leave_balance_description as leave_description,leave_total,
	    	@saldo:=@saldo+leave_total AS leave_saldo,leave_source_string
	    	FROM
	    	(SELECT leave_balance_id as leave_id,leave_balance_date as leave_date,'".Yii::t('app','beginning balance')."' as leave_type_string,
	    	leave_balance_description,+leave_balance_total as leave_total,'".Yii::t('app','leave')."' as leave_source_string		
	    	FROM leave_balance
	    	WHERE employee_id = ".($this->employee_id ? $this->employee_id : 0) ."
	    	UNION ALL
	    	SELECT leave_id,leave_date,
	    	(CASE WHEN leave_type = ".self::$cuti_bersama." THEN '".Yii::t('app','cuti bersama')."'
	    		WHEN leave_type = ".self::$cuti_tahunan." THEN '".Yii::t('app','cuti tahunan')."'
	    		WHEN leave_type = ".self::$cuti_tambahan." THEN '".Yii::t('app','cuti tambahan')."'
	    		WHEN leave_type = ".self::$cuti_khusus." THEN '".Yii::t('app','cuti khusus')."'
	    		WHEN leave_type = ".self::$cuti_izin." THEN '".Yii::t('app','izin diklasifikan Cuti')."'	
				WHEN leave_type = ".self::$beginning_balance." THEN '".Yii::t('app','saldo awal')."'
	    	END) as leave_type_string,		
	    	CONCAT(leave_description,' ',DATE_FORMAT(leave_date_from,'%d %M %Y'),' - ',DATE_FORMAT(leave_date_to,'%d %M %Y')) leave_description,
	    	-leave_total as leave_total,IF(leave_source = ".self::$timesheet_source.",'".Yii::t('app','timesheet')."','".Yii::t('app','leave1')."') as leave_source_string
	    	FROM `leaves`
	    	WHERE employee_id = ".($this->employee_id ? $this->employee_id : 0) ." and leave_approved = 1
	    	) balance
	    	JOIN (SELECT @saldo:=0) a
	    	ORDER BY leave_date 
	    	) AS balanced
	    	WHERE balanced.leave_date <> '' 
	    ";
    
    	$query = LeaveBalance::findBySql($sqlquery);
    
    	$dataProvider = new \yii\data\ActiveDataProvider([
    			'query' => $query,
    			'totalCount' => count($query),
    			'pagination' =>[
    					'pageSize' => Yii::$app->params['per_page']
    			]
    	]);
    	
    	if ((!$this->load($params)) && ($this->validate())) {
    		return $dataProvider;
    	}
    	
    	return $dataProvider;
    }
    
    /**
     * Get DataProvider for App Leave 
     * return $model
     */
    public function getAppLeaveDataProvider($params){
    	$query = Leaves::find()
    	->select(['leave_id','DATE_FORMAT(leave_date,\'%d/%m/%Y\') leave_date','leave_description','leave_range',
    			"EmployeeID as employeeid","CONCAT(EmployeeFirstName,' ',EmployeeMiddleName,' ',EmployeeLastName) as employee_name","EmployeeTitle",
    			'leave_total','leave_request','DATE_FORMAT(leave_date_from,\'%d/%m/%Y\') leave_date_from',
    			'DATE_FORMAT(leave_date_to,\'%d/%m/%Y\') leave_date_to',
    			"(CASE WHEN leave_status = ".self::$request." THEN '".Yii::t('app','request')."'
    			WHEN leave_status = ".self::$approve_manager." THEN '".Yii::t('app','approved by manager')."'
    			WHEN leave_status = ".self::$inapprove_manager." THEN '".Yii::t('app','inapproved by manager')."'
    			WHEN leave_status = ".self::$approve_hrd." THEN '".Yii::t('app','approved by hrd')."'
    			WHEN leave_status = ".self::$inapprove_hrd." THEN '".Yii::t('app','inapproved by hrd')."'
    			WHEN leave_status = ".self::$approve_partner." THEN '".Yii::t('app','approved by partner')."'
    			WHEN leave_status = ".self::$inapprove_partner." THEN '".Yii::t('app','inapproved by partner')."'
    			END) as leave_status_string",
    			"(CASE WHEN leave_status = ".self::$timesheet_source." THEN '".Yii::t('app','timesheet')."' ELSE '".Yii::t('app','leave')."' END) as leave_source_string"
    	])
    	->from('leaves')
    	->join('inner join','employee','employee.employee_id = leaves.employee_id')
    	->orWhere(['leave_app_user1' => Yii::$app->user->getId(),'leave_app_user1_status' => self::$approval_progress])
    	->orWhere(" leave_app_hrd = ".Yii::$app->user->getId()." and leave_app_hrd_status = ".self::$approval_progress." and leave_app_user1_status <> ".self::$approval_progress."")
    	->orWhere(" leave_app_pic = ".Yii::$app->user->getId()." and leave_app_pic_status = ".self::$approval_progress." and leave_app_hrd_status <> ".self::$approval_progress."");
    
    	$dataProvider = new \yii\data\ActiveDataProvider([
    			'query' => $query,
    			'totalCount' => count ($query),
    			'pagination' =>[
    					'pageSize' => Yii::$app->params['per_page']
    			]
    	]);
    
    	if ((!$this->load($params)) && ($this->validate())) {
    		return $dataProvider;
    	}
   
    	if($this->leave_date_from && $this->leave_date_to)
    		$query->andWhere("leave_date between STR_TO_DATE('".$this->leave_date_from."', '%d/%m/%Y') and STR_TO_DATE('".$this->leave_date_to."', '%d/%m/%Y') ");
    	if($this->employee_name) $query->andFilterWhere(['like', "CONCAT(EmployeeID,' ',EmployeeFirstname,' ',EmployeeMiddleName,' ',EmployeeLastName)",  $this->employee_name]);
    		
    	return $dataProvider;
    }
    
    /**
     * Get DataProvider for Management Leave
     * return $model
     */
    public function getAppManageLeaveDataProvider($params){
    	$query = Leaves::find()
    	->select(['leave_id','DATE_FORMAT(leave_date,\'%d/%m/%Y\') leave_date','leave_description','leave_range',
    			"employee.EmployeeID as employeeid","CONCAT(EmployeeFirstName,' ',EmployeeMiddleName,' ',EmployeeLastName) as employee_name",
    			'leave_total','leave_request','DATE_FORMAT(leave_date_from,\'%d/%m/%Y\') leave_date_from',
    			'DATE_FORMAT(leave_date_to,\'%d/%m/%Y\') leave_date_to',
    			"(CASE WHEN leave_status = ".self::$request." THEN '".Yii::t('app','request')."'
    			WHEN leave_status = ".self::$approve_manager." THEN '".Yii::t('app','approved by manager')."'
    			WHEN leave_status = ".self::$inapprove_manager." THEN '".Yii::t('app','inapproved by manager')."'
    			WHEN leave_status = ".self::$approve_hrd." THEN '".Yii::t('app','approved by hrd')."'
    			WHEN leave_status = ".self::$inapprove_hrd." THEN '".Yii::t('app','inapproved by hrd')."'
    			WHEN leave_status = ".self::$approve_partner." THEN '".Yii::t('app','approved by partner')."'
    			WHEN leave_status = ".self::$inapprove_partner." THEN '".Yii::t('app','inapproved by partner')."'
    			END) as leave_status_string",
    			"(CASE WHEN leave_status = ".self::$timesheet_source." THEN '".Yii::t('app','timesheet')."' ELSE '".Yii::t('app','leave')."' END) as leave_source_string"
    	])
    	->from('leaves')
    	->join('inner join','employee','employee.employee_id = leaves.employee_id')
    	->where("leave_status = ".self::$approve_partner." OR leave_status = ".self::$inapprove_partner." ");
    
    	$dataProvider = new \yii\data\ActiveDataProvider([
    			'query' => $query,
    			//'count' => clone ($query),
    			'pagination' =>[
    					'pageSize' => Yii::$app->params['per_page']
    			]
    	]);
    
    	if ((!$this->load($params)) && ($this->validate())) {
    		return $dataProvider;
    	}
    	if($this->employee_name) $query->andWhere(" CONCAT(EmployeeID,' ',EmployeeFirstName,' ',EmployeeMiddleName,' ',EmployeeLastName) LIKE '%" .$this->employee_name. "%' "); 
    	if($this->leave_date_from) $query->andFilterWhere(['>=', 'DATE_FORMAT(leave_date,\'%d/%m/%Y\')', $this->leave_date_from]);
    	if($this->leave_date_from) $query->andFilterWhere(['<=', 'DATE_FORMAT(leave_date,\'%d/%m/%Y\')', $this->leave_date_to]);
    	
    	
    	return $dataProvider;
    }
    
    /**
     * Get Outstanding DataProvider for Management Leave
     * return $model
     */
    public function getOutstandingLeaveDataProvider($params){
    	$query = Leaves::find()
    	->select(['leave_id','DATE_FORMAT(leave_date,\'%d/%m/%Y\') leave_date','leave_description','leave_range',
    			"employee.EmployeeID as employeeid","CONCAT(EmployeeFirstName,' ',EmployeeMiddleName,' ',EmployeeLastName) as employee_name",
    			'leave_total','leave_request','DATE_FORMAT(leave_date_from,\'%d/%m/%Y\') leave_date_from',
    			'DATE_FORMAT(leave_date_to,\'%d/%m/%Y\') leave_date_to',
    			"(CASE WHEN leave_status = ".self::$request." THEN '".Yii::t('app','request')."'
    			WHEN leave_status = ".self::$approve_manager." THEN '".Yii::t('app','approved by manager')."'
    			WHEN leave_status = ".self::$inapprove_manager." THEN '".Yii::t('app','inapproved by manager')."'
    			WHEN leave_status = ".self::$approve_hrd." THEN '".Yii::t('app','approved by hrd')."'
    			WHEN leave_status = ".self::$inapprove_hrd." THEN '".Yii::t('app','inapproved by hrd')."'
    			WHEN leave_status = ".self::$approve_partner." THEN '".Yii::t('app','approved by partner')."'
    			WHEN leave_status = ".self::$inapprove_partner." THEN '".Yii::t('app','inapproved by partner')."'
    			END) as leave_status_string",
    			"(CASE WHEN leave_status = ".self::$timesheet_source." THEN '".Yii::t('app','timesheet')."' ELSE '".Yii::t('app','leave')."' END) as leave_source_string"
    	])
    	->from('leaves')
    	->join('inner join','employee','employee.employee_id = leaves.employee_id')
    	->where("leave_status <> ".self::$completed." ");
    
    	$dataProvider = new \yii\data\ActiveDataProvider([
    			'query' => $query,
    			//'count' => clone ($query),
    			'pagination' =>[
    					'pageSize' => Yii::$app->params['per_page']
    			]
    	]);
    
    	if ((!$this->load($params)) && ($this->validate())) {
    		return $dataProvider;
    	}
    	if($this->employee_name) $query->andWhere(" CONCAT(EmployeeID,' ',EmployeeFirstName,' ',EmployeeMiddleName,' ',EmployeeLastName) LIKE '%" .$this->employee_name. "%' ");
    	if($this->leave_date_from) $query->andFilterWhere(['>=', 'DATE_FORMAT(leave_date,\'%d/%m/%Y\')', $this->leave_date_from]);
    	if($this->leave_date_from) $query->andFilterWhere(['<=', 'DATE_FORMAT(leave_date,\'%d/%m/%Y\')', $this->leave_date_to]);
    	if($this->leave_status) $query->andWhere(['leave_status'=>$this->leave_status]);
    	if($this->leave_source) $query->andWhere(['leave_source'=>$this->leave_source]);
    	
    	return $dataProvider;
    }
    
    /**
     * Get Details View of Leave Data
     * @param integer $id
     */
    public function getDetailView($id) {
    	return Leaves::find()
    	->select(["DATE_FORMAT(leave_date,'%d %M %Y') as leave_date","leave_total","leave_range","leave_description","leave_address",
    	"DATE_FORMAT(leave_date_from,'%d %M %Y') as leave_date_from","DATE_FORMAT(leave_date_to,'%d %M %Y') as leave_date_to",
    	"leave_saldo_total","leave_saldo_balanced","employee_id","leave_status",
    	"(CASE WHEN leave_status = ".self::$request." THEN '".Yii::t('app','request')."'
			WHEN leave_status = ".self::$approve_manager." THEN '".Yii::t('app','approved by manager')."'
    		WHEN leave_status = ".self::$inapprove_manager." THEN '".Yii::t('app','inapproved by manager')."'
    		WHEN leave_status = ".self::$approve_hrd." THEN '".Yii::t('app','approved by hrd')."'
    		WHEN leave_status = ".self::$inapprove_hrd." THEN '".Yii::t('app','inapproved by hrd')."'
    		WHEN leave_status = ".self::$approve_partner." THEN '".Yii::t('app','approved by partner')."'
    		WHEN leave_status = ".self::$inapprove_partner." THEN '".Yii::t('app','inapproved by partner')."'	
    	END) as leave_status_string","(CASE WHEN leave_type = ".self::$cuti_bersama." THEN '".Yii::t('app','cuti bersama')."'
		    		WHEN leave_type = ".self::$cuti_tahunan." THEN '".Yii::t('app','cuti tahunan')."'
		    		WHEN leave_type = ".self::$cuti_tambahan." THEN '".Yii::t('app','cuti tambahan')."'
		    		WHEN leave_type = ".self::$cuti_khusus." THEN '".Yii::t('app','cuti khusus')."'
		    		WHEN leave_type = ".self::$cuti_izin." THEN '".Yii::t('app','izin diklasifikan Cuti')."'
		    	END) as leave_type_string",
    	"(CASE WHEN leave_status = 1 THEN '".Yii::t('app','timesheet')."' ELSE '".Yii::t('app','leave')."' END) as leave_source_string"
    	])
    	->from('leaves l')
    	->where(['l.leave_id' => $id])
    	->one();
    }
    
    /**
     *  Get Approval Data 
     *  Submission Total Leave
     */
    public static function getAppLeave() {
    	$query = Leaves::find()
    	->join('inner join','employee','employee.employee_id = leaves.employee_id')
    	->select(["*","CONCAT(EmployeeFirstName,' ',EmployeeMiddleName,' ',EmployeeLastName) as employee_name","DATE_FORMAT(leave_date,'%d/%m/%Y') as leave_date"])
    	->orWhere(['leave_app_user1' => Yii::$app->user->getId(),'leave_app_user1_status' => self::$approval_progress])
    	->orWhere(" leave_app_hrd = ".Yii::$app->user->getId()." and leave_app_hrd_status = ".self::$approval_progress." and leave_app_user1_status <> ".self::$approval_progress."")
    	->orWhere(" leave_app_pic = ".Yii::$app->user->getId()." and leave_app_pic_status = ".self::$approval_progress." and leave_app_hrd_status <> ".self::$approval_progress."")
    	->all();
    	
    	if($query)
    		return $query;
    	return false;
    }
    
    /**  
     * Total Sum App Leave
     * @return number
     */
    public static function sumAppLeave() {
    	$app_leave = self::getAppLeave();
    	if($app_leave)
    		return count($app_leave);
    	else 
    		return 0;
    }
    
    /**
     *  Get Approval Data
     *  Submission Total Leave
     */
    public static function getAppAllLeave() {
    	$query = Leaves::find()
    	->join('inner join','employee','employee.employee_id = leaves.employee_id')
    	->select(["*","CONCAT(EmployeeFirstName,' ',EmployeeMiddleName,' ',EmployeeLastName) as employee_name","DATE_FORMAT(leave_date,'%d/%m/%Y') as leave_date"])
    	->orWhere(["leave_app_user1_status" => self::$approval_progress])
    	->orWhere(["leave_app_hrd_status" => self::$approval_progress])
    	->orWhere(["leave_app_pic_status" => self::$approval_progress])
    	->limit(5) //for demo
    	->all();
    	 
    	if($query)
    		return $query;
    		return false;
    }
    
    
    public static function getApprovedTodayLeave() {
    	return Leaves::find()
    	->select(["l.*",
    	"CONCAT(e.EmployeeFirstName,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) as employee_name",
    	"CONCAT(em.EmployeeFirstName,' ',em.EmployeeMiddleName,' ',em.EmployeeLastName) as manager_approval",
    	"CONCAT(eh.EmployeeFirstName,' ',eh.EmployeeMiddleName,' ',eh.EmployeeLastName) as hrd_approval",
    	"CONCAT(ep.EmployeeFirstName,' ',ep.EmployeeMiddleName,' ',ep.EmployeeLastName) as partner_approval",
    	"e.EmployeeEmail","e.EmployeeTitle","DATE_FORMAT(leave_date,'%d %M %Y') as leave_date",
    	"DATE_FORMAT(leave_date_from,'%d %M %Y') as leave_date_from","DATE_FORMAT(leave_date_to,'%d %M %Y') as leave_date_to",
    	"(CASE WHEN l.leave_type = ".self::$cuti_bersama." THEN '".Yii::t('app','cuti bersama')."'
		  	WHEN l.leave_type = ".self::$cuti_tahunan." THEN '".Yii::t('app','cuti tahunan')."'
		  	WHEN l.leave_type = ".self::$cuti_tambahan." THEN '".Yii::t('app','cuti tambahan')."'
		  	WHEN l.leave_type = ".self::$cuti_khusus." THEN '".Yii::t('app','cuti khusus')."'
		  	WHEN l.leave_type = ".self::$cuti_izin." THEN '".Yii::t('app','izin diklasifikan Cuti')."'
		END) as leave_type_string",
    	"(CASE WHEN leave_status = ".self::$request." THEN '".Yii::t('app','request')."'
    		WHEN leave_status = ".self::$approve_manager." THEN '".Yii::t('app','approved by manager')."'
    		WHEN leave_status = ".self::$inapprove_manager." THEN '".Yii::t('app','inapproved by manager')."'
    		WHEN leave_status = ".self::$approve_hrd." THEN '".Yii::t('app','approved by hrd')."'
    		WHEN leave_status = ".self::$inapprove_hrd." THEN '".Yii::t('app','inapproved by hrd')."'
    		WHEN leave_status = ".self::$approve_partner." THEN '".Yii::t('app','approved by partner')."'
    		WHEN leave_status = ".self::$inapprove_partner." THEN '".Yii::t('app','inapproved by partner')."'
    	END) as leave_status_string",
    	"(CASE WHEN leave_app_user1_status = ".self::$approval_no." THEN '".Yii::t('app','inapprove')."'
    		WHEN leave_app_user1_status = ".self::$approval_yes." THEN '".Yii::t('app','approve')."'
    		WHEN leave_app_user1_status = ".self::$approval_progress." THEN '".Yii::t('app','progress')."'	
    	END) as leave_app_user1_status_string",
    	"(CASE WHEN leave_app_hrd_status = ".self::$approval_no." THEN '".Yii::t('app','inapprove')."'
    		WHEN leave_app_hrd_status = ".self::$approval_yes." THEN '".Yii::t('app','approve')."'
    		WHEN leave_app_hrd_status = ".self::$approval_progress." THEN '".Yii::t('app','progress')."'
    	END) as leave_app_hrd_status_string",
    	"(CASE WHEN leave_app_pic_status = ".self::$approval_no." THEN '".Yii::t('app','inapprove')."'
    		WHEN leave_app_pic_status = ".self::$approval_yes." THEN '".Yii::t('app','approve')."'
    		WHEN leave_app_pic_status = ".self::$approval_progress." THEN '".Yii::t('app','progress')."'
    	END) as leave_app_pic_status_string"
    	])
    	->from("leaves l")
    	->join("inner join","employee e","e.employee_id = l.employee_id")
    	->join("left join","employee em","em.employee_id = l.leave_app_user1")
    	->join("left join","employee eh","eh.employee_id = l.leave_app_hrd")
    	->join("left join","employee ep","ep.employee_id = l.leave_app_pic")
    	->where("l.leave_status > ".self::$completed)
    	->andWhere("DATE(l.leave_updated_date) = '".date('Y-m-d')."'")
    	->andWhere("l.leave_app_pic_status <> ".self::$approval_progress." OR l.leave_app_hrd_status <> ".self::$approval_progress." OR l.leave_app_user1_status <> ".self::$approval_progress)
    	->all();
    }
    
    
    
    public static function ExpiredNotify(){
    	$message = '';
    	$hire_date = substr(Yii::$app->user->identity->EmployeeHireDate,0,10);
    	$now_date =  date('Y-m-d');
    	$range1 = \app\components\Common::dateRange($hire_date,$now_date);
    	//$message.= "Jumlah Hari Pengabdian :". $hire_date;
    
    	if($range1 >= 365) {
    		$lyear = date('Y') - 1;
    		$ldate = $lyear.substr($hire_date,4,6);
    
    		// + 1 tahun untuk Expired
    		$exlyear = substr($ldate,0,4)+1;
    		$exldate = $exlyear.substr($ldate,4,6);
    
    		$range2 = \app\components\Common::dateRange($now_date,$exldate);
    
    		if($range2>0)
    			$ldate = $ldate;
    			else
    				$ldate = $exldate;
    
    				$expyear = substr($ldate,0,4) + 1;
    				$expdate =  $expyear.substr($ldate,4,6);
    
    				$range3 = \app\components\Common::dateRange($now_date,$expdate);
    				$leave = Yii::$app->user->identity->EmployeeLeaveTotal - Yii::$app->user->identity->EmployeeLeaveUse;
    
    				if( ($range3<=150) && ($leave > 0) )
    					$message.= 'Hak Cuti Anda sebesar '.$leave.' Hari akan Kadaluarsa pada Tanggal '.\app\components\Common::MysqlDateToString($expdate);
    
    	}
    
    	return $message;
    
    }
    
    /**
    public static function getDropDownType(){
        $data = [
            1 => 'Cuti Bersama' ,
            2 => 'Cuti Tahunan' ,
            3 => 'Cuti Tambahan',
            4 => 'Cuti Khusus',
            5 => 'Izin diklasifikasikan Cuti',
        ];
        return $data;
    } 
    
    
    
    public static function getDropDownStatus($ALL=FALSE){
        $data = [
            1 => 'Completed' ,
            2 => 'Approved By Partner' ,
            3 => 'Approved By HRD',
            4 => 'Approved By Manager',
            5 => 'Request',
            6 => 'Request By Timesheet',
            //7 => 'Waiting By Timesheet',
            //8 => 'Approved By Timesheet',
            12 => 'Don\'t Agree By Partner',
            13 => 'Don\'t Agree By HRD',
            14 => 'Don\'t Agree By Senior/Manager',
            15 => 'Failed',
            16 => 'Returned By Timesheet',
        ];
        
        if($ALL==TRUE)
            $data[0] = Yii::t('app','all');
           
        return $data;
    }
    
    public static function getDropDownRequest($ALL=FALSE){
        $data = [
            1 => 'Completed',
            2 => 'Request to Partner' ,
            3 => 'Request to HRD',
            4 => 'Request to Manager',
            5 => 'Request to Senior',
            6 => 'By Timesheet',
        ];
        
        if($ALL==TRUE)
            $data[0] = Yii::t('app','all');
           
        return $data;
    }
    
    public static function getStringType($key){
        switch($key){
            case 1 : $string = 'Cuti Bersama';break;
            case 2 : $string = 'Cuti Tahunan';break;
            case 3 : $string = 'Cuti Tambahan';break;
            case 4 : $string = 'Cuti Khusus';break;
        }
        return $string;
    }
    
    public static function getDropDownApproval(){
        $data = [
            1 => 'Approve' ,
            3 => 'Reject' ,
        ];
        return $data;
    }
    
    public static function getDropDownApproved(){
        $data = [
            0 => 'All',
            1 => 'Approve' ,
            2 => 'Reject',
            3 => 'Process',
        ];
        return $data;
    }
    
    public static function getDropDownDateType(){
        $data = [
            1 => 'Date Filling',
            2 => 'Date Leave'
        ];
        return $data;
    }
    
     public static function getDropDownHRApproval(){
        $data = [
            1 => 'Approve By HRD Request to Partner (By Paper)',
            3 => 'Reject By HRD Request to Partner (By Paper)',
            4 => 'Approve HRD Request to Partner (Automatic)',
            5 => 'Reject HRD Request to Partner (Automatic)',
        ];
        return $data;
    }
    
    public static function getStringStatus($key) {
        switch($key){
            case 1 : $string = 'Completed';break;
            case 2 : $string = 'Approved By Partner';break;
            case 3 : $string = 'Approved By HRD';break;
            case 4 : $string = 'Approved By Senior/Manager';break;
            case 5 : $string = 'Request';break;
            case 6 : $string = 'By Timesheet';break;    
            case 12 : $string = 'Don\'t Agree By Partner';break;
            case 13 : $string = 'Don\'t Agree By HRD';break;
            case 14 : $string = 'Don\'t Agree By Senior/Manager';break;    
            case 15 : $string = 'Failed';break;
            case 16 : $string = 'Returned By Timesheet';break;
            default : $string = Yii::t('app','uknown');break;    
        }
        return $string;
    }
    
    public static function getStringRequest($key){
        switch($key){
            case 1 : $string = 'Completed';break;
            case 2 : $string = 'HR Request to Partner';break;
            case 3 : $string = 'Request to HRD';break;
            case 4 : $string = 'Request to Manager';break;
            case 5 : $string = 'Request to Senior';break;
            case 6 : $string = 'Request By Timesheet';break;    
            default : $string = Yii::t('app','uknown');break;    
        }
        return $string;
    }
    
    public static function getStringApproved($key){
        switch($key){
            case 1 : $string = 'Approved';break;
            case 2 : $string = 'Reject';break;
            case 3 : $string = 'Process';break;
            default : $string = Yii::t('app','process');break;    
        }
        return $string;
    }
    
    public function getSaveRequest($status=false){
        if($this->validate()){
            if($this->employee_id){
                //set status to 0 /completed
                $this->status = 1;
				$this->leave_approved=1;
                $this->getSaveData($this->employee_id);
                $data = $this->getLeaveSingleDataByEmployee($this->employee_id);
                $request = \app\models\LeaveLog::getSaveData($data->leave_id,'Request','Request By '.Yii::$app->user->identity->EmployeeFirstName);
                $approved = \app\models\LeaveLog::getSaveData($data->leave_id,'Approved By HRD','Approved By '.Yii::$app->user->identity->EmployeeFirstName);
                $completed = \app\models\LeaveLog::getSaveData($data->leave_id,'Completed','Completed By '.Yii::$app->user->identity->EmployeeFirstName);
			}
            else {
                $employees = \app\models\Employee::getAllEmployee();
                foreach($employees as $row){
                    //set status to 0 /completed
                    $this->status = 1;
					$this->leave_approved=1;
                    $this->getSaveData($row->employee_id);
                    $data = $this->getLeaveSingleDataByEmployee($row->employee_id);
                    $request = \app\models\LeaveLog::getSaveData($data->leave_id,'Request','Request By '.Yii::$app->user->identity->EmployeeFirstName);
                    $approved = \app\models\LeaveLog::getSaveData($data->leave_id,'Approved By HRD','Approved By '.Yii::$app->user->identity->EmployeeFirstName);
                    $completed = \app\models\LeaveLog::getSaveData($data->leave_id,'Completed','Completed By '.Yii::$app->user->identity->EmployeeFirstName);
                }
            }
            return true;
        }
        
    } **/
        
    
        
    /**
     * Selection All Employee
     *  Get Save All Employee
     */    
    public function getSaveSelectionLeaveRequest() {   
    	if($this->employee_id == 1) {
    		$employees = Employee::getAllEmployee();
    		if($employees) {
    			foreach ($employees as $key => $employee) {
    				$this->getSaveLeaveRequest($employee->employee_id,$this->leave_status);
    			}
    		} else {
    			$this->getSaveLeaveRequest($this->employee_id,$this->leave_status);
    		}
    	}
    		
    }
    
        
    /** 
     * Save/Update The Process 
     * @param integer $employee_id
     * @return boolean
     */
    public function getSaveLeaveRequest($employee_id = 0,$status = 0) {
    	if($this->validate()) {
    		
    		$is_app_user1 = false;
    		$is_app_hrd = false;
    		$is_app_partner = false;
    		$status = !$status ? self::$request : $status;
    		
    		switch ($status) {
    			case self::$completed : 
    				$is_app_user1 = true;
    				$is_app_hrd =true;
    				$is_app_partner = true;
    				$status = self::$completed;
    				break;
    			case self::$approve_partner :
    				$is_app_user1 = true;
    				$is_app_hrd =true;
    				$is_app_partner = true;
    				$status = self::$approve_partner;
    				break;
    			case self::$approve_hrd :
    				$is_app_user1 = true;
    				$is_app_hrd =true;
    				$is_app_partner = false;
    				$status = self::$approve_hrd;
    				break;
    			case self::$approve_manager :
    				$is_app_user1 = true;
    				$is_app_hrd =false;
    				$is_app_partner = false;
    				$status = self::$approve_manager;
    				break;
    		}
    			
    		
    		$model = new Leaves();
	    	$model->leave_type = $this->leave_type;
	    	$model->employee_id = $employee_id;
	    	$model->leave_status = $status;
	    	$model->leave_source = self::$leave_source;
	    	$model->leave_date = date('Y-m-d');
	    	$model->leave_date_from = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_from);
	    	$model->leave_date_to = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_to);
	    	$model->leave_total = $this->getRangeData()['count'];
	    	$model->leave_saldo_total = $this->leave_saldo_total;
	    	$model->leave_saldo_balanced = ($this->leave_saldo_total?$this->leave_saldo_total:0) - ($model->leave_total?$model->leave_total:0);
	    	$model->leave_range = $this->getRangeData()['description'];
	    	$model->leave_description = $this->leave_description;
	    	$model->leave_address = $this->leave_address;
	    	$model->leave_app_user1 = Employee::getField($employee_id,'EmployeeLeaveManager') ? Employee::getField($employee_id,'EmployeeLeaveManager') : 0;
	    	$model->leave_app_user1_status = ($is_app_user1 == false &&  Employee::getField($employee_id,'EmployeeLeaveManager')) ? self::$approval_progress : self::$approval_yes;
	    	$model->leave_app_hrd = Employee::getField($employee_id,'EmployeeLeaveHRD') ? Employee::getField($employee_id,'EmployeeLeaveHRD') : 0;
	    	$model->leave_app_hrd_status = ($is_app_hrd == false &&  Employee::getField($employee_id,'EmployeeLeaveHRD')) ? self::$approval_progress : self::$approval_yes;
	    	$model->leave_app_pic = Employee::getField($employee_id,'EmployeeLeavePartner') ? Employee::getField($employee_id,'EmployeeLeavePartner') : 0;
	    	$model->leave_app_pic_status = ($is_app_partner == false && Employee::getField($employee_id,'EmployeeLeavePartner')) ? self::$approval_progress : self::$approval_yes;
	    	$model->leave_created_by = Yii::$app->user->getId();
	    	$model->leave_created_date = date('Y-m-d H:i:s');
	    	
	    	if($model->insert()) {
	    		if(($model->leave_app_user1) && ($is_app_user1 == false)) 
	    			$approval = $model->leave_app_user1;
	    		else if(($model->leave_app_hrd) && ($is_app_hrd == false))
	    			$approval = $model->leave_app_hrd;
	    		else if(($model->leave_app_pic) && ($is_app_partner == false))
	    			$approval = $model->leave_partner;
	    		else 
	    			$approval = 0;
	    		
	    		/** 
	    		 *  Link to Timesheet
	    		 */
	    		$this->setLinkTimesheet($employee_id, $approval);	
	    		
	    		/** 
	    		 * Save to Log Lestatus
	    		 * @Set to leave Log
	    		 */	
	    		LeaveLog::set([
	    				'id' => $model->leave_id,
	    				'status'=> $status,
	    				'title' => Yii::t('app','request from').' '.Yii::$app->user->identity->EmployeeFirstName,
	    				'description' => $this->leave_description,
	    				'approval' =>  $approval,
	    				'approval_name' => Employee::getField($approval,"EmployeeFirstName")
	    		]);
	    		return true;
	    	}
    	}
    	
    	return false;
    }
    
    
    /**
     *  Link to Timesheet
     */
    public function setLinkTimesheet($employee_id,$approval){
    	if($this->validate()){
    		$total_days = $this->getRangeData()['count'];
    		$data_days = $this->getRangeData()['description'];
    		
    		if($total_days > 30)
    			$timesheet_job_id = 10;
    		else if($this->leave_type == self::$cuti_tahunan)
    			$timesheet_job_id = 11;
    		else if($this->leave_type == self::$cuti_bersama)
    			$timesheet_job_id = 500;
    		else if($this->leave_type == self::$cuti_khusus)
    			$timesheet_job_id = 10;
    		
    		$sheet_data = explode(",", $data_days);
    		for($i=0;$i<$total_days;$i++) {
    			//set date convert to Y-m-d
    			$ddate = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$sheet_data[$i]);
    			$date = new \DateTime($ddate);
    			
    			//Timeshet Week
    			$timesheetWeek = new TimesheetStatus();
    			$find = $timesheetWeek->findOne([
    				'employee_id' => $employee_id,
    				'week' => $date->format("W"),
    				'year' => $date->format("Y"),
    			]);
    			
    			if(!$find) {
    				$timesheetWeek->week = $date->format("W"); 	
    				$timesheetWeek->year =  $date->format("Y");
    				$timesheetWeek->employee_id =  $employee_id;
    				$timesheetWeek->drequest = date('Y-m-d H:i:s');
    				$timesheetWeek->approval_id = $approval;
    				$timesheetWeek->timesheet_approval = 4;
    				$timesheetWeek->sysdate = date('Y-m-d H:i:s');
    				$timesheetWeek->sysuser = Yii::$app->user->getId();
    				$timesheetWeek->insert();
    				
    			} else {
    				$timesheetWeek = $find;
    			}
    			
    			// Timesheet Save
    			$timesheet = new Timesheet();
    			$timesheet->timesheet_status_id = $timesheetWeek->timesheet_status_id;
    			$timesheet->project_id = 1;
    			$timesheet->job_id = $timesheet_job_id;
    			$timesheet->employee_id =  $employee_id;
    			$timesheet->week =  $date->format("W");
    			$timesheet->year =  $date->format("Y");
    			$timesheet->hour =  8;
    			$timesheet->overtime =  0;
    			$timesheet->cost =  8;
    			$timesheet->transport_type =  1;
    			$timesheet->transport_cost =  0;
    			$timesheet->transport_paid =  1;
    			$timesheet->notes = $this->leave_description;
    			$timesheet->timesheetdate = $ddate;
    			$timesheet->timesheet_approval = 0;
    			$timesheet->source = self::$leave_source;
    			$timesheet->sysdate = date('Y-m-d H:i:s');
    			$timesheet->sysuser = Yii::$app->user->getId();
    			$timesheet->insert();	
    		}
    		
    	}
    }
    /**
     *  Process to get Range Total Data
     *  return @array
     */
    private function getRangeData() {
    	$data = [];
    	$data['description'] = '';
    	$data['count'] = 0;
    	$date_from = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_from);
    	$date_to = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_to);
    	$range = strtotime($date_to) -  strtotime($date_from);
    	$range = ($range/(60*60*24)) + 1;
    	
    	$new_date = $date_from;
    	for($i=1;$i<=$range;$i++) {
    		if(date('N', strtotime($new_date))< 6 ) {
    			//check holiday
    			$holiday = \app\models\Holiday::find()->where(['holiday_date' => $new_date])->one();
    			
    			if(!$holiday) {
    				$data['count']+=1;
    				$data['description'].= Yii::$app->formatter->asDatetime($new_date,"php:d/m/Y").",";
    			}
    		}
    		
    		//counter for new date
    		$new_date = date('Y-m-d', strtotime('+1 days', strtotime($new_date)));
    	}
    	
    	return $data;
    	
    }
    
    /** 
     * Process to Approval By Manager/HRD/Partner/Director
     * @param Leave ID $id
     */
    
    public function getApprovalLeaveRequest($id) {
    	if($this->validate()) {
    		$model = new Leaves();
    		$model = $model->findOne($id);
    		$model->leave_updated_date = date("Y-m-d H:i:s");
    		$model->leave_updated_by = Yii::$app->user->getId();
    		//choice for employee approval list
    		switch (Yii::$app->user->getId()) {
    			case $model->leave_app_user1 : 
    				$model->leave_app_user1_status = $this->leave_approval;
    				$model->leave_app_user1_note = $this->leave_note;
    				$model->leave_app_user1_date = date('Y-m-d H:i:s');
    				$model->leave_status = $this->leave_approval = self::$approval_yes ? self::$approve_manager : self::$inapprove_manager;
    				$next_approval = $model->leave_app_hrd;
    				break;
    			case $model->leave_app_hrd :
    				$model->leave_app_hrd_status = $this->leave_approval;
    				$model->leave_app_hrd_note = $this->leave_note;
    				$model->leave_app_hrd_date = date('Y-m-d H:i:s');
    				$model->leave_status = $this->leave_approval = self::$approval_yes ? self::$approve_hrd : self::$inapprove_hrd;
    				$next_approval = $model->leave_app_pic;
    				break;
    			case $model->leave_app_pic :
    				$model->leave_app_pic_status = $this->leave_approval;
    				$model->leave_app_pic_note = $this->leave_note;
    				$model->leave_app_pic_date = date('Y-m-d H:i:s');
    				$model->leave_status = $this->leave_approval = self::$approval_yes ? self::$approve_partner : self::$inapprove_partner;
    				$next_approval = $model->leave_app_hrd;
    				break;
    		}
    		
    		if ($model->update()) {
				/**
				 * Save to Log Lestatus
				 * @Set to leave Log
				 */
				LeaveLog::set ( [ 
						'id' => $model->leave_id,
						'status' => $model->leave_status,
						'title' => Yii::t ( 'app', 'approved by' ) . ' ' . Yii::$app->user->identity->EmployeeFirstName,
						'description' => $this->leave_note,
						'approval' => $next_approval,
						'approval_name' => Employee::getField ( $next_approval, "EmployeeFirstName" ) 
				] );		
    			return true;
    		}
    			
    		
    	}
    	return false;
    }
    
    /**
     * Process to Approval Completed By HRD & Management
     * @param Leave ID $id
     */
    
    public function getCompletedLeaveRequest($id) {
    	if($this->validate()) {
    		$model = new Leaves();
    		$model = $model->findOne($id);
    		//choice for employee approval list
    		switch ($this->leave_approval) {
    			case self::$completed :
    				$model->leave_status = self::$completed;
    				$model->leave_completed_note = $this->leave_note;
    				break;
    			case self::$reject :
    				$model->leave_status = self::$reject;
    				$model->leave_completed_note = $this->leave_note;
    				break;
    			case self::$cancel :
    				$model->leave_status = self::$reject;
    				$model->leave_cancel_request = self::$cancel;
    				$model->leave_cancel_note = $this->leave_note;	 
    				break;
    		}
    
    		if ($model->update()) {
    			/**
    			 * Save to Log Lestatus
    			 * @Set to leave Log
    			 */
    			LeaveLog::set ( [
    					'id' => $model->leave_id,
    					'status' => $model->leave_status,
    					'title' => Yii::t ( 'app', 'completed by' ) . ' ' . Yii::$app->user->identity->EmployeeFirstName,
    					'description' => $this->leave_note,
    					'approval' => 0,
    					'approval_name' => ""
    			] );
    			return true;
    		}
    		 
    
    	}
    	return false;
    }
    
    /**
    public function getSaveMyRequest() {
        /** Leave Approval Status & name For Request
        $request = Yii::$app->user->identity->EmployeeLeaveSenior; 
        if(!$request) $request = Yii::$app->user->identity->EmployeeLeaveManager;
        if(!$request) $request = Yii::$app->user->identity->EmployeeLeaveHRD;
        /** End of Leave Approval Status & name For Request
        $this->status = 5;
        $save = $this->getSaveData(Yii::$app->user->getId(),TRUE);
        if($save){
            $queryApp = \app\models\Employee::findOne($request);
            $data = $this->getLeaveSingleDataByEmployee(Yii::$app->user->getId());
            $status = \app\models\LeaveLog::getSaveData($data->leave_id,'Request by','Request By '.Yii::$app->user->identity->EmployeeFirstName);
            $request = \app\models\LeaveLog::getSaveData($data->leave_id,'Request to',$this->getStringRequest($data->leave_request)." ".$queryApp->EmployeeID.' - '.$queryApp->EmployeeFirstName);
            return true;
        }
        return false;
    }
    
    public function getSaveData($employee_id,$status=FALSE){
        if($this->validate()){
            //calculate range date from to date to
            $leave_date_from = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_from);
            $leave_date_to = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_to);
            $leave_range = strtotime($leave_date_to) -  strtotime($leave_date_from);
            $range = ($leave_range/(60*60*24))+1;
            
            if($range > 0){
                Yii::$app->session->setFlash('msg','<div class="notice bg-success marker-on-left">'.Yii::t('app/message','msg date range is not correct').'</div>');
                return false;
            }
            
            $x = 0;
            $date_char='';
            $newdate = $leave_date_from;
            if($range>=60)
                $timesheetLeaveJobId = "10";
            else
                $timesheetLeaveJobId = "11";
                
            for($i=1;$i<=$range;$i++){
                //$date_char.='';
                //check sunday & saturday
                if(date('N', strtotime($newdate))<6){
                    //check holiday
                    $holiday = \app\models\Holiday::find()
                    ->where(['holiday_date' => $newdate])
                    ->one();
					
                    if(!$holiday) {
                        //check last date leave
                        $lastLeave = static::find()
                        ->andFilterWhere(['like', "leave_range", Yii::$app->formatter->asDatetime($newdate,"php:d/m/Y")])
                        ->andWhere(['employee_id' => Yii::$app->user->getId()])
                        ->one();
                        
                        if(!$lastLeave) {
                            $x++;
                            $date_char.= Yii::$app->formatter->asDatetime($newdate,"php:d/m/Y").",";
                        }
                        
                        /** Start Patch For Timesheet 
                        $ddate = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$newdate);
                        $date = new \DateTime($ddate);
                        $timesheet = [
                            'week'  =>  $date->format("W"),
                            'year'  =>  $date->format("Y"),
                            'project_id' => 1,
                            'job_id' => 11,
                            'notes' => "Leave :".$this->leave_description,
                            'date' => $newdate,
                            'employee_id' => Yii::$app->user->getId(),
                            'hour' => 8,
                            'overtime' => 0,
                            'transport_type' => 1,
                            'cost' => 0,
                            'approval' => 4
                        ];
                        
                        $timesheetModel = new \app\models\Timesheet();
                        $timesheetStatusModel = new \app\models\TimesheetStatus();
                        //counter for timesheet status id
                        $timesheet_status_id = 0;
                        $EmployeeWeek = $timesheetStatusModel->checkTimesheetWeek($timesheet);
                        
                        if (!$EmployeeWeek)
                            $timesheet_status_id = $timesheetStatusModel->insertTimesheetWeekly($timesheet);
                        else 
                            $timesheet_status_id = $EmployeeWeek->timesheet_status_id;
                        
                        $EmployeeTimesheet = $timesheetModel->checkTimesheetByEmployee($timesheet);
                        
                        if(!$EmployeeTimesheet)
                            $insertTimesheet =  $timesheetModel->saveTimesheet($timesheet,$timesheet_status_id);    
                        
                       /** End of Patch For Timesheet
                    }
                    
                }
                //counter
                $newdate = date('Y-m-d', strtotime('+1 days', strtotime($newdate)));
            }
            
            if($x==0){
                $x=1;
                $date_char.=  $this->leave_date_from;
    
                /** Start Patch For Single Timesheet
                $timesheetLeaveJobId = "11";
                $date2 = new \DateTime($leave_date_from);
                $timesheet = [
                    'week'  =>  $date2->format("W"),
                    'year'  =>  $date2->format("Y"),
                    'project_id' => 1,
                    'job_id' => 11,
                    'notes' => $this->leave_description,
                    'date'  => $leave_date_from,
                    'employee_id' => Yii::$app->user->getId(),
                    'hour' => 8,
                    'overtime' => 0,
                    'transport_type' => 1,
                    'cost' => 0,
                    'approval' => 4
                ];
                
                $timesheetModel = new \app\models\Timesheet();
                $timesheetStatusModel = new \app\models\TimesheetStatus();
                
                //counter for timesheet status id
                $timesheet_status_id = 0;
                
                $EmployeeWeek = $timesheetStatusModel->checkTimesheetWeek($timesheet);
                        
                if (!$EmployeeWeek)
                    $timesheet_status_id = $timesheetStatusModel->insertTimesheetWeekly($timesheet);
                else 
                    $timesheet_status_id = $EmployeeWeek->timesheet_status_id;
                        
                $EmployeeTimesheet = $timesheetModel->checkTimesheetByEmployee($timesheet);
                        
                if(!$EmployeeTimesheet)
                    $insertTimesheet =  $timesheetModel->saveTimesheet($timesheet,$timesheet_status_id);    
                /** End of Patch For Single Timesheet 
            }
            
            $model = new Leaves();
            $model->employee_id = $employee_id;
            
            if($this->leave_approved)
                $model->leave_approved = $this->leave_approved; //process 
            else
                $model->leave_approved = 3;
            
            $model->leave_type = $this->leave_type;
            $model->leave_status = $this->status;
            $model->leave_date = date('Y-m-d');
            $model->leave_date_from = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_from);
            $model->leave_date_to = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->leave_date_to);
            $model->leave_total = $x;
            $model->leave_range = $date_char;
            $model->leave_description = $this->leave_description;
            $model->leave_address = $this->leave_address;
            $model->leave_created_by = Yii::$app->user->getId();
            $model->leave_created_date = date('Y-m-d H:i:s');
            
            if($status==TRUE){
                $setting = \app\models\Employee::findOne($employee_id);
                
                if($setting->EmployeeLeaveSenior){
                    $model->leave_app_user1 = $setting->EmployeeLeaveSenior;
                    $model->leave_app_user1_status = 2;
                } else {
                    $model->leave_app_user1 = 0;
                    $model->leave_app_user1_status = 1;
                }
                
                if($setting->EmployeeLeaveManager){
                    $model->leave_app_user2 = $setting->EmployeeLeaveManager;
                    $model->leave_app_user2_status = 2;
                } else {
                    $model->leave_app_user2 = 0;
                    $model->leave_app_user2_status = 1;
                }
                /*
                 *  1 => 'Completed',
                    2 => 'Request to Partner' ,
                    3 => 'Request to HRD',
                    4 => 'Request to Manager',
                    5 => 'Request to Senior',
                
                
                if($setting->EmployeeLeavePartner)
                    $request = 2;
                if($setting->EmployeeLeaveHRD)
                    $request = 3;
                if($setting->EmployeeLeaveManager)
                    $request = 4;  
                if($setting->EmployeeLeaveSenior)
                    $request = 5;  
                
                $model->leave_request = $request;
                $model->leave_app_hrd = $setting->EmployeeLeaveHRD;
                $model->leave_app_hrd_status = 2;
                $model->leave_app_pic = $setting->EmployeeLeavePartner;
                $model->leave_app_pic_status = 2;
                
            
            } else {
                $model->leave_request = 1;
                $model->leave_app_user1 = Yii::$app->user->getId();
                $model->leave_app_user1_status = 1;
                $model->leave_app_user2 = Yii::$app->user->getId();
                $model->leave_app_user2_status = 1;
                $model->leave_app_hrd = Yii::$app->user->getId();
                $model->leave_app_hrd_status = 1;
                $model->leave_app_pic = Yii::$app->user->getId();
                $model->leave_app_pic_status = 1;
            }
		
            // insert the data 
            $model->insert();
			
			// patch leave Approved 1 
			if($this->leave_approved==1){
				//link to EmployeeLeaveUse
				$employee = \app\models\Employee::findOne($employee_id);
				if(!$employee->EmployeeLeaveUse){
					$update = \app\models\Employee::updateAll(['EmployeeLeaveUse' => 0],['employee_id'=>$employee_id]);
				}	
				//update counter for use employee if use=0
				\app\models\Employee::updateAllCounters(['EmployeeLeaveUse' => $x],['employee_id' => $employee_id]);
			}
			
            return true;
        }
    }
    
    public function getApprovalRequest($id){
        if($this->validate()){
            //find
            $query = Leaves::findOne($id);
            //update initial
            $model = new Leaves();
            $model = $model->findOne($query->leave_id);
            //check if request to senior
            if($query->leave_request == 5) {
                $model->leave_app_user1_status = $this->leave_approval;
                $model->leave_app_user1_note = $this->leave_note;
                $model->leave_app_user1_date = date('Y-m-d H:i:s');
                if($this->leave_approval==3)
                    $approval = 14;
                else
                    $approval = 4;                
                $model->leave_status = $approval;
                $model->leave_request = ($query->leave_request - 1);
                $model->update();
                //update for status
                $queryApp = \app\models\Employee::findOne($model->leave_app_user2);
                $status = \app\models\LeaveLog::getSaveData($query->leave_id,$this->getStringStatus($approval).' : '.Yii::$app->user->identity->EmployeeID.' - '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note?$this->leave_note:Yii::t('app','no comment'));
                $request = \app\models\LeaveLog::getSaveData($query->leave_id,'Request to',$this->getStringRequest(($query->leave_request-1))." ".$queryApp->EmployeeID.' - '.$queryApp->EmployeeFirstName);
                return true;
            }
            elseif($query->leave_request == 4){
                $model->leave_app_user2_status = $this->leave_approval;
                $model->leave_app_user2_note = $this->leave_note;
                $model->leave_app_user2_date = date('Y-m-d H:i:s');
                if($this->leave_approval==3)
                    $approval = 14;
                else
                    $approval = 4;                
                $model->leave_status = $approval;
                $model->leave_request = ($query->leave_request-1);
                $model->update();
                //update for status
                $queryApp = \app\models\Employee::findOne($model->leave_app_hrd);
                $status = \app\models\LeaveLog::getSaveData($query->leave_id,$this->getStringStatus($approval).' : '.Yii::$app->user->identity->EmployeeID.' - '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note?$this->leave_note:Yii::t('app','no comment'));
                $request = \app\models\LeaveLog::getSaveData($query->leave_id,'Request to',$this->getStringRequest(($query->leave_request-1))." ".$queryApp->EmployeeID.' - '.$queryApp->EmployeeFirstName);
                return true;
            }
            elseif($query->leave_request == 2){
                $model->leave_app_pic_status = $this->leave_approval;
                $model->leave_app_pic_note = $this->leave_note;
                $model->leave_app_pic_date = date('Y-m-d H:i:s');
                if($this->leave_approval==3)
                    $approval = 12;
                else
                    $approval = 2;                
                $model->leave_status = $approval;
                $model->leave_request = ($query->leave_request-1);
                $model->update();
                //update for status
                $queryApp = \app\models\Employee::findOne($model->leave_app_hrd);
                $status = \app\models\LeaveLog::getSaveData($query->leave_id,$this->getStringStatus($approval).' : '.Yii::$app->user->identity->EmployeeID.' - '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note?$this->leave_note:Yii::t('app','no comment'));
                $request = \app\models\LeaveLog::getSaveData($query->leave_id,'Request to',$this->getStringRequest(($query->leave_request-1))." ".$queryApp->EmployeeID.' - '.$queryApp->EmployeeFirstName);
                return true;
            }
            else {
                $model->leave_app_pic_status = $this->leave_approval;
                $model->leave_app_pic_note = $this->leave_note;
                $model->leave_app_pic_date = date('Y-m-d H:i:s');
                if($this->leave_approval==3)
                    $approval = 13;
                else
                    $approval = 3;                
                $model->leave_status = $approval;
                $model->update();
                //update for status
                $queryApp = \app\models\Employee::findOne($model->leave_app_hrd);
                $status = \app\models\LeaveLog::getSaveData($query->leave_id,$this->getStringStatus($approval).' : '.Yii::$app->user->identity->EmployeeID.' - '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note?$this->leave_note:Yii::t('app','no comment'));
                $request = \app\models\LeaveLog::getSaveData($query->leave_id,'Request to',$this->getStringRequest(($query->leave_request-1))." ".$queryApp->EmployeeID.' - '.$queryApp->EmployeeFirstName);
                return true;
            }
    
        }
        return false;
    }
    
    public function getPartnerApprovalRequest($id){
        if($this->validate()){
            $model = new Leaves();
            $model = $model->findOne($id);
            if($this->leave_approval==1){
                $model->leave_app_pic_status = 1;
                $model->leave_app_pic_note = $this->leave_note;
                $model->leave_app_pic_date = date('Y-m-d H:i:s');
                $model->leave_request = 1;
                $model->leave_status = 2;
                
                /** Linked to timesheet
                $leave = static::findOne($id);
                $employee_id = $leave->employee_id;
                $leave_range = strtotime($leave->leave_date_to) -  strtotime($leave->leave_date_from);
                $range = ($leave_range/(60*60*24)) + 1;
                
                $newdate = $leave->leave_date_from;
                
                if($range>59)
                    $jobId = 10;
                else
                    $jobId = 11;
                
                for($i=1;$i<=$range;$i++){
                    //variabel
                    $timesheet = [
                        'date' => $newdate,
                        'project_id' => 1,
                        'employee_id' => $employee_id,
                        'job_id' => $jobId,
                    ];
                    $timesheetModel = new \app\models\Timesheet();
                    $data = $timesheetModel->checkTimesheetByApproval($timesheet);
                    
                    if($data){
                        $timesheetModel->UpdateAll(['timesheet_approval' => 2],['timesheetdate' => $timesheet['date'],'project_id' => $timesheet['project_id'],'job_id' => $timesheet['job_id']]);
                        //check status timesheetModel
                        $timesheetStatusModel = new \app\models\TimesheetStatus();
                        $timesheetStatusModel->UpdateAll(['timesheet_approval' => 2],['timesheet_status_id' => $data->timesheet_status_id]);
                    }
                    
                    $newdate = date('Y-m-d', strtotime('+1 days', strtotime($newdate)));
                }
                /** Linked to timesheet
                
            }
            elseif($this->leave_approval==3){
                $model->leave_app_pic_status = 3;
                $model->leave_app_pic_note = $this->leave_note;
                $model->leave_app_pic_date = date('Y-m-d H:i:s');
                $model->leave_request = 1;
                $model->leave_status = 12;
                
                /** Linked to timesheet for Returned 
                $leave = static::findOne($id);
                $leave_range = strtotime($leave->leave_date_to) -  strtotime($leave->leave_date_from);
                $range = ($leave_range/(60*60*24)) + 1;
                
                $newdate = $leave->leave_date_from;
                
                if($range>59)
                    $jobId = 10;
                else
                    $jobId = 11;
                
                for($i=1;$i<=$range;$i++){
                    //variabel
                    $timesheet = [
                        'date' => $newdate,
                        'project_id' => 1,
                        'employee_id' => $employee_id,
                        'job_id' => $jobId,
                    ];
                    $timesheetModel = new \app\models\Timesheet();
                    $data = $timesheetModel->checkTimesheetByApproval($timesheet);
                    
                    if($data){
                        $timesheetModel->UpdateAll(['timesheet_approval' => 3],['timesheetdate' => $timesheet['date'],'project_id' => $timesheet['project_id'],'job_id' => $timesheet['job_id']]);
                        //check status timesheetModel
                        $timesheetStatusModel = new \app\models\TimesheetStatus();
                        $timesheetStatusModel->UpdateAll(['timesheet_approval' => 3],['timesheet_status_id' => $data->timesheet_status_id]);
                    }
                    
                    $newdate = date('Y-m-d', strtotime('+1 days', strtotime($newdate)));
                }
                /** Linked to timesheet
                
            }
            $model->update();
            
            $status = \app\models\LeaveLog::getSaveData($id,$this->getStringStatus($this->leave_approval).' : '.Yii::$app->user->identity->EmployeeID.' - '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note);
            return true;    
            
        }
    }
    
    public function getHRApprovalRequest($id){
        if($this->validate()){
            $query = Leaves::findOne($id);
            $model = new Leaves();
            $model = $model->findOne($query->leave_id);
            if($this->leave_approval==1){
                $model->leave_app_hrd_status = 1;
                $model->leave_app_hrd_note = $this->leave_note;
                $model->leave_app_hrd_date = date('Y-m-d H:i:s');
                $model->leave_request = 2;
                $model->leave_status = 3;
            }
            elseif($this->leave_approval==3){
                $model->leave_app_hrd_status = 3;
                $model->leave_app_hrd_note = $this->leave_note;
                $model->leave_app_hrd_date = date('Y-m-d H:i:s');
                $model->leave_request = 2;
                $model->leave_status = 13;
            }
            elseif($this->leave_approval==4){
                $model->leave_app_hrd_status = 1;
                $model->leave_app_hrd_note = $this->leave_note;
                $model->leave_app_hrd_date = date('Y-m-d H:i:s');
                $model->leave_request = 2;
                $model->leave_status = 3;
            }
            elseif($this->leave_approval==5){
                $model->leave_app_hrd_status = 3;
                $model->leave_app_hrd_note = $this->leave_note;
                $model->leave_app_hrd_date = date('Y-m-d H:i:s');
                $model->leave_request = 2;
                $model->leave_status = 13;
            }
            $model->leave_updated_by = Yii::$app->user->getId();
            $model->leave_updated_date = date('Y-m-d H:i:s');
            $model->update();
            
            //update for status
            if(($this->leave_approval==1) || ($this->leave_approval==3) ){
                //check approval partner & hrd
                $queryApp = \app\models\Employee::findOne($model->leave_app_pic);
                if($this->leave_approval==1) $approval = 3; // 3 approve by hrd
                if($this->leave_approval==3) $approval = 13; // 13 not approved by hrd
                $status = \app\models\LeaveLog::getSaveData($query->leave_id,$this->getStringStatus($approval).' : '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note?$this->leave_note:Yii::t('app','no comment'));
                $request = \app\models\LeaveLog::getSaveData($query->leave_id,'Request to',$this->getStringRequest(2).' '.$queryApp->EmployeeID.' - '.$queryApp->EmployeeFirstName);
            }
            
            if(($this->leave_approval==4) || ($this->leave_approval==5) ){
                //check approval status
                $queryApp = \app\models\Employee::findOne($model->leave_app_pic);
                if($this->leave_approval==4) $approval = 3;
                if($this->leave_approval==5) $approval = 13;
                
                $status = \app\models\LeaveLog::getSaveData($query->leave_id,$this->getStringStatus($approval).' : '.Yii::$app->user->identity->EmployeeFirstName,$this->leave_note?$this->leave_note:Yii::t('app','no comment'));
                $request = \app\models\LeaveLog::getSaveData($query->leave_id,'Request to',$this->getStringRequest(2).' '.$queryApp->EmployeeID.' - '.$queryApp->EmployeeFirstName);
            }
            return true;
        }
        return false;
    }
    
    
    public function getLeaves($params)
    {
        $query = static::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','e.employeefirstname','e.employeetitle','l.leave_range',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description','leave_approved',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total','l.leave_status',
                  "CONCAT(EmployeeFirstname,' ',e.EmployeeMiddleName,' ',EmployeeLastName) as employee_name"])
        ->from('leaves l')
        ->join('inner join','employee e','e.employee_id = l.employee_id');
        
        if (!$this->load($params))
        {
            return $query->all();
        }
        
        $query->andFilterWhere(['like', "CONCAT(e.EmployeeID,' ',e.EmployeeFirstname,' ',e.EmployeeMiddleName,' ',EmployeeLastName)",  $this->employee_name]);
        
        if($this->leave_status)
        {
            $query->andFilterWhere(['leave_status'=>$this->leave_status]);
        }
        
        if($this->leave_date_type == 1)
        {
            if($this->leave_date_from)
            {
                $query->andFilterWhere(['>=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_from]);
            }
            
            if($this->leave_date_from)
            {
                $query->andFilterWhere(['<=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_to]);
            }
        }
        else
        {
            if($this->leave_date_from)
            {
                $date_from = $this->leave_date_from;
                $date_to = $this->leave_date_to;
                $date_from = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$date_from);
                $date_to = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$date_to);
                $range = strtotime($date_to) -  strtotime($date_from);
                $range = ($range/(60*60*24)) + 1;
                if($range>0)
                    $total = $range;
                else
                    $total = 0;
                
                
                $x = 0;
                $date_char='';
                $newdate = $date_from;    
                for($i=1;$i<=$total;$i++)
                {
                    if(date('N', strtotime($newdate))<6)
                    {
                        //check holiday
                        $holiday = \app\models\Holiday::find()
                        ->where(['holiday_date' => $newdate])
                        ->one();
                        
                        if(!$holiday)
                        {
                            $x++;
                            $string = Yii::$app->formatter->asDatetime($newdate,"php:d/m/Y");
                            $query->orFilterWhere(['like', "leave_range",  $string]);
                        }
                    }
                    
                    //counter
                    $newdate = date('Y-m-d', strtotime('+1 days', strtotime($newdate)));
                }
                
            }
        }
        
        return $query->all();
        
    }
    
    public function getLeaveManagement($params){
        
        $query = Leaves::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','e.employeefirstname','l.leave_range',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description','leave_approved',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total','l.leave_status'])
        ->from('leaves l')
        ->join('inner join','employee e','e.employee_id = l.employee_id');
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['leave_id'=>SORT_DESC]],
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', "CONCAT(e.EmployeeID,' ',e.EmployeeFirstname,' ',e.EmployeeMiddleName,' ',EmployeeLastName)",  $this->employee_name]);
        if($this->leave_status) $query->andFilterWhere(['leave_status'=>$this->leave_status]);
        
        if($this->leave_date_type==1){
            if($this->leave_date_from) $query->andFilterWhere(['>=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_from]);
            if($this->leave_date_from) $query->andFilterWhere(['<=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_to]);
        }
        else {
            if($this->leave_date_from) {
                $date_from = $this->leave_date_from;
                $date_to = $this->leave_date_to;
                $date_from = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$date_from);
                $date_to = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$date_to);
                $range = strtotime($date_to) -  strtotime($date_from);
                $range = ($range/(60*60*24)) + 1;
                if($range>0)
                    $total = $range;
                else
                    $total = 0;
                
                
                $x = 0;
                $date_char='';
                $newdate = $date_from;    
                for($i=1;$i<=$total;$i++){
                    if(date('N', strtotime($newdate))<6){
                        //check holiday
                        $holiday = \app\models\Holiday::find()
                        ->where(['holiday_date' => $newdate])
                        ->one();
                        
                        if(!$holiday){
                            $x++;
                            $string = Yii::$app->formatter->asDatetime($newdate,"php:d/m/Y");
                            $query->orFilterWhere(['like', "leave_range",  $string]);
                        }
                    }
                    
                    //counter
                    $newdate = date('Y-m-d', strtotime('+1 days', strtotime($newdate)));
                }
                
            }
        }
        
        //if($this->leave_approved) $query->andWhere(['leave_approved'=>$this->leave_approved]);
        
        return $dataProvider;
    }
    
    
    
    public function getLeaveApproval($params){
        $query = Leaves::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','e.employeefirstname','l.leave_range',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total','l.leave_status'])
        ->from('leaves l')
        ->join('inner join','employee e','e.employee_id = l.employee_id');
        
        if(\app\models\Employee::isAuditorPartner())
            $query->andWhere('l.leave_app_pic= '.Yii::$app->user->getId().' AND l.leave_request = 2');
        
        if(\app\models\Employee::isAuditorManager())
            $query->andWhere('(l.leave_app_user1= '.Yii::$app->user->getId().' OR l.leave_app_user2= '.Yii::$app->user->getId().') AND l.leave_request = 4');
        
        if(\app\models\Employee::isAuditorSenior())
            $query->andWhere('l.leave_app_user1= '.Yii::$app->user->getId().' AND l.leave_request = 5');
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
    
        $query->andFilterWhere(['like', "CONCAT(e.EmployeeFirstname,' ',e.EmployeeMiddleName,' ',EmployeeLastName)",  $this->employee_name]);
        if($this->leave_status) $query->andWhere(['leave_status'=>$this->leave_status]);
        if($this->leave_date_from) $query->andFilterWhere(['>=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_from]);
        if($this->leave_date_from) $query->andFilterWhere(['<=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_to]);
        
        return $dataProvider;
    }
    
    public function getLeaveApprovalList($params){
        $query = Leaves::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','e.employeefirstname','l.leave_range',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total','l.leave_status'])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id');
        

        if(\app\models\Employee::isAuditorPartner())
            $query->andWhere('l.leave_app_pic= '.Yii::$app->user->getId());
        
        if(\app\models\Employee::isAuditorManager())
            $query->andWhere('(l.leave_app_user1= '.Yii::$app->user->getId().' OR l.leave_app_user2= '.Yii::$app->user->getId().')');
        
        if(\app\models\Employee::isAuditorSenior())
            $query->andWhere('l.leave_app_user1= '.Yii::$app->user->getId());
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
    
        $query->andFilterWhere(['like', "CONCAT(e.EmployeeFirstname,' ',e.EmployeeMiddleName,' ',EmployeeLastName)",  $this->employee_name]);
        if($this->leave_status) $query->andWhere(['leave_status'=>$this->leave_status]);
        if($this->leave_date_from) $query->andFilterWhere(['>=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_from]);
        if($this->leave_date_from) $query->andFilterWhere(['<=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_to]);
        
        return $dataProvider;
    }
    
    public function getLeaveHRDApproval($params){
        $query = Leaves::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','e.employeefirstname','l.leave_range',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description','leave_request',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total','l.leave_status'])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id')
        ->andWhere('l.leave_request=3 or l.leave_request =2');
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', "CONCAT(e.EmployeeID,' ',e.EmployeeFirstname,' ',e.EmployeeMiddleName,' ',EmployeeLastName)",  $this->employee_name]);
        if($this->leave_date_from) $query->andFilterWhere(['>=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_from]);
        if($this->leave_date_from) $query->andFilterWhere(['<=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_to]);
        
        return $dataProvider;
    }
    
    public function getLeaveSingleData($id){
        return Leaves::find()
        ->select(['l.leave_id','leave_date as sysdate','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','CONCAT(e.employeefirstname,\' \',e.employeemiddlename,\' \',e.employeelastname) as employee_name',
                  'l.leave_range','l.leave_type','l.leave_address','d.department','leave_status','leave_request','e.EmployeeEmail',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total',
                  'e.EmployeeLeaveTotal','e.EmployeeLeaveUse','(e.EmployeeLeaveTotal-e.EmployeeLeaveUse) as leave_over',
                  'leave_app_user1','leave_app_user2','leave_app_hrd','leave_app_pic','l.employee_id',
                  'CONCAT(u1.employeefirstname,\' \',u1.employeemiddlename,\' \',u1.employeelastname) as user1_name',
                  'u1.EmployeeEmail as user1_email',
                  'CONCAT(u2.employeefirstname,\' \',u2.employeemiddlename,\' \',u2.employeelastname) as user2_name',
                  'u2.EmployeeEmail as user2_email',
                  'CONCAT(hr.employeefirstname,\' \',hr.employeemiddlename,\' \',hr.employeelastname) as hrd_name',
                  'hr.EmployeeEmail as hrd_email',
                  'CONCAT(pic.employeefirstname,\' \',pic.employeemiddlename,\' \',pic.employeelastname) as pic_name',
                  'leave_app_user1_status','leave_app_user2_status','leave_app_hrd_status','leave_app_pic_status',
                  'DATE_FORMAT(l.leave_app_user1_date,\'%d/%m/%Y\')  leave_app_user1_date',
                  'DATE_FORMAT(l.leave_app_user2_date,\'%d/%m/%Y\')  leave_app_user2_date',
                  'DATE_FORMAT(l.leave_app_hrd_date,\'%d/%m/%Y\')  leave_app_hrd_date',
                  'DATE_FORMAT(l.leave_app_pic_date,\'%d/%m/%Y\')  leave_app_pic_date',
                  'leave_app_user1_note','leave_app_user2_note','leave_app_hrd_note','leave_app_pic_note'
                ])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id')
        ->join('left join','department d','d.department_id = e.department_id')
        ->join('left join','employee u1','u1.employee_id = l.leave_app_user1')
        ->join('left join','employee u2','u2.employee_id = l.leave_app_user2')
        ->join('left join','employee hr','hr.employee_id = l.leave_app_hrd')
        ->join('left join','employee pic','pic.employee_id = l.leave_app_pic')
        ->where(['l.leave_id'=>$id])
        ->one();
    }
    
    public function getLeaveSingleDataByEmployeeID($employee_id){
        return Leaves::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','CONCAT(e.employeefirstname,\' \',e.employeemiddlename,\' \',e.employeelastname) as employee_name',
                  'l.leave_range','l.leave_type','l.leave_address','d.department','leave_status',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total',
                  'e.EmployeeLeaveTotal','e.EmployeeLeaveUse','(e.EmployeeLeaveTotal-e.EmployeeLeaveUse) as leave_over',
                  'leave_app_user1','leave_app_user2','leave_app_hrd','leave_app_pic',
                  'CONCAT(u1.employeefirstname,\' \',u1.employeemiddlename,\' \',u1.employeelastname) as user1_name',
                  'CONCAT(u2.employeefirstname,\' \',u2.employeemiddlename,\' \',u2.employeelastname) as user2_name',
                  'CONCAT(hr.employeefirstname,\' \',hr.employeemiddlename,\' \',hr.employeelastname) as hrd_name',
                  'CONCAT(pic.employeefirstname,\' \',pic.employeemiddlename,\' \',pic.employeelastname) as pic_name',
                  'leave_app_user1_status','leave_app_user2_status','leave_app_hrd_status','leave_app_pic_status',
                  'DATE_FORMAT(l.leave_app_user1_date,\'%d/%m/%Y\')  leave_app_user1_date',
                  'DATE_FORMAT(l.leave_app_user2_date,\'%d/%m/%Y\')  leave_app_user2_date',
                  'DATE_FORMAT(l.leave_app_hrd_date,\'%d/%m/%Y\')  leave_app_hrd_date',
                  'DATE_FORMAT(l.leave_app_pic_date,\'%d/%m/%Y\')  leave_app_pic_date',
                  'leave_app_user1_note','leave_app_user2_note','leave_app_hrd_note','leave_app_pic_note'
                ])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id')
        ->join('left join','department d','d.department_id = e.department_id')
        ->join('left join','employee u1','u1.employee_id = l.leave_app_user1')
        ->join('left join','employee u2','u2.employee_id = l.leave_app_user2')
        ->join('left join','employee hr','hr.employee_id = l.leave_app_hrd')
        ->join('left join','employee pic','pic.employee_id = l.leave_app_pic')
        ->where(['l.employee_id'=>$employee_id])
        ->orderBy('l.leave_id DESC')
        ->one();
    }
    
    public function getLeavDataByEmployee($employee_id,$params){
        $query = Leaves::find()
        ->select(['l.leave_id','DATE_FORMAT(l.leave_date,\'%d/%m/%Y\') as leave_date','e.employeeid','e.employeefirstname','l.leave_range',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total','l.leave_status'])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id')
        ->where(['e.employee_id'=>$employee_id]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        if ((!$this->load($params)) && ($this->validate())) {
            return $dataProvider;
        }
        
        if($this->leave_date_from) $query->andFilterWhere(['>=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_from]);
        if($this->leave_date_from) $query->andFilterWhere(['<=', 'DATE_FORMAT(l.leave_date,\'%d/%m/%Y\')', $this->leave_date_to]);
        if($this->leave_status) $query->andWhere(['leave_status'=>$this->leave_status]);
        
        return $dataProvider;
    }
    
    public function getLeaveSingleDataByEmployee($employee_id){
        return Leaves::find()
        ->select(['l.leave_id','l.leave_date','e.employeeid','CONCAT(e.employeefirstname,\' \',e.employeemiddlename,\' \',e.employeelastname) as employee_name',
                  'l.leave_range','l.leave_type','l.leave_address','leave_request',
                  'DATE_FORMAT(l.leave_date_from,\'%d/%m/%Y\') as leave_date_from','l.leave_description',
                  'DATE_FORMAT(l.leave_date_to,\'%d/%m/%Y\') as leave_date_to','l.leave_total'])
        ->from('leaves l')
        ->join('left join','employee e','e.employee_id = l.employee_id')
        ->where(['l.employee_id'=>$employee_id])
        ->orderBy('l.leave_id DESC')
        ->one();
    }
    
    public static function ExpiredNotify(){
        $message = '';
        $hire_date = substr(Yii::$app->user->identity->EmployeeHireDate,0,10);
        $now_date =  date('Y-m-d');
        $range1 = \app\components\Common::dateRange($hire_date,$now_date);
        //$message.= "Jumlah Hari Pengabdian :". $hire_date;
        
        if($range1 >= 365) {
            $lyear = date('Y') - 1;
            $ldate = $lyear.substr($hire_date,4,6);
            
            // + 1 tahun untuk Expired
            $exlyear = substr($ldate,0,4)+1;
            $exldate = $exlyear.substr($ldate,4,6);
            
            $range2 = \app\components\Common::dateRange($now_date,$exldate);
            
            if($range2>0) 
                $ldate = $ldate;
            else 
                $ldate = $exldate;
            
            $expyear = substr($ldate,0,4) + 1;
            $expdate =  $expyear.substr($ldate,4,6);
            
            $range3 = \app\components\Common::dateRange($now_date,$expdate);
            $leave = Yii::$app->user->identity->EmployeeLeaveTotal - Yii::$app->user->identity->EmployeeLeaveUse;
            
            if( ($range3<=150) && ($leave > 0) )
                $message.= 'Hak Cuti Anda sebesar '.$leave.' Hari akan Kadaluarsa pada Tanggal '.\app\components\Common::MysqlDateToString($expdate);    
        
        }
        
        return $message;
        
    }
    
    public static function sumLastLeaveByEmployee($employee_id){
        $sqlquery = "
            SELECT SUM(leave_total) AS total
            FROM leaves
            WHERE employee_id = ".$employee_id."
            and leave_approved = 1
        ";
        return Leaves::findBySql($sqlquery)->one();
    }
    
    public static function sumLastLeave($employee_id,$date){
        $sqlquery = "
            SELECT SUM(leave_total) AS total
            FROM leaves
            WHERE employee_id = ".$employee_id."
            AND leave_date < '".$date."'
            and leave_approved = 1
        ";
        return Leaves::findBySql($sqlquery)->one();
    }**/
    
}    