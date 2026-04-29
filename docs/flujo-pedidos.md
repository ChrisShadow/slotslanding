# Flujo de Pedidos

## Objetivo

Permitir que un visitante solicite una prueba gratuita, pida una cotizacion o envie una consulta desde la landing. El pedido debe llegar por correo y tambien por Telegram.

## Campos iniciales del formulario

- Nombre y apellido.
- Empresa.
- Telefono.
- Email.
- Ciudad.
- Cantidad estimada de maquinas.
- Tipo de interes: prueba gratuita, cotizacion, demo o consulta.
- Mensaje.

## Flujo funcional

1. El visitante completa el formulario en la landing.
2. El frontend valida campos obligatorios.
3. El frontend envia los datos a `/api/pedido.php`.
4. PHP valida nuevamente los datos del lado del servidor.
5. PHP envia un email por SMTP usando PHPMailer.
6. PHP envia un mensaje a Telegram usando Telegram Bot API.
7. PHP responde al frontend con exito o error.
8. La landing muestra una confirmacion clara al usuario.

## Respuesta JSON esperada

Exito:

```json
{
  "ok": true,
  "message": "Pedido enviado correctamente."
}
```

Error:

```json
{
  "ok": false,
  "message": "No se pudo enviar el pedido. Intente nuevamente."
}
```

## Medidas antispam

- Campo honeypot invisible.
- Limite basico por IP.
- Validacion de email.
- Longitud maxima para mensajes.
- Rechazo de metodos distintos a POST.

## Email

El correo debe incluir:

- Datos del contacto.
- Cantidad de maquinas.
- Tipo de solicitud.
- Mensaje.
- Fecha y hora.
- IP de origen.

## Telegram

El mensaje de Telegram debe ser breve y facil de leer desde el celular.

Formato sugerido:

```text
Nuevo pedido SRS

Nombre: Juan Perez
Empresa: Slots Paraguay
Telefono: +595...
Email: contacto@empresa.com
Ciudad: Asuncion
Maquinas: 35
Interes: Prueba gratuita
Mensaje: Quiero coordinar una demo.
```

## Variables sensibles

Los siguientes datos no deben estar escritos directamente en el frontend:

- Credenciales SMTP.
- Email receptor.
- Token del bot de Telegram.
- Chat ID de Telegram.

## Archivos implementados

- `public/api/pedido.php`: endpoint publico que recibe el formulario.
- `server/config.example.php`: plantilla de configuracion privada.
- `server/config.php`: archivo real de configuracion, no versionado.
- `composer.json`: dependencia de PHPMailer.

## Instalacion de PHPMailer

En un entorno con Composer:

```text
composer install --no-dev --optimize-autoloader
```

El endpoint espera encontrar `vendor/autoload.php` en la raiz del proyecto o del hosting.
