<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/../../frontend/config/params-upload.php'),
   
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-upload.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-wx',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'wx\controllers',

    'components' => [
        'smser'=>[
            'class'=>'wx\extensions\sms\Smser',
        ],

        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'file'=>[
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info','error'],
                    'categories' => ['rhythmk'],
                    'logFile' => '@wx/runtime/logs/mylogs/sms.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 60,
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
