<?php
namespace app\models;
use yii;
 
class Holiday extends \yii\db\ActiveRecord {
    public $holiday_date_from = '01/01/2016';
    public $holiday_date_to = '31/12/2016';
	
    public static function tableName(){
        return 'holiday';
    }
    
    public function rules(){
        return [
	            [['holiday_date_from'],'safe','on'=>['search']],
	        	[['holiday_date_to'],'safe','on'=>['search']],
	            [['holiday_date'],'required','on'=>['save','update']],
	        	[['holiday_type'],'required','on'=>['save','update']],
	            [['holiday_desc'],'required','on'=>['save','update']],
	            [['holiday_date'],'validateHolidayDate','on'=>['save']],
        ];
    }
    
    public function attributeLabels(){
        return [
            'holiday_date' => Yii::t('app','date'),
            'holiday_desc' => Yii::t('app','description'),
        ];
    }
    
    /**
     * Dropdown List Get
     * @return string
     */
    
    /**
     * Get Type List
     * @param string $ALL
     */
    public static function getListType($data = array()){
    	$lists = [];
    	if(isset($data['label']))
    		$lists[""] = $data['label'];
    	
    	$lists["Libur"] = "Libur";
    	$lists["Cuti Bersama"] = "Cuti Bersama";
    	return $lists;
    }
    
    
    /**
     * End Dropdown List Get
     * @return string
     */
    
    
    public static function getDropDownYear($ALL=TRUE){
        $data[0] = Yii::t('app','all');
        $data[2015] = 2015;
        $data[2016] = 2016;
        return $data;
    }
    
    
    public function getAllDataProvider($params){
        $query = Holiday::find()
        ->select(['holiday_id','DATE_FORMAT(holiday_date,\'%d/%m/%Y\') as holiday_date','holiday_type','holiday_desc'])
        ->orderBy('holiday_date');
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        //if ((!$this->load($params)) && ($this->validate())) {
        	//return $dataProvider;
        //}
        
        if($this->holiday_date_from && $this->holiday_date_to) 
        	$query->andWhere("holiday_date between STR_TO_DATE('".$this->holiday_date_from."', '%d/%m/%Y') and STR_TO_DATE('".$this->holiday_date_to."', '%d/%m/%Y') ");
    	
        return $dataProvider;
    }
    
    public function getSingleHolidayDataByDate($date){
        return Holiday::find()
        ->select(['holiday_id','DATE_FORMAT(holiday_date,\'%d/%m/%Y\') as holiday_date','holiday_type','holiday_desc'])
        ->where(['DATE_FORMAT(holiday_date,\'%d/%m/%Y\')' => $date])
        ->one();
    }
    
    
    /**
     * Get Save / Update Request
     * @param number $id
     */
    public function getSaveRequest($id = 0 ){
        if($this->validate()){
            $model = new Holiday();
            if($id) $model =  $model->findOne($id);
            $model->holiday_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->holiday_date);
            $model->holiday_type = $this->holiday_type;
            $model->holiday_desc = $this->holiday_desc;
            if($id) 
            	$model->update();
            else
            	$model->insert(); 
            return true;
        }
        return false;
    }
    
    public function getUpdateRequest($id){
        if($this->validate()){
            $model = new Holiday();
            $model = $model->findOne($id);
            $model->holiday_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->holiday_date);
            $model->holiday_desc = $this->holiday_desc;
            $model->update();
            return true;
        }
        
    }
    
    public function validateHolidayDate($attribute,$params){
        $holiday_date = $this->getSingleHolidayDataByDate($this->holiday_date);
        if($holiday_date) {
            return $this->addError($attribute, Yii::t('app/message','msg date is already'));
        }
    }
}