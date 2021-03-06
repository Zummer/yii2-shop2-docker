<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params-local.php'
);

return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\auth\Identity',
            'identityCookie' => [
                'name' => '_identity',
                'httpOnly' => true,
                'domain' => $params['cookieDomain'],
            ],
        ],
    ],
];
