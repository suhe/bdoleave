<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'timeZone' => 'Asia/Jakarta',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
	'i18n' => [                                          
            'translations' => [                      
                'app*'=>[
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en_us',
                    'basePath' => '@app/language',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/message' => 'message.php'
                    ],
                ],
            ],
        ],
        'language' => 'en_us',
        'defaultRoute' => 'site/login',
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'lDWr3LYiq6P5JXitDnpnUeMSI3Nsa3Pp',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Employee',
            'enableAutoLogin' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.bdoindonesia.com',
                'username' => 'support@bdoindonesia.com',
                'password' => 'admin1234',
                'port' => '587',
                
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
        'db' => require(__DIR__ . '/db.php'),
        // Yii2 TCPDF
        'tcpdf' => [
            'class' => '\app\cinghie\tcpdf\TCPDF',
        ],
        
    ],
    
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
