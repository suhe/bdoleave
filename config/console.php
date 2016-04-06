<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
    	
    	'i18n' => [
    		'translations' => [
    			'app*' => [
    				'class' => 'yii\i18n\PhpMessageSource',
    				'sourceLanguage' => 'en_us',
    				'basePath' => '@app/language',
		    		'fileMap' => [
		    			'app' => 'app.php',
		    			'app/message' => 'message.php'
		    		]
		    	]
    		]
    	],
    	/*'user' => [
    		'class' => 'yii\web\User',
    		'identityClass' => 'app\models\Employee',
    		'enableAutoLogin' => false,
    	],*/
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
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
    				'port' => '587'
    			]
    					
    	],
    	/*'session' => [
    		'class' => 'yii\web\Session',
    	],*/
        'db' => $db,
    ],
    'params' => $params,
];
