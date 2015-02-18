<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use app\models\Employee;
use app\models\Bpjs;
use app\models\BpjsEmployee;
use app\models\EmployeeExcel;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

class BpjsController extends Controller {
    public $class="";
    public $function="";
    public $title="";
    
    public function actions(){
        if(!Yii::$app->session->get('user.id')) $this->redirect(['site/login'],301);
        $this->class = Yii::t('app/backend','bpjs');
    }
    
    
    
    public function actionEmployee(){
        $model = new BpjsEmployee();
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search']))
            $dataProvider = $model->getBpjsEmployeeData(Yii::$app->request->queryParams);
        $dataProvider = $model->getBpjsEmployeeData(Yii::$app->request->queryParams);
        return $this->render('employee',[
            'model' => $model,
            'dataProvider' => $dataProvider
         ]);
    }
    
    public function actionEmployee_excel(){
        $excel = new EmployeeExcel();
        $excel = $excel->getEmployeeAll();
        if(($_SERVER['SERVER_NAME']=='localhost') || ($_SERVER['SERVER_NAME']=='127.0.0.1'))
            $link = 'http://'.$_SERVER['SERVER_NAME'].'/bdobpjs/web/'.$excel;
        else
            $link = 'http://'.$_SERVER['SERVER_NAME'].'/web/'.$excel;
        $this->redirect($link);
    }
    
    public function actionSetup(){
        $model = new Bpjs();
        $query = Bpjs::findOne(1);
        if($query){
            $model->bpjs_registration_date = $query->bpjs_registration_date;
            $model->bpjs_expired_date = $query->bpjs_expired_date;
        }
        if(($model->load(Yii::$app->request->post())) && ($model->getUpdate())){
            $query = Bpjs::findOne(1);
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg save successfuly'));
        }
        return $this->render('setup_form',[
            'model' => $model,
            'query' => $query,
            'bpjsType' => $model->getBpjsType(),
            'bpjsBank' => $model->getBpjsBank(),
            'bpjsDependent' => $model->getBpjsDependent(),
         ]);
    }
    
    public function actionForm($key=0){
        switch($key){
            case 2 :
                $title = Yii::t('app','relation biodata');
                $page  = 'biodata_form_relation';
                $scenario = 'relation';
                $post = ['relation'=>2,'pisat'=>'Suami'];
                break;
            case 3 :
                $title = Yii::t('app','child01 biodata');
                $page  = 'biodata_form_child';
                $scenario = 'child';
                $post = ['relation'=>3,'pisat'=>'Anak Ke 1'];
                break;
            case 4 :
                $title = Yii::t('app','child02 biodata');
                $page  = 'biodata_form_child';
                $scenario = 'child';
                $post = ['relation'=>4,'pisat'=>'Anak Ke 2'];
                break;
            case 5 :
                $title = Yii::t('app','child03 biodata');
                $page  = 'biodata_form_child';
                $scenario = 'child';
                $post = ['relation'=>5,'pisat'=>'Anak Ke 3'];
                break;
            default:
                $title = Yii::t('app','personal biodata');
                $page = 'biodata_form_personal';
                $scenario = 'personal';
                $post = ['relation'=>1,'pisat'=>'Peserta'];
                break;
        }
        
        $model = new BpjsEmployee(['scenario'=>$scenario]);
        if(($model->load(Yii::$app->request->post()))){
            //upload photo
            FileHelper::createDirectory('uploads/'.Yii::$app->session->get('user.id'),777,true);
            $model->employee_photo = UploadedFile::getInstance($model, 'employee_photo');
            $model->employee_photo->saveAs('uploads/'.Yii::$app->session->get('user.id').'/personal'.'.'.$model->employee_photo->extension);
            //resize image
            $file = 'uploads/'.Yii::$app->session->get('user.id').'/'.'/personal'.'.'.$model->employee_photo->extension;
            $image=Yii::$app->image->load($file);
            header("Content-Type: image/jpg");
            $image->resize(40,30)->rotate(30)->render(); 
            $model->getUpdate($post);
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg save successfuly'));
        }
        
        $query = BpjsEmployee::find()
        ->where(['employee_id' => Yii::$app->session->get('user.id'),'employee_relation' => $post['relation']])
        ->select(['*',"DATE_FORMAT(employee_birth_date,'%d/%m/%Y') as employee_birth_date",
                  "DATE_FORMAT(employee_active_date,'%d/%m/%Y') as employee_active_date"])
        ->one();
        if($query){
            $model->employee_birth_date = $query->employee_birth_date;
            $model->employee_active_date = $query->employee_active_date;
        }
        
        $querymaster = BpjsEmployee::find()
        ->where(['employee_id' => Yii::$app->session->get('user.id'),'employee_relation' => 1])
        ->one();
        
        return $this->render('biodata_form',[
            'page'  => $page,
            'model' => $model,
            'query' => $query,
            'master'=> $querymaster,
            'title' => $title,
            'post'  => $post,
            'pisatDropdown' => $model->pisatDropdown(),
         ]);
    }
}
