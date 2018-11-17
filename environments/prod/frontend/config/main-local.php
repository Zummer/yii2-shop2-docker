<?php
return [
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vk' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '',
                    'clientSecret' => '',
                    'scope' => ['email']
                ],
                'yandex' => [
                    'class' => 'yii\authclient\clients\Yandex',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
            ],
        ]
    ],
];
