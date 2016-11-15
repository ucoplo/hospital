<?php

/**
 * Application configuration for farmacia unit tests
 */
return yii\helpers\ArrayHelper::merge(
    require(YII_APP_BASE_PATH . '/common/config/main.php'),
    require(YII_APP_BASE_PATH . '/common/config/main-local.php'),
    require(YII_APP_BASE_PATH . '/farmacia/config/main.php'),
    require(YII_APP_BASE_PATH . '/farmacia/config/main-local.php'),
    require(dirname(__DIR__) . '/config.php'),
    require(dirname(__DIR__) . '/unit.php'),
    require(__DIR__ . '/config.php'),
    [
    ]
);
