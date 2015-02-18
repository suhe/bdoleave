<?php
namespace app\components;
use yii;

class Common {
    
    public function __construct(){
        parent::__construct();
    }
    
    public static function timeAgo($ptime){
        $estimate_time = time() - $ptime;
        if( $estimate_time < 1 ){
            return Yii::t('app/message','msg less than 1 second ago');
        }

        $condition = [
            12 * 30 * 24 * 60 * 60  =>  Yii::t('app','year'),
            30 * 24 * 60 * 60       =>  Yii::t('app','month'),
            24 * 60 * 60            =>  Yii::t('app','day'),
            60 * 60                 =>  Yii::t('app','hour'),
            60                      =>  Yii::t('app','minute'),
            1                       =>  Yii::t('app','second')
        ];

        foreach( $condition as $secs => $str){
            $d = $estimate_time / $secs;
            if( $d >= 1){
                $r = round( $d );
                return $r . ' ' . $str . ( $r > 1 ? '' : '' ) .' '. Yii::t('app','ago');
            }
        }
    }
    
    public static function dateToString($date){
        //24/05/2014
        $day = substr($date,0,2);
        $month = substr($date,3,2);
        $year = substr($date,6,4);
        
        switch($month){
            case '01' : $string = 'Januari';break;
            case '02' : $string = 'Februari';break;
            case '03' : $string = 'Maret';break;
            case '04' : $string = 'April';break;
            case '05' : $string = 'Mei';break;
            case '06' : $string = 'Juni';break;
            case '07' : $string = 'Juli';break;
            case '08' : $string = 'Agustus';break;
            case '09' : $string = 'September';break;
            case '10' : $string = 'Oktober';break;
            case '11' : $string = 'November';break;
            case '12' : $string = 'Desember';break;
            default : $string = '----';break;
        }
        
        return $day.' '.$string.' '.$year;
    }
    
    public static function force_download($filename = '', $data = ''){
	if ($filename == '' || $data == '')
	    return FALSE;
	
	// Try to determine if the filename includes a file extension.
	// We need it in order to set the MIME type
	if (FALSE === strpos($filename, '.'))
			return FALSE;
	
        // Grab the file extension
	$x = explode('.', $filename);
	$extension = end($x);

	// Load the mime types
	if (defined('ENVIRONMENT') AND is_file('@app/config/'.ENVIRONMENT.'/mimes.php')){
	    include('@app/config/'.ENVIRONMENT.'/mimes.php');
	}
	elseif (is_file('@app/config/mimes.php')){
	    include('@app/config/mimes.php');
	}

	// Set a default mime if we can't find it
	if ( ! isset($mimes[$extension])) {
            $mime = 'application/octet-stream';
	}
	else{
	    $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
	}

	// Generate the server headers
	if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE){
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header("Content-Transfer-Encoding: binary");
			header('Pragma: public');
			header("Content-Length: ".strlen($data));
	}
	else {
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Pragma: no-cache');
			header("Content-Length: ".strlen($data));
	}
	exit($data);
    }
    
}