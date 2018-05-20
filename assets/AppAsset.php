<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',	
		'//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css',			
        'css/site.css',
        'css/bootstrap_alert.css',
		
    ];
    public $js = [
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];	
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
