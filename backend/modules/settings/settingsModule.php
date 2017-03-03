<?php

namespace backend\modules\settings;

/**
 * settings module definition class
 */
class settingsModule extends \backend\components\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\settings\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
