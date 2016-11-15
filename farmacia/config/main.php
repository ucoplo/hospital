<?php
use kartik\datecontrol\Modulew;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php'),
    [
    // format settings for displaying each date attribute (ICU format example)
   /* 'dateControlDisplay' => [
        Module::FORMAT_DATE => 'php:d/m/Y',
        Module::FORMAT_TIME => 'php:H:i:s',
        Module::FORMAT_DATETIME => 'php:d/m/Y H:i:s', 
    ],
    */
    // format settings for saving each date attribute (PHP format example)
    /*'dateControlSave' => [
        Module::FORMAT_DATE => 'php:Y-m-d', // saves as unix timestamp
        Module::FORMAT_TIME => 'php:H:i:s',
        Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
    ],*/
    'autoWidget' => true,
    ]
   
);

return [
    'id' => 'app-farmacia',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'es',
    'controllerNamespace' => 'farmacia\controllers',
    'defaultRoute'=>'inicio/principal',
    'timeZone' => 'America/Argentina/Buenos_Aires',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => '../../login/web/'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'flushInterval' => 1, // <-- here
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/error.log',
                    'exportInterval' => 1,
                    'logVars' => [null],
                    'prefix' => function ($message) {
                            $user = Yii::$app->has('user', true) ? Yii::$app->get('user') : null;
                            $userID = $user ? $user->getId(false) : '-';
                            return "[$userID]";
                        }
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'logFile' => '@runtime/logs/info.log',
                    'exportInterval' => 1,
                ],
            ],
            
            // 'targets' => [
            //     [
            //         'class' => 'yii\log\FileTarget',
            //         'levels' => ['error', 'warning'],
                    
            //     ],
            // ],
        ],
        'errorHandler' => [
            'errorAction' => 'inicio/error',
        ],
        /*'urlManager' => [
           'class' => 'yii\web\urlManager', //clase UrlManager
           'showScriptName' => false, //eliminar index.php
           'enablePrettyUrl' => true //urls amigables
        ],*/
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        'jasper' => [
            'class' => 'chrmorandi\jasper\Jasper',
            'redirect_output' => false, //optional
            'resource_directory' => false, //optional
            
            
        ],
    ],
    'modules' => [
        
       'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module',// format settings for displaying each date attribute
        

            // automatically use kartikwidgets for each of the above formats
            'autoWidget' => true,

        ],
       
    ],
    'params' => $params,

];
