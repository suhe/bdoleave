<?php
namespace app\models;
use yii;
 
class Holiday extends \yii\db\ActiveRecord {
    
    public $holiday_year = 2015;
    
    public static function tableName(){
        return 'holiday';
    }
    
    public function rules(){
        return [
            [['holiday_year'],'safe','on'=>['search']],
            [['holiday_date'],'required','on'=>['save','update']],
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
    
    
    public static function getDropDownYear($ALL=TRUE){
        $data[0] = Yii::t('app','all');
        $data[2015] = 2015;
        $data[2016] = 2016;
        return $data;
    }
    
    
    public function getHolidayData($params){
        $query = Holiday::find()
        ->select(['holiday_id','DATE_FORMAT(holiday_date,\'%d/%m/%Y\') as holiday_date','holiday_desc'])
        ->orderBy('holiday_date');
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page']
            ]    
        ]);
        
        $query->andWhere(['YEAR(holiday_date)'=>$this->holiday_year]);
        
        return $dataProvider;
    }
    
    public function getSingleHolidayDataByDate($date){
        return Holiday::find()
        ->select(['holiday_id','DATE_FORMAT(holiday_date,\'%d/%m/%Y\') as holiday_date','holiday_desc'])
        ->where(['DATE_FORMAT(holiday_date,\'%d/%m/%Y\')' => $date])
        ->one();
    }
    
    public function getSaveData(){
        if($this->validate()){
            $model = new Holiday();
            $model->holiday_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->holiday_date);
            $model->holiday_desc = $this->holiday_desc;
            $model->insert();
            return true;
        }
    }
    
    public function getUpdateData($id){
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