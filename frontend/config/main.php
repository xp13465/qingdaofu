<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php'),
    require(__DIR__ . '/params-error.php'),
    require(__DIR__ . '/params-upload.php')
);
return [
	'name'=>'清道夫债管家',
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'sourceLanguage'=>'en_US',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',

    'components' => [
        'smser'=>[
            'class'=>'frontend\extensions\sms\Smser',
        ],
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ], 
        'urlManager'=>[
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:[\w-]+>/<id:\d+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:[\w-]+>/<id:\d+>' => '<module>/<controller>/<action>',


            ]
        ],

        'i18n' => [
            'translations' => [
                'zcb' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en_US',
                    'basePath' => '@frontend/messages',
                    'fileMap' => [
                        'zcb' => 'frontend.php',
                    ],
                ],
            ],
        ],

        'log' => [  
            'traceLevel' => YII_DEBUG ? 3 : 0,  
            'targets' => [  
                [  
                    'class' => 'yii\log\FileTarget',  
                    'levels' => ['error', 'warning'],  
                ],  
                //在原配置的基础上，增加以下配置（新增一个target）  
                [  
					'class' => 'yii\log\FileTarget',  
					'levels' => ['error', 'warning', 'info'],  
					'logVars'=>[],  
					//表示以yii\db\或者app\models\开头的分类都会写入这个文件  
					'categories'=>['yii\db\*','app\models\*'],  
					//表示写入到文件sql.log中年月日记录日志  
					'logFile'=>'@runtime/logs/sql/sql.log'.date('Ymd'),  
               ],  
            ],  
              
        ], 


        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        //'//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js'
                        //'//cdn.bootcss.com/jquery/2.2.4/jquery.js',
                    ],

                ],
            ],
        ],





        /*'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'file'=>[
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info','error'],
                    'categories' => ['rhythmk'],
                    'logFile' => '@frontend/runtime/logs/mylogs/sms.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 60,
                ],
            ],
            ],*/



        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],

    'modules' => [
        'admin' => [
            'class' => 'frontend\modules\admin\admin',
        ],
        'wap' => [
            'class' => 'frontend\modules\wap\WapModule',
        ],
    ],
    'params' => $params,
    
];
