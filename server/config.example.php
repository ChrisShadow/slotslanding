<?php

return [
    'app' => [
        'debug' => false,
        'timezone' => 'America/Asuncion',
    ],
    'mail' => [
        'host' => 'mail.tudominio.com',
        'port' => 465,
        'username' => 'pedidos@tudominio.com',
        'password' => 'CAMBIAR_CONTRASENA_SMTP',
        'encryption' => 'ssl',
        'from_email' => 'pedidos@tudominio.com',
        'from_name' => 'SRS Landing',
        'to_email' => 'ventas@tudominio.com',
        'to_name' => 'Equipo Comercial',
    ],
    'telegram' => [
        'enabled' => true,
        'bot_token' => 'CAMBIAR_TOKEN_DEL_BOT',
        'chat_id' => 'CAMBIAR_CHAT_ID',
    ],
    'security' => [
        'rate_limit_seconds' => 45,
        'max_message_length' => 1200,
    ],
];
