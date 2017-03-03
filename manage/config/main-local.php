<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'NYr6mNJNdurV-UH3e9AAIkLCydJRHzzf',
        ],

        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.20.149;dbname=manage',
            'username' => 'honghanzheng',
            'password' => '123456',
            'charset' => 'utf8',
            'tablePrefix'=>'m_'
        ],
		
		'qdfdb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.20.149;dbname=zcb_web',
            'username' => 'honghanzheng',
            'password' => '123456',
            'charset' => 'utf8',
            'tablePrefix'=>'m_'
        ],

    ],
];

if (YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
