# Plan de Implementacion

## Fase 1 - Documentacion

Estado: completada.

Objetivo:

- Ordenar el contenido del producto.
- Definir stack tecnico.
- Definir flujo de pedidos.
- Definir criterios de despliegue en cPanel.

Entregables:

- `README.md`
- `docs/producto.md`
- `docs/arquitectura.md`
- `docs/flujo-pedidos.md`
- `docs/cpanel.md`
- `docs/plan-implementacion.md`

## Fase 2 - Base del proyecto

Estado: completada.

Objetivo:

- Crear proyecto Astro.
- Configurar estructura de carpetas.
- Agregar estilos globales.
- Preparar assets e imagenes.

Entregables:

- `package.json`
- `astro.config.mjs`
- `tsconfig.json`
- `src/pages/index.astro`
- `src/layouts/BaseLayout.astro`
- `src/styles/global.css`
- `public/images/`

## Fase 3 - Landing visual

Estado: completada.

Objetivo:

- Construir la experiencia principal.
- Adaptar el contenido de SRS a una narrativa comercial.
- Agregar efectos visuales modernos sin comprometer rendimiento.

Secciones:

- Hero.
- Beneficios.
- Combo de Sistemas.
- Kit GSC.
- Actores de control.
- Precios.
- Formulario de pedido.
- Contacto/WhatsApp.

Entregables:

- Hero visual con imagen generada para SRS.
- Secciones comerciales completas.
- Grillas de beneficios, modulos, actores de control y precios.
- Formulario visual de pedido preparado para conectar al backend.
- Animaciones suaves de aparicion al hacer scroll.

## Fase 4 - Backend de pedidos

Estado: completada.

Objetivo:

- Crear endpoint PHP.
- Integrar PHPMailer.
- Integrar Telegram Bot API.
- Validar formulario y responder JSON.

Entregables:

- `public/api/pedido.php`
- `server/config.example.php`
- `server/.htaccess`
- `composer.json`
- Documentacion de variables necesarias.

## Fase 5 - Pruebas

Estado: completada.

Objetivo:

- Validar experiencia responsive.
- Probar envio de pedidos.
- Revisar errores comunes de cPanel.

Pruebas:

- Formulario con datos validos.
- Formulario con datos invalidos.
- Email recibido.
- Telegram recibido.
- Prueba en movil.
- Prueba en desktop.

Entregables:

- Validacion de build Astro.
- Validacion de sintaxis PHP.
- Pruebas de endpoint con respuestas JSON.
- Confirmacion de assets y archivos de despliegue.
- `docs/pruebas.md`

## Fase 6 - Despliegue

Estado: completada.

Objetivo:

- Construir archivos finales.
- Subir a cPanel.
- Configurar PHP y secretos.
- Verificar funcionamiento en dominio real.

Checklist:

- PHP 8.2+ activo.
- HTTPS activo.
- `dist/` subido correctamente.
- Endpoint PHP accesible.
- SMTP configurado.
- Telegram configurado.
- Formulario probado en produccion.

Entregables:

- Build final generado en `dist/`.
- Documentacion de despliegue en `docs/despliegue.md`.
- Paquete para cPanel preparado sin credenciales reales.
