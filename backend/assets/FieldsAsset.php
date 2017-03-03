<?php
namespace backend\assets;

class FieldsAsset extends \yii\web\AssetBundle
{

    public function init()
    {

        $this->sourcePath = __DIR__ . '/fields';

        $this->css = [
            'css/fields.css',
        ];

        $this->js = [
            'js/fields.js'
        ];

        $this->depends = [
            'yii\web\JqueryAsset',
        ];

        parent::init();

    }

}
