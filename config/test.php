<?php
$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/test_db.php');

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),    
    'language' => 'en-US',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        
        'db' => $db,
        
        'season' => [
            'class' => '\app\components\Season',
        ],
        
        'financial' => [
            'class' => '\app\components\Financial',
        ],
        'predraft' => [
            'class' => '\app\components\Predraft',
        ],
        'save2session' => [
            'class' => '\app\components\Save2Session',
        ],
        
        'mailer' => [
            'useFileTransport' => true,
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'user' => [
            'identityClass' => 'app\models\User',
        ],
        'season' => [
            'class' => '\app\components\Season',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'flushInterval' => 1,

            'targets' => [
                'file' =>
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','trace'],
                    //'categories'=>['application'],   // this limited writing to those with this 'application' category
                    //'categories' => ['yii\db\Command::query'],
                    'exportInterval' => 1,
                ],
            ],
        ],
    ],
    'params' => $params,


];
