<?php

return [
    'class'            => 'yii\swiftmailer\Mailer',
    'useFileTransport' => false,
    'transport' => [
        'class'    => 'Swift_SmtpTransport',

//      'username' => 'smtp@chartflow.ru',
//      'password' => 'fT5-7sP-HRN-62f',
        'host'     => 'mail.r01.ru',
        'port'     => '25',
        'username' => 'smtp@chartflow.ru',
        'password' => 'fT5-7sP-HRN-62f',
    ]
];