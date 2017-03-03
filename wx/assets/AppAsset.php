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
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/login.css',
        '/css/global.css',
        '/css/global01.css',
        '/css/global01.css',
        '/css/global02.css',
        '/js/msgbox/jquery.msgbox.css',
        '/css/style.css',
        '/css/pull.css',
        '/css/more.css',
    ];
    public $js = [
        '/js/TouchSlide.1.1.js',
        '/js/jquery-1.11.1.js',
        '/js/function.js',
        '/js/msgbox/jquery.msgbox.js',
        '/js/jquery.wheelmenu.js',
        '/js/fastclick.js',
        '/js/iscroll.js',
        '/js/layer/layer.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
