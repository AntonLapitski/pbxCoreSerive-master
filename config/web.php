<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'app\modules\api\Api',
            'defaultRoute' => 'api',
        ],
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                '*/xml' => 'yii\web\XmlParser',
            ],
            'baseUrl' => '',
            'enableCsrfValidation' => false,
        ],
        'twilio' => [
            'class' => 'app\components\twilio\TwilioClient'
        ],
        'commutator' => [
            'class' => 'crmpbx\commutator\Commutator',
            'amoServiceAddress' => AMO_SERVICE_ADDRESS,
            'amoTestServiceAddress' => AMO_TEST_SERVICE_ADDRESS,
            'amoServiceTimeout' => 0,
            'extensionServiceAddress' => EXTENSION_SERVICE_ADDRESS,
            'extensionServiceTimeout' => EXTENSION_SERVICE_TIMEOUT,
            'logServiceAddress' => LOG_SERVICE_ADDRESS,
            'logServiceTimeout' => LOG_SERVICE_TIMEOUT,
            'logServiceAccessToken' => LOG_SERVICE_ACCESS_TOKEN,
            'notificationServiceAddress' => NOTIFICATION_SERVICE_ADDRESS,
            'notificationServiceTimeout' => NOTIFICATION_SERVICE_TIMEOUT,
            'pipedriveServiceAddress' => PIPEDRIVE_SERVICE_ADDRESS,
            'pipedriveServiceTimeout' => PIPEDRIVE_SERVICE_TIMEOUT,
            'pipedriveTestServiceAddress' => PIPEDRIVE_TEST_SERVICE_ADDRESS
        ],
        'logger' => [
            'class' => 'crmpbx\logger\Logger',
            'service' => $params['serviceName'],
            'callback' => function(){
                return Yii::$app->commutator;
            }
        ],
        'response' => [
            'format' => \yii\web\Response::FORMAT_JSON,
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if (404 !== $response->statusCode) {
                    if (!$response->isSuccessful){
                        Yii::$app->logger->addInFile('exception', Yii::$app->errorHandler->exception);
                    }
                    Yii::$app->logger->addInFile('incoming_request', Yii::$app->request->bodyParams);
                    Yii::$app->logger->send();
                    Yii::$app->logger->writeInFileSystem();
                }
            },
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false
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
        'errorHandler' => [
            'maxSourceLines' => 5,
            'errorAction' => 'exception/error',
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
