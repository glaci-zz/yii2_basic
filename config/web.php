<?php

//environment judge
$local_ip = getHostByName(getHostName());
$env = $_GET['debug'] ? 'test' : 'DEV'; //for debug
$project = $_GET['project'] ? $_GET['project'] : 'match_cpnp'; // for ios pem, 判斷不同project

//test
if($env == 'test' && $local_ip == ''){
    //db settings
    $db_host = '';
    $db_name = '';
    $db_acc = '';
    $db_pw = '';

    //redis
    $redis_host = '';

    //params

//DEV
}elseif($env == 'DEV' && $local_ip == ''){
    //db settings
    $db_host = '';
    $db_name = '';
    $db_acc = '';
    $db_pw = '';

    //redis
    $redis_host = '';

    //params

//PRD
}else{
    //db settings
    $db_host = '';
    $db_name = '';
    $db_acc = '';
    $db_pw = '';

    //redis
    $redis_host = '';

    //params

}

//settings
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timezone' => 'Asia/Taipei',
    //modules
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module'
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1', '*.*.*.*']
        ],
        //rules
        // api/(product/)category/id/function/
        // ex:  api/user/id
        //      api/match/device/control/
        //      api/match/app/group/
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
        'app' => [
            'class' => 'app\modules\app\Module',
        ],
    ],
    'components' => [
        // 'response' => [
        //     'format' => yii\web\Response::FORMAT_JSON,
        //     'charset' => 'UTF-8',
        // ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => $redis_host,
            'port' => 6379,
            'database' => 0,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                // ...
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                                        'user/default',
                                        'app/default',
                                    ],
                ],
                // [
                //     'class' => 'yii\rest\UrlRule',
                //     'controller' => 'user/users',
                //     'extraPatterns' => [
                //         'GET name' => 'name',
                //     ],
                // ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                                        'app/push'
                                    ],
                    'extraPatterns' => [
                        // post url => action
                        'POST push' => 'push',
                    ],
                ],

                '<module:\w+>/<action:\w+>' => '<module>/default/<action>'
                //'<module:\w+>/<controller:\w+>' => '<module>/<controller>/index',
                //'<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>'
                //'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '1qazluxultek@wsxmatchlux#edc',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'categories' => ['yii\*', 'app\*',],
                    'logVars' => [],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'gcm' => [
            'class' => 'bryglen\apnsgcm\Gcm',
            'apiKey' => 'AIzaSyDHHzFFQ1K2_AI6uvsHzJxa2mjEXhBqyuA', // offical use
        ],
        'apns' => [
            'class' => 'bryglen\apnsgcm\Apns',
            // 如果app已上架過，environment均使用production 發送push, 從未上架過才使用sandbox
            'environment' => $apns_env,
            'pemFile' => $pem_path,
            'options' => [
                'sendRetryTimes' => 5
            ],
        ],
        'util' => [
            'class' => 'app\components\UtilComponent',
        ]
    ],
    'params' => require(__DIR__ . '/params.php'),
];


return $config;
