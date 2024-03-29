<?php

$db = require(__DIR__ . '/../../config/db.php');
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic-api',
    'name' => 'CountryPopulation',
    // Need to get one level up:
    'basePath' => dirname(__DIR__) . '/..',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'app\api\modules\v1\module',
        ],
    ],
    'components' => [
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null && Yii::$app->request->get('suppress_response_code')) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                    ];
                    $response->statusCode = 200;
                }
            },
        ],
        'request' => [
            //'cookieValidationKey' => 'Qq0fIK5vB6mseTKoYXX-dVdwHQFYrEXC',
            // Enable JSON Input:
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    // Create API log in the standard log dir
                    // But in file 'api.log':
                    'logFile' => '@app/runtime/logs/api.log',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/clinic',
                        'v1/region',
                    ],
                    //'GET,HEAD <id:\d+>/booth' => 'booth/all-booths',
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                    ],
                    'extraPatterns' => [
                        //'GET query' => 'query',
                        //'GET users' => 'citizens',
                        //'GET all-booths' => 'all-booths',
                        //'GET all/{id}' => 'all',
                        //'GET summary/{id}' => 'summary',
                        //post actions
                        //'POST document' => 'document',
                        //'POST logo' => 'logo',
                    ],
                ],
            ],
        ],
        'db' => $db,
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
        ],
    ],
    'params' => $params,
];

return $config;