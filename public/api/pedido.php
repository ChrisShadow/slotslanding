<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Metodo no permitido.', 405);
}

$config = load_config();
$debug = (bool)($config['app']['debug'] ?? false);

date_default_timezone_set((string)($config['app']['timezone'] ?? 'America/Asuncion'));

try {
    $payload = collect_payload($config);
    enforce_rate_limit((int)($config['security']['rate_limit_seconds'] ?? 45));

    if ($payload['website'] !== '') {
        respond(true, 'Pedido enviado correctamente.');
    }

    $autoload = dirname(__DIR__, 2) . '/vendor/autoload.php';
    if (!is_file($autoload)) {
        throw new RuntimeException('PHPMailer no esta instalado. Falta vendor/autoload.php.');
    }

    require_once $autoload;

    send_email($payload, $config);
    send_telegram($payload, $config);

    respond(true, 'Pedido enviado correctamente.');
} catch (Throwable $exception) {
    error_log('[SRS pedido] ' . $exception->getMessage());

    $message = $debug
        ? 'Error: ' . $exception->getMessage()
        : 'No se pudo enviar el pedido. Intente nuevamente.';

    respond(false, $message, 500);
}

function load_config(): array
{
    $paths = array_filter([
        getenv('SRS_CONFIG') ?: null,
        dirname(__DIR__, 2) . '/server/config.php',
    ]);

    foreach ($paths as $path) {
        if (is_file($path)) {
            $config = require $path;

            if (!is_array($config)) {
                throw new RuntimeException('El archivo de configuracion debe retornar un array.');
            }

            return $config;
        }
    }

    throw new RuntimeException('No se encontro archivo de configuracion.');
}

function collect_payload(array $config): array
{
    $maxMessageLength = (int)($config['security']['max_message_length'] ?? 1200);

    $payload = [
        'name' => clean_text($_POST['name'] ?? '', 120),
        'company' => clean_text($_POST['company'] ?? '', 120),
        'phone' => clean_text($_POST['phone'] ?? '', 60),
        'email' => clean_text($_POST['email'] ?? '', 160),
        'city' => clean_text($_POST['city'] ?? '', 100),
        'machines' => clean_text($_POST['machines'] ?? '', 10),
        'interest' => clean_text($_POST['interest'] ?? 'Consulta general', 80),
        'message' => clean_text($_POST['message'] ?? '', $maxMessageLength),
        'website' => clean_text($_POST['website'] ?? '', 80),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'IP no disponible',
        'date' => date('Y-m-d H:i:s'),
    ];

    $errors = [];

    if ($payload['name'] === '') {
        $errors[] = 'nombre';
    }

    if ($payload['phone'] === '') {
        $errors[] = 'telefono';
    }

    if (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'email';
    }

    if ($payload['machines'] !== '' && (!ctype_digit($payload['machines']) || (int)$payload['machines'] < 1)) {
        $errors[] = 'cantidad de maquinas';
    }

    if ($errors !== []) {
        respond(false, 'Revise los campos: ' . implode(', ', $errors) . '.', 422);
    }

    return $payload;
}

function clean_text(mixed $value, int $maxLength): string
{
    $text = trim((string)$value);
    $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text) ?? '';
    $text = preg_replace('/\s+/u', ' ', $text) ?? '';

    if (function_exists('mb_substr')) {
        return mb_substr($text, 0, $maxLength, 'UTF-8');
    }

    return substr($text, 0, $maxLength);
}

function enforce_rate_limit(int $seconds): void
{
    if ($seconds < 1) {
        return;
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $key = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $ip) ?: 'unknown';
    $file = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'srs_pedido_' . $key . '.lock';
    $now = time();

    if (is_file($file) && ($now - (int)filemtime($file)) < $seconds) {
        respond(false, 'Espere unos segundos antes de enviar otro pedido.', 429);
    }

    @touch($file);
}

