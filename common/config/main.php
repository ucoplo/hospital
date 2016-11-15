<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'es',
    'timeZone' => 'America/Argentina/Buenos_Aires',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
       'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true, //(ignorado por enablesession)
            'loginUrl' => '../../login/web/index.php?r=site/login',
            'authTimeout' => 60*60*2 // en segundos
            //'loginUrl' => '/login',
            //'enableSession' => true,
        ],

    ],
    'modules' => [
	   'gridview' =>  [
	        'class' => '\kartik\grid\Module',
	        'downloadAction' => 'gridview/export/download',
	        
	    ]
	],
    
];
