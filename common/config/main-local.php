<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'OlTeJMBRlHP4y5BDQyX3XcHv9HnBK9bi',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=hospital',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'dbRafam' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'oci:dbname=//XRAFAM:1521/HMABB;charset=UTF8',
            'username' => 'OWNER_RAFAM',
            'password' => 'OWNERDBA',
            'charset' => 'utf8',
        ],
        'dbUser' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=administrativa',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
         'formatter' => [
            'dateFormat' => 'php:d-m-Y',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => '$',
       ],
    ],
];