function send_email(array $payload, array $config): void
{
    $mailConfig = $config['mail'] ?? [];

    $mail = new PHPMailer(true);

    try {
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = (string)$mailConfig['host'];
        $mail->SMTPAuth = true;
        $mail->Username = (string)$mailConfig['username'];
        $mail->Password = (string)$mailConfig['password'];
        $mail->Port = (int)$mailConfig['port'];

        $encryption = strtolower((string)($mailConfig['encryption'] ?? 'ssl'));
        if ($encryption === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }

        $mail->setFrom((string)$mailConfig['from_email'], (string)$mailConfig['from_name']);
        $mail->addAddress((string)$mailConfig['to_email'], (string)$mailConfig['to_name']);
        $mail->addReplyTo($payload['email'], $payload['name']);

        $mail->isHTML(true);
        $mail->Subject = 'Nuevo pedido SRS - ' . $payload['interest'];
        $mail->Body = build_email_html($payload);
        $mail->AltBody = build_plain_message($payload);
        $mail->send();
    } catch (MailException $exception) {
        throw new RuntimeException('No se pudo enviar el correo: ' . $exception->getMessage());
    }
}

function send_telegram(array $payload, array $config): void
{
    $telegram = $config['telegram'] ?? [];

    if (!($telegram['enabled'] ?? false)) {
        return;
    }

    $token = trim((string)($telegram['bot_token'] ?? ''));
    $chatId = trim((string)($telegram['chat_id'] ?? ''));

    if ($token === '' || $chatId === '') {
        throw new RuntimeException('Telegram esta habilitado, pero faltan bot_token o chat_id.');
    }

    $url = 'https://api.telegram.org/bot' . $token . '/sendMessage';
    $data = [
        'chat_id' => $chatId,
        'text' => build_plain_message($payload),
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true,
    ];

    $response = http_post($url, $data);
    $decoded = json_decode($response, true);

    if (!is_array($decoded) || !($decoded['ok'] ?? false)) {
        throw new RuntimeException('Telegram rechazo el mensaje.');
    }
}

function http_post(string $url, array $data): string
{
    if (function_exists('curl_init')) {
        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_TIMEOUT => 12,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($response === false) {
            throw new RuntimeException('Error de conexion Telegram: ' . $error);
        }

        return (string)$response;
    }

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($data),
            'timeout' => 12,
        ],
    ]);

    $response = file_get_contents($url, false, $context);

    if ($response === false) {
        throw new RuntimeException('No se pudo conectar con Telegram.');
    }

    return $response;
}

function build_email_html(array $payload): string
{
    $rows = [
        'Nombre' => $payload['name'],
        'Empresa' => $payload['company'] ?: 'No informado',
        'Telefono' => $payload['phone'],
        'Email' => $payload['email'],
        'Ciudad' => $payload['city'] ?: 'No informado',
        'Maquinas' => $payload['machines'] ?: 'No informado',
        'Interes' => $payload['interest'],
        'Mensaje' => $payload['message'] ?: 'Sin mensaje',
        'Fecha' => $payload['date'],
        'IP' => $payload['ip'],
    ];

    $html = '<h2>Nuevo pedido SRS</h2><table cellpadding="8" cellspacing="0" border="1">';

    foreach ($rows as $label => $value) {
        $html .= '<tr><th align="left">' . escape($label) . '</th><td>' . nl2br(escape($value)) . '</td></tr>';
    }

    return $html . '</table>';
}

function build_plain_message(array $payload): string
{
    return implode("\n", [
        '<b>Nuevo pedido SRS</b>',
        '',
        'Nombre: ' . escape($payload['name']),
        'Empresa: ' . escape($payload['company'] ?: 'No informado'),
        'Telefono: ' . escape($payload['phone']),
        'Email: ' . escape($payload['email']),
        'Ciudad: ' . escape($payload['city'] ?: 'No informado'),
        'Maquinas: ' . escape($payload['machines'] ?: 'No informado'),
        'Interes: ' . escape($payload['interest']),
        'Mensaje: ' . escape($payload['message'] ?: 'Sin mensaje'),
        'Fecha: ' . escape($payload['date']),
        'IP: ' . escape($payload['ip']),
    ]);
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function respond(bool $ok, string $message, int $status = 200): never
{
    http_response_code($status);

    echo json_encode([
        'ok' => $ok,
        'message' => $message,
    ], JSON_UNESCAPED_UNICODE);

    exit;
}
