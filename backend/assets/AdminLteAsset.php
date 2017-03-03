<?php
namespace backend\assets;

use yii\base\Exception;
use yii\web\AssetBundle;

class AdminLteAsset extends AssetBundle
{
    public $skin = '_all-skins';

    /**
     * @inheritdoc
     */
    public function init()
    {

        $this->sourcePath = __DIR__ . '/AdminLte';

        $this->css = [
            'css/AdminLTE.min.css',
        ];

        $this->js = [
            'js/app.min.js'
        ];

        $this->depends = [
            'rmrevin\yii\fontawesome\AssetBundle',
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
            'yii\bootstrap\BootstrapPluginAsset',
        ];

        // Append skin color file if specified
        if ($this->skin) {
            if (('_all-skins' !== $this->skin) && (strpos($this->skin, 'skin-') !== 0)) {
                throw new Exception('Invalid skin specified');
            }

            $this->css[] = sprintf('css/skins/%s.min.css', $this->skin);
        }


        parent::init();
    }
}
