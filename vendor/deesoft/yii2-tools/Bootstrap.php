<?php

namespace dee\tools;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Description of Bootstrap
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Bootstrap implements BootstrapInterface
{

    public function bootstrap($app)
    {
        Yii::$container->set('yii\caching\DbCache', 'dee\tools\DbCache');
        Yii::$container->set('yii\web\DbSession', 'dee\tools\DbSession');
    }
}
