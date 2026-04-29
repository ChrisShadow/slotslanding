# SRS Landing

Landing moderna para presentar SRS, Sistema de Recaudacion de Slots.

El proyecto se plantea como una landing estatica moderna, con un backend PHP liviano para recibir pedidos y enviar notificaciones por correo y Telegram. Esta estructura esta pensada para funcionar bien en cPanel con Apache y multiples versiones de PHP.

## Documentacion

- [Resumen del producto](docs/producto.md)
- [Arquitectura tecnica](docs/arquitectura.md)
- [Flujo de pedidos](docs/flujo-pedidos.md)
- [Configuracion en cPanel](docs/cpanel.md)
- [Plan de implementacion](docs/plan-implementacion.md)
- [Pruebas](docs/pruebas.md)
- [Despliegue](docs/despliegue.md)

## Stack recomendado

- Astro para generar la landing estatica.
- HTML, CSS y JavaScript para la experiencia visual.
- PHP 8.2 o superior para el endpoint de pedidos.
- PHPMailer para envio SMTP.
- Telegram Bot API para avisos instantaneos.
- Apache/cPanel como entorno de despliegue.

## Backend de pedidos

El formulario envia datos a `/api/pedido.php`. Para activar el envio real:

1. Instalar dependencias PHP con Composer.
2. Copiar `server/config.example.php` como `server/config.php`.
3. Completar SMTP, correo receptor y datos de Telegram.
4. Subir `dist/`, `server/` y `vendor/` al hosting segun la documentacion de cPanel.

## Objetivo

Construir una landing rapida, profesional y responsive que comunique los beneficios de SRS, muestre sus modulos, explique el Kit GSC y permita a potenciales clientes solicitar una prueba o pedir informacion.
