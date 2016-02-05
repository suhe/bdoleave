<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Employee;


/**
 * Site controller
 */
class SiteController extends Controller {	
    public function actions() {
	}
	
	public function behaviors() {
		$subDomain = 'http://timesheet.local/';
		return [ 
			'corsFilter' => [ 
				'class' => \yii\filters\Cors::className (),
					'cors' => [ 
						'Origin' => [ 
							"*" 
						],
						'Access-Control-Request-Method' => [ 
							'GET' 
						],
						'Access-Control-Request-Headers' => [ 
							'*' 
						],
						'Access-Control-Allow-Credentials' => null,
						'Access-Control-Max-Age' => 3600,
    				],
    			],
    	];
    }
	
    public function actionIndex() {
        return $this->redirect(['site/login']);
    }

    public function actionLogin() {
    	if(!Yii::$app->user->isGuest) {
    		return $this->redirect(['leave/index']);
    	}
    		
		$model = new Employee(['scenario'=>'login']);
		//$this->TimesheetRevival();
		///$this->actionRefresh();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->getLogin()) {
	    	return $this->redirect(['leave/index']);
        }
        $this->layout = 'login';
        return $this->render('login',[
            'model' => $model,        ]);
    }
    
    public function actionApiLogin($id=0,$pass="") {
    	$model = new Employee(['scenario'=>'login']);
    	$data['Employee']['EmployeeID'] = $id;
    	$data['Employee']['passtext'] =  $pass;
    	if ($model->load($data) && $model->validate() &&  $model->getLogin()) {
    		return $this->redirect(['leave/index']);
    	} else {
    		return $this->redirect(['/']);
    	}
    	return \yii\helpers\Json::encode($result);
    }
    
    public function actionLogout(){
        Yii::$app->user->logout();
		//Yii::$app->session->set('helpdesk','');
        $this->redirect(['site/login'],301);
    }
    
    public function actionError(){
	echo 'ERROR 404';
    }
    
    public function actionTcpdf(){
        return $this->render('tcpdf');
    }
    
    
    public function actionRefresh(){
        $Employee = \app\models\Employee::getAllEmployee();
        foreach($Employee as $row){
            if($row->EmployeeHireDate!='0000-00-00'){
                /** Variable Counter **/
                $ledate = '';
                
           
                //update automatic leave
                $hire_date = $row->EmployeeHireDate;
                $now_date =  date('Y-m-d');
                
				$range1 = \app\components\Common::dateRange($hire_date,$now_date);
                
                //echo "Selisih Tanggal Masuk :".$range1."<br/>";
				
                //if > 1 (one) year in executed hak cuti
                if($range1 >= 365) {
                    $lyear = date('Y') - 1;
                    $ldate = $lyear.substr($hire_date,4,6);
                    
                    // + 1 tahun untuk Expired
                    $exlyear = substr($ldate,0,4)+1;
                    $exldate = $exlyear.substr($ldate,4,6);
                    
                    $range2 = \app\components\Common::dateRange($now_date,$exldate);
                    if($range2>0) {
                        $ldate = $ldate;
                    }
                    else {
                        $ldate = $exldate;
                    }
                
                    //echo "Tanggal Hak Cuti :".$ldate."<br/>";    
                    $leaves = \app\models\LeaveBalance::findOne(['employee_id' => $row->employee_id,'leave_balance_date' => $ldate,'leave_balance_stype' => 0]);
                    
                    if(!$leaves) {
                        $lbtotal = 12;
                        $ledate = $ldate;
                        $description = 'Hak Cuti Tahun ('.\app\components\Common::MysqlDateToString($ldate).') Dengan Jumlah '.$lbtotal;
                        //echo $description."<br/>";
                        
                        $exyear = substr($ldate,0,4) + 1;
                        $exdate = $exyear.substr($ldate,4,6);
                        //echo "Tanggal Expired Cuti : ".$exdate."<br/>";
                        /**save data update **/
                        $modelUpdate = new \app\models\LeaveBalance();
						$modelUpdate->employee_id = $row->employee_id;
						$modelUpdate->leave_balance_date = $ldate;
						$modelUpdate->leave_balance_description = $description;
						$modelUpdate->leave_balance_total = $lbtotal;
									$modelUpdate->leave_balance_stype = 0;
						$modelUpdate->leave_balance_created_date =date('Y-m-d H:i:s');	
						$modelUpdate->leave_balance_created_by = 0;
						$modelUpdate->insert();
                        /** save data update **/
                        
                        $last_leave = \app\models\LeaveBalance::sumLastLeaveBalance($row->employee_id,$ldate);
                        
                        if($last_leave->total > 0)
                            $xtotal = $last_leave->total;
                        else
                            $xtotal = 0;
                        
                        //apabila ada sisa cuti + sebelum hak cuti maka kurangi
                        if($xtotal>0) {
                            $xtotalmin = $xtotal * -1;
                            $descriptionx = 'Hak Cuti Hangus Sebelum Tanggal '.\app\components\Common::MysqlDateToString($ldate).' Dengan Jumlah '.$xtotal;
                           // echo $descriptionx."<br/>";
                            /**save data update **/
                            $modelUpdate = new \app\models\LeaveBalance();
                            $modelUpdate->employee_id = $row->employee_id;
                            $modelUpdate->leave_balance_date = $ldate;
                            $modelUpdate->leave_balance_description = $descriptionx;
                            $modelUpdate->leave_balance_total = $xtotalmin;
                            $modelUpdate->leave_balance_stype = 1;
                            $modelUpdate->leave_balance_created_date = date('Y-m-d H:i:s');	
                            $modelUpdate->leave_balance_created_by = 0;
                            $modelUpdate->insert();
                            /**save data update **/
                            
                        }
                        
                    }
                    
                }
            }
			
			//search employee
            $sumTotal = \app\models\LeaveBalance::sumLastBalanceByEmployee($row->employee_id);
                
            if(count($sumTotal)>0)
                $xtotals = $sumTotal->total;
            else 
                $xtotals = 0;
                
            $sumUse = \app\models\Leaves::sumLastLeaveByEmployee($row->employee_id);
                
            if(count($sumUse->total)>0)
                $xuse = $sumUse->total;
            else 
                $xuse = 0;
                
            $xo = $xtotals - $xuse;    
                         
            //update employee
            $update = \app\models\Employee::updateAll(['EmployeeLeaveTotal' => $xtotals,'EmployeeLeaveUse' => $xuse],['employee_id'=>$row->employee_id]);
		
            //update tanggal
            $lastDate = \app\models\LeaveBalance::lastDateSadloBalanceByEmployee($row->employee_id);
            if($lastDate)
                 $updateDate = \app\models\Employee::updateAll(['EmployeeLeaveDate' => $lastDate->date],['employee_id'=>$row->employee_id]);
			
        }
    }
	
	
	 /**
     * Timesheet Revival
     */
    public function TimesheetRevival()
    {
        $query = \app\models\Leaves::find()
        ->andWhere(['leave_status' => 6])
        ->orderBy(['leave_date'=>SORT_ASC])
        ->all();
        
        //echo count($query);
        
        if(count($query))
        {
        foreach($query as $row)
            {
                //echo $row->employee_id;
                $timesheet = \app\models\Timesheet::find()
                ->andWhere(['employee_id' => $row->employee_id])
                ->andWhere(['timesheetdate' => $row->leave_date_from])
				->andWhere('(hour)>=4')
                ->andWhere('job_id in (4,5,6,7,8,9,10,11,12,14,17)')
                ->one();
                     
                if($timesheet)
                {
                    if($timesheet->timesheet_approval==2)
                    {
                        $approved = 1;
                        $request  = 1;
						$status   = 1;
                    }
                    elseif($timesheet->timesheet_approval==1)
                    {
                        $approved = 2;
                        $request  = 1;
                        $status   = 1;
                    }
                    else
                    {
                        $approved = 3;
                        $request  = 6;
                        $status   = 6;
					}
                    
					// Update Leaves
                    $update = \app\models\Leaves::updateAll(['leave_status'=>$status,'leave_request'=>$request,'leave_approved'=>$approved],['leave_id'=>$row->leave_id]);
                }
				else
				{
					$delete = \app\models\Leaves::deleteAll('leave_status = :status AND leave_id = :id', [':status' => 6, ':id' => $row->leave_id]);
					$deleteLog = \app\models\LeaveLog::deleteAll('leave_id = :id', [':id' => $row->leave_id]);
				}
            }
        }
    }
    
}
