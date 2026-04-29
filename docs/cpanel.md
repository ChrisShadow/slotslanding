# Configuracion en cPanel

## Objetivo

Desplegar la landing en un hosting con cPanel, Apache y PHP, manteniendo una instalacion simple y compatible.

## Requisitos del hosting

- cPanel con acceso a administrador de archivos o FTP.
- Apache habilitado.
- PHP 8.2 o superior seleccionado para el dominio.
- Extension PHP `curl` habilitada para Telegram.
- Extension PHP `openssl` habilitada para SMTP seguro.
- Cuenta de correo SMTP o credenciales SMTP externas.

## Configuracion PHP

En cPanel:

1. Entrar a "Select PHP Version" o herramienta equivalente.
2. Seleccionar PHP 8.2 o superior.
3. Activar extensiones necesarias:
   - `curl`
   - `openssl`
   - `mbstring`
   - `json`
4. Guardar cambios.

## Despliegue de la landing

El frontend se construye localmente y se sube a cPanel.

Flujo esperado:

```text
npm install
npm run build
subir contenido de dist/ a public_html/
```

Ademas de `dist/`, el backend necesita:

```text
server/
vendor/
composer.json
composer.lock
```

## Ubicacion del endpoint PHP

Opcion simple:

```text
public_html/api/pedido.php
```

El archivo fuente esta en:

```text
public/api/pedido.php
```

## Manejo de secretos

Preferencia:

```text
home_del_usuario/server/config.php
```

Alternativa si solo se puede usar `public_html`:

```text
public_html/server/config.php
```

En ese caso, se debe proteger la carpeta con `.htaccess`.

La plantilla esta en `server/config.example.php`. Para produccion se debe copiar como `server/config.php` y completar los datos reales.

## Dependencias PHP

El backend usa PHPMailer instalado con Composer.

En cPanel, si hay Terminal o Composer disponible:

```text
composer install --no-dev --optimize-autoloader
```

Si el hosting no permite Composer, se puede generar la carpeta `vendor/` en otro equipo y subirla junto con el proyecto.

## SMTP

Datos necesarios:

- Host SMTP.
- Puerto.
- Usuario.
- Contrasena.
- Tipo de seguridad: SSL/TLS o STARTTLS.
- Email remitente.
- Email receptor.

## Telegram

Datos necesarios:

- Token del bot.
- Chat ID del destinatario o grupo.

Pasos generales:

1. Crear bot con BotFather.
2. Obtener token.
3. Agregar el bot al chat o grupo.
4. Obtener el chat ID.
5. Configurar esos datos en el backend PHP.

## Seguridad minima

- No mostrar errores PHP en produccion.
- Registrar errores en archivo privado si el hosting lo permite.
- Proteger archivos de configuracion.
- Usar HTTPS.
- Usar SMTP autenticado.
- Validar todo dato recibido desde el formulario.
