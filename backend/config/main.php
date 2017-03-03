<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/../../common/config/params-error.php'),
    require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-error.php'),
    require(__DIR__ . '/params-local.php'),
    require(__DIR__ . '/params-upload.php')
);

return [
    'id' => 'app-backend',
    'name'=>'清道夫债管家总后台',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language'=>'zh-CN',

    "modules" => [

        "rbac" => [
            "class" => "mdm\admin\Module",
            'controllerMap' => [
                'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    'userClassName' => 'backend\models\Admin',
                    'idField' => 'id'
                ]
            ],
        ],

        'settings' => [
            'class' => 'backend\modules\settings\settingsModule',
        ],

        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ]

    ],

    "aliases" => [
        "@mdm/admin" => "@vendor/mdmsoft/yii2-admin",
    ],

    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            '/*',
        ]
    ],

    'components' => [

        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
        ],

        'smser'=>[
            'class'=>'backend\extensions\sms\Smser',
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            //'defaultRoles'=> ['普通管理员']
        ],


        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
	/*
        'profile' => [
            'class' => 'dee\tools\State'
        ],

        'session' => [
            'class' => 'yii\web\DbSession'
        ],


        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
	*/
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],



        'urlManager'=>[
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<action:[\w-]+>/<id:\d+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:[\w-]+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<action:(login|logout)>' => 'site/<action>',
            ]
        ],
		
        'i18n' => [
            'translations' => [
                'zcb' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en_US',
                    'basePath' => '@backend/messages',
                    'fileMap' => [
                        'zcb' => 'admin.php',
                    ],
                ],
            ],
        ],

		/*
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        // '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js'
                        // '//cdn.bootcss.com/jquery/2.2.4/jquery.js',
                        // '/js/jquery-1.11.1.js',
                        // '/js/jquery2.2.4.js',
                    ],

                ],
            ],
        ],
		*/


    ],
    'params' => $params,
];
