<?php
/*
* NOT tracked because database connected included here
*/
$params = require __DIR__ . '/params.php';
//$db = require __DIR__ . '/db.php';
//$dbDemo = require __DIR__ . '/dbDemo.php';

$config = [
    'name' => 'Milan Girls Softball',
    'timezone' => 'America/Chicago',
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],

    'components' => [

        'assetManager' => [
            'forceCopy' => true,
            'dirMode' => 0755],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'c5rkJAXHw_f-LF6t8BZDszsJiBhXUtRV',
            'enableCsrfValidation' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,

        ],'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,

            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'burnett.moody@gmail.com',
                'password' => 'cvyplrpxkipoxwpe',
                'port' => 587,
                'encryption' => 'tls',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'flushInterval' => 1,

            'targets' => [
                'file' =>
                [
                    'class' => 'yii\log\FileTarget',
                    //'levels' => ['error'],
                    //'categories'=>['application'],   // this limited writing to those with this 'application' category
                    //'categories' => ['yii\db\Command::query'],
                    'logVars' => ['_GET','_POST','_SESSION'],
                    'exportInterval' => 1,
                ],

            ],
        ],
        'view' => [
           'theme' => [
              'basePath' => '@app/themes/phones',
              'baseUrl' => '@web/themes/phones',
              'pathMap' => [
                 '@app/views' => '@app/themes/phones',
              ],
           ],
        ],
        'db' => [

            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=basic',
            'username' => 'grayhouse',
            'password' => 'ez2win',
            'charset' => 'utf8',
        ],

    ],
    'params' => $params,

];

if (YII_ENV_DEV) {
    //configuration adjustments for 'dev'
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['173.19.240.141', '127.0.0.1', '104.131.112.37'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
