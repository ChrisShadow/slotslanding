# Despliegue en cPanel

## Objetivo

Publicar la landing SRS en cPanel usando Apache y PHP.

## Archivos a subir

Subir el contenido final a `public_html/`:

```text
dist/
server/
vendor/
composer.json
composer.lock
```

La estructura final recomendada dentro de `public_html/` es:

```text
public_html/
  index.html
  _astro/
  api/
    pedido.php
  images/
  server/
    .htaccess
    config.example.php
    config.php
  vendor/
  composer.json
  composer.lock
```

## Importante sobre credenciales

`server/config.php` contiene credenciales reales de SMTP y Telegram.

No debe incluirse en paquetes publicos, repositorios ni archivos zip compartidos. Para produccion, crear este archivo directamente en cPanel copiando `server/config.example.php` y completando los datos reales.

## Pasos de despliegue

1. Ejecutar build local:

```text
npm run build
```

2. Subir a `public_html/`:

- Todo el contenido de `dist/`.
- Carpeta `server/`, excluyendo credenciales si el zip sera compartido.
- Carpeta `vendor/`.
- `composer.json`.
- `composer.lock`.

3. En cPanel, crear o revisar:

```text
public_html/server/config.php
```

4. Confirmar permisos:

- Archivos PHP legibles por Apache.
- `server/.htaccess` presente para bloquear acceso directo.

5. Probar:

```text
https://tudominio.com/
https://tudominio.com/api/pedido.php
```

`GET /api/pedido.php` debe responder JSON con metodo no permitido. El formulario debe enviar correo y Telegram.

## Configuracion PHP en cPanel

Seleccionar PHP 8.2 o superior y habilitar:

- `curl`
- `openssl`
- `mbstring`
- `json`

## Checklist final

- HTTPS activo.
- `public_html/index.html` existe.
- `public_html/api/pedido.php` existe.
- `public_html/vendor/autoload.php` existe.
- `public_html/server/config.php` existe.
- SMTP probado.
- Telegram probado.
- Formulario probado desde el dominio real.
