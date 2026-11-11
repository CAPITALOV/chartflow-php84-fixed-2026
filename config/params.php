<?php

return [
    'adminEmail' => 'smtp@chartflow.ru',
    'mailer'     => [
        'from' => ['smtp@chartflow.ru' => 'ChartFlow'],
    ],
    'chat' => [
        'consultant_id' => (YII_ENV_DEV)? 7 : 6,
    ],
    'yeKurs' => 65,
    'tel' => '+79259021110',
];
