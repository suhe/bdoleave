<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Suhendar <hendarsyahss@gmail.com>
 * @patch untuk IE8
 */
class AppAssetIE8 extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'assets/js/plugins/easypiechart/easypiechart.ie-fix.js'
    ];
    public $jsOptions = ['condition' => 'lte IE8'];
}
