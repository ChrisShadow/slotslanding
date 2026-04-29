<?php

return [
    'app' => [
        'debug' => false,
        'timezone' => 'America/Asuncion',
    ],
    'mail' => [
        'host' => 'smtp.gmail.com',
        'port' => 465,
        'username' => 'chrisdanielbene@gmail.com',
        'password' => 'btpdywrysizjbgow',
        'encryption' => 'ssl',
        'from_email' => 'chrisdanielbene@gmail.com',
        'from_name' => 'SRS Landing',
        'to_email' => 'clubcomodinsoporte@gmail.com',
        'to_name' => 'Equipo Comercial',
    ],
    'telegram' => [
        'enabled' => true,
        'bot_token' => '8269637361:AAF3T-t63YRZL3uwsrjuu4HVJ3W1NQWdqhM',
        'chat_id' => '1782814229',
    ],
    'security' => [
        'rate_limit_seconds' => 45,
        'max_message_length' => 1200,
    ],
];
