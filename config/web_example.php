<?php

use kartik\mpdf\Pdf;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'name' => 'Задачник',
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
    'defaultRoute' => 'task',
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
        'drive' => [
            'class' => 'app\modules\drive\Module',
        ],
        'disk' => [
            'class' => 'app\modules\disk\Yandex',
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        // setup Krajee Pdf component
        'pdf' => [
            'class' => Pdf::class,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            // refer settings section for all configuration options
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '667521552878-91u8d3422fsslqf19tgnbfhulohjmg3jmngvosg.apps.googleusercontent.com',
                    'clientSecret' => 'Q3342mYeNv5dHMAgVfwd3ADUdte3edrUrpb',
                    'returnUrl' => 'http://localhost/drive',
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => 'facebook_client_id',
                    'clientSecret' => 'facebook_client_secret',
                ],
                // etc.
            ],
        ],
        'googleDrive' => [
            'class' => 'lhs\Yii2FlysystemGoogleDrive\GoogleDriveFilesystem',
            'clientId' => '66752155287dewevvsw8-91u8dlqf19tgnbfhulohjmg3jmngvosg.apps.googleusercontent.com',
            'clientSecret' => 'QmYeNwer44v5dHgte3MAgVADUwervcdterUrpb',
            'refreshToken' => '1/bVn_hdjxQ3CVTsXtN3434gyQyBcEgpv334534gAZPGxRAXikYT4GMZYH4kvl20bt_-QaK1xh3CCw',
            'rootFolderId' => '1dDERhuGyb34g34koHHsBswt4Eb3453gg34gXeLVHlZe9fT',
        ],
        'backup' => [
            'class' => 'demi\backup\Component',
            // The directory for storing backups files
            'backupsFolder' => dirname(__DIR__) . '/backups', // <project-root>/backups
            // Directories that will be added to backup
            'directories' => [
                'images' => '@webroot/images',
            ],
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'timeFormat' => 'H:i',
            'datetimeFormat' => 'dd.MM.yyyy H:i:s',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'gfhskldfghwuilerghg589ghgh89pg5goh48gh4hgigp',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
