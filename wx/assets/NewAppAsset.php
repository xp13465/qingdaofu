<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace wx\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class NewAppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/bate2.0/css/login.css',
        '/bate2.0/css/global.css',
        '/bate2.0/css/global01.css',
        '/bate2.0/css/global02.css',
        '/bate2.0/css/more.css',
		'/css/pull.css',
    ];
    public $js = [
		//'/js/jquery.SuperSlide.2.1.1.js',
        '/bate2.0/js/jquery-1.11.1.js',
        '/js/layer/layer.js',
        // '/js/layer_mobile/layer.js',
		'/js/TouchSlide.1.1.js',
        '/js/iscroll.js',
		
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
