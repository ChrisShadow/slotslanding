# Pruebas

## Verificaciones tecnicas

Ejecutar antes de desplegar:

```text
npm run build
php -l public/api/pedido.php
php -l server/config.example.php
```

Resultados esperados:

- Astro genera `dist/` sin errores.
- `dist/index.html` existe.
- `dist/api/pedido.php` existe.
- `dist/images/srs-hero-dashboard.png` existe.
- `dist/images/kitgsc.png` existe.
- `vendor/autoload.php` existe para PHPMailer.

## Pruebas del endpoint

Servidor local PHP:

```text
php -S 127.0.0.1:8000 -t dist
```

Casos esperados:

- `GET /api/pedido.php`: responde `405` con JSON.
- `POST /api/pedido.php` con email invalido: responde `422` con JSON.
- `POST /api/pedido.php` con datos validos: envia correo y Telegram.

## Prueba manual del formulario

Abrir:

```text
http://127.0.0.1:8000/
```

Completar:

- Nombre.
- Telefono.
- Email valido.
- Ciudad.
- Cantidad de maquinas.
- Interes.
- Mensaje.

Resultado esperado:

- La landing muestra `Pedido enviado correctamente.`
- El correo llega al `to_email` configurado.
- Telegram recibe el aviso.

## Notas

- En local la IP puede aparecer como `127.0.0.1`.
- En produccion normalmente se registra la IP publica del visitante.
- Si Gmail rechaza el envio, usar una contrasena de aplicacion.
- Si Telegram falla, revisar `bot_token`, `chat_id` y que el bot tenga acceso al chat.
