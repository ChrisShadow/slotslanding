# Arquitectura Tecnica

## Enfoque recomendado

El proyecto se implementara como una landing estatica moderna generada con Astro y un backend PHP liviano para recibir pedidos.

Esta arquitectura se adapta bien a cPanel porque la parte publica puede subirse como archivos estaticos a `public_html`, mientras que PHP se ejecuta nativamente con Apache.

## Componentes

### Frontend

Responsable de la experiencia visual y del formulario de pedido.

Tecnologias:

- Astro.
- HTML semantico.
- CSS moderno.
- JavaScript para efectos e interacciones.
- Imagenes optimizadas.

Funciones:

- Hero visual del producto.
- Secciones de beneficios, modulos, Kit GSC, actores de control y precios.
- Animaciones suaves.
- Formulario de pedido/contacto.
- Boton de WhatsApp.
- SEO basico y responsive design.

### Backend PHP

Responsable de recibir y procesar pedidos.

Tecnologias:

- PHP 8.2 o superior.
- PHPMailer.
- Telegram Bot API.

Funciones:

- Validar datos del formulario.
- Enviar email por SMTP.
- Enviar aviso a Telegram.
- Responder al frontend con JSON.
- Aplicar medidas basicas antispam.

### Hosting

Entorno esperado:

- cPanel.
- Apache.
- Selector de version PHP.
- Dominio o subdominio apuntando a `public_html`.
- Cuenta SMTP propia del dominio o servicio externo.

## Estructura esperada del proyecto

```text
slotslanding/
  docs/
  public/
  src/
    components/
    layouts/
    pages/
    styles/
  server/
    pedido.php
    config.example.php
  package.json
  astro.config.mjs
  README.md
```

## Estructura esperada en produccion

```text
public_html/
  index.html
  assets/
  pedido.php
  server-config/
```

Nota: los secretos reales no deben quedar expuestos en carpetas publicas si el hosting permite ubicarlos fuera de `public_html`. Si no es posible, deben protegerse con configuracion de Apache.

## Versiones recomendadas

- PHP: 8.2 o superior.
- Node.js local: 18.17.1 o superior. Recomendado: Node 20 LTS.
- Apache: el incluido por cPanel.

## Por que no usar Laravel en esta fase

Laravel es una buena opcion para una aplicacion administrativa completa, pero para una landing con formulario puede ser mas pesado de lo necesario. En esta etapa conviene priorizar velocidad, compatibilidad con cPanel y despliegue simple.
