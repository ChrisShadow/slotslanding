<?php

return [
    'app' => [
        'debug' => false,
        'timezone' => 'America/Asuncion',
    ],
    'mail' => [
        'host' => 'smtp.gmail.com',
        'port' => 465,
        'username' => '',
        'password' => '',
        'encryption' => 'ssl',
        'from_email' => 'chrisdanielbene@gmail.com',
        'from_name' => 'SRS Landing',
        'to_email' => 'clubcomodinsoporte@gmail.com',
        'to_name' => 'Equipo Comercial',
    ],
    'telegram' => [
        'enabled' => true,
        'bot_token' => '',
        'chat_id' => '',
    ],
    'security' => [
        'rate_limit_seconds' => 45,
        'max_message_length' => 1200,
    ],
];
