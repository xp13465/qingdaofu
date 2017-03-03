<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class ZcbAsset extends AssetBundle
{

    public function init()
    {
        $this->sourcePath = __DIR__ . '/media';


        $this->css = [
            'css/base.css',
            'css/style.css',
            'css/base1.css',
            'css/cd.css',
            'css/list_news.css',
            'css/ls_intro.css'
        ];

        $this->js = [
            'js/jquery.validate.js',
            'js/index.js',
            'js/zDialog.js'
        ];

        $this->depends = [
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
            'yii\bootstrap\BootstrapPluginAsset',
        ];



        parent::init();
    }
}
