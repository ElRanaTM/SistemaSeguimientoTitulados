<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
require_once(__DIR__.'/functions.php');
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'name' => 'Seguimiento a Titulados',
    'language' => 'es-ES',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'on beforeRequest' => function ($event) {
        Yii::$app->layout = Yii::$app->user->isGuest ? 
            '@app/views/layouts/GuestUser.php' : (
            Yii::$app->user->identity->user_type === 'director' ?
            '@app/views/layouts/director.php' : (
                Yii::$app->user->identity->user_type === 'admin' ?
            '@app/views/layouts/admin.php' : (
                Yii::$app->user->identity->user_type === 'SuperAdmin' ?
            '@app/views/layouts/main.php' :
            '@app/views/layouts/titulado.php')));
    },
    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'es_ES',
        ],
        'as passwordChangedFilter' => [
            'class' => 'app\filters\PasswordChangedFilter',
        ],        
        'dbAcademica' => [
            'class' => 'yii\db\Connection',
            'driverName' => 'sqlsrv',
            'dsn' => 'sqlsrv:Server=tcp:172.16.1.250,1433;Database=Academica;Encrypt=0;TrustServerCertificate=1',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
            'on afterOpen' => function($event){$event->sender->createCommand("SET DATEFORMAT DMY; SET LANGUAGE spanish")->execute();},
        ],
        'as beforeAction' => [
            'class' => 'yii\filters\AccessControl',
            'only' => ['login', 'logout', 'signup', 'titulado'],
            'rules' => [
                [
                    'allow' => true,
                    'controllers' => ['titulado'],
                    'actions' => ['index'],
                    'roles' => ['?'],
                ],
                [
                    'allow' => false,
                    //'controllers' => ['*'],
                    'matchCallback' => function ($rule, $action) {
                        return Yii::$app->user->isGuest;
                    },
                    'denyCallback' => function ($rule, $action) {
                        return Yii::$app->getResponse()->redirect(['/site/index']);
                    },
                ],
            ],
        ], 
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['admin', 'titulado', 'director', 'SuperAdmin'],
        ],
        'countries' => [
            'class' => 'akavov\yii2-countries\components\CountriesBehavior',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ZY98hV5YgV7gGdeUTumFpYH1wh4YOVgg',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'authTimeout' => 1800,
            'enableSession' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'dtic.mail1@usfx.bo',
                'password' => '9511*dtic1',
                'port' => '587',
                'encryption' => 'tls',
            ],
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
        /* Activar para debugear */
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'informe/generar-informe-pdf' => 'informe/generar-informe-pdf',
            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '10.1.11.31', '0.0.0.0'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '10.1.11.31'],
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
