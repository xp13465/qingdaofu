<?php

namespace manage\assets;

use yii\web\AssetBundle;

class ZcbAsset extends AssetBundle
{

    public function init()
    {
        $this->sourcePath = __DIR__ . '/admin';

        $this->js = [
            'js/admin.js',
        ];

        $this->css = [
            'css/admin.css',
            'css/widget.css',
            'css/styler.css',
        ];

        parent::init();
    }
}
