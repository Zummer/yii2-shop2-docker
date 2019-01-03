<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\MemCache',
            'useMemcached' => true,
            'servers' => [
                [
                    'host' => 'memcached_server',
                    'port' => 11211,
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ]
    ],
];
