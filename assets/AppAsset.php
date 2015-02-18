<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/css/bootstrap.min.css',
        'assets/css/fonts.css',
        'assets/font-awesome/css/font-awesome.min.css',
        'assets/css/plugins/daterangepicker/daterangepicker-bs3.css',
        'assets/css/plugins/morris/morris.css',
        'assets/css/plugins/bootstrap-datepicker/datepicker.css',
        'assets/css/plugins/gritter/jquery.gritter.css',
        'assets/css/themes/style.css',
        'assets/css/only-for-demos.css',
    ];
      
    public $js = [
        'assets/js/jquery.min.js',
        'assets/js/bootstrap.min.js',
        //'assets/js/plugins/slimscroll/jquery.slimscroll.min.js',
        //'assets/js/plugins/pace/pace.min.js',
        //'assets/js/plugins/jqueryui/jquery-ui-1.10.4.custom.min.js',
        //'assets/js/plugins/jqueryui/jquery.ui.touch-punch.min.js',
        //'assets/js/plugins/daterangepicker/moment.js',
        //'assets/js/plugins/daterangepicker/daterangepicker.js',
        //'assets/js/plugins/morris/raphael-min.js',
        //'assets/js/plugins/morris/morris.min.js',
        //'assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.js',
        //'assets/js/plugins/jquery-sparkline/jquery.sparkline.min.js',
        //'assets/js/plugins/easypiechart/jquery.easypiechart.min.js',
        //'assets/js/plugins/easypiechart/excanvas.compiled.js',
        //'assets/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        //'assets/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        //'assets/js/main.js',
        //'assets/js/speech-commands.js',
        //'assets/js/plugins/gritter/jquery.gritter.min.js',
        //'assets/js/plugins/slimscroll/jquery.slimscroll.init.js',
        //'assets/js/home-page.init.js',
        //'assets/js/plugins/jquery-sparkline/jquery.sparkline.init.js',
        //'assets/js/plugins/easypiechart/jquery.easypiechart.init.js',
    ];
    
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
    /*
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];*/
}
