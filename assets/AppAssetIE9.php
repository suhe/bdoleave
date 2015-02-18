<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Suhendar <hendarsyahss@gmail.com>
 * @patch untuk IE9
 */
class AppAssetIE9 extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/css/ie-fix.css',
    ];
    public $cssOptions = ['condition' => 'lte IE9'];
      
    public $js = [
        'assets/js/html5shiv.js',
        'assets/js/respond.min.js',
    ];
    
    public $jsOptions = ['condition' => 'lte IE9'];
}
