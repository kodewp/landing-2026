# Prueba Técnica - Webmaster & Desarrollador WordPress

Implementación de una landing de admisión desarrollada con WordPress,
Bricks Builder y Contact Form 7, incluyendo contenido dinámico,
integración con CRM mediante API y captura de parámetros UTM.

## Requisitos

- WordPress 6.x o superior
- Bricks Builder
- Contact Form 7
- PHP 8.x

## Estructura del desarrollo

El proyecto utiliza un Child Theme para centralizar las
personalizaciones realizadas mediante código.

### Custom Post Type

Se registró mediante código el Custom Post Type:

- Nombre: Programas / Carreras
- Slug: `programas`
- Soportes:
  - Título
  - Editor
  - Imagen destacada

También se registró la taxonomía personalizada:

- Tipo de Programa
- Slug: `tipo-de-programa`

El código correspondiente se encuentra en:

`inc/cpt/programa.php`

## Landing Page - Bricks Builder

Se creó la página:

`Admisión - Conoce Nuestros Programas`

La landing está compuesta por:

- Header y navegación
- Hero principal con CTA
- Sección dinámica de programas
- Formulario de captación

La sección de programas utiliza Query Loop de Bricks para consultar
dinámicamente el CPT `programas`.

Cada programa muestra:

- Título
- Imagen destacada
- Tipo de programa
- Enlace de acceso

La estructura fue desarrollada utilizando elementos semánticos y
clases CSS reutilizables, considerando adaptación responsive para
desktop, tablet y móvil.

## Contact Form 7

El formulario de admisión captura:

- Nombre
- Correo electrónico
- Teléfono / WhatsApp
- Programa de interés
- Parámetros UTM

El campo Programa de interés se genera dinámicamente utilizando los
programas registrados en WordPress.

## Integración con CRM

La integración se realiza mediante código PHP utilizando hooks
nativos de Contact Form 7.

Flujo:

Contact Form 7
→ WPCF7_Submission
→ Sanitización
→ Payload JSON
→ wp_remote_post()
→ CRM / Webhook

Para las pruebas se utilizó Webhook.site como endpoint de captura.

Los datos son sanitizados antes de generar el payload utilizando
funciones nativas de WordPress como:

- `sanitize_text_field()`
- `sanitize_email()`

La respuesta HTTP es validada mediante `is_wp_error()` y el código
de estado HTTP retornado por el endpoint.

## Configuración del endpoint

La URL del CRM no se encuentra almacenada directamente dentro del
código del theme.

Debe definirse en `wp-config.php`:

define( 'VISIVA_CRM_WEBHOOK_URL', 'https://webhook.site/...' );

### SSL en entorno local

Durante el desarrollo local con WAMP fue necesario disponer de una
configuración opcional para deshabilitar temporalmente la validación
SSL debido a la ausencia del certificado CA en dicho entorno.

define( 'VISIVA_CRM_SSLVERIFY', false );

Esta configuración es exclusivamente para desarrollo local.

En producción la validación SSL debe permanecer habilitada.

## Parámetros UTM

La implementación permite incorporar información de atribución al
payload enviado al CRM.

Ejemplo:

- `utm_source`
- `utm_medium`
- `utm_campaign`

Esto permite identificar el origen de los leads generados desde
campañas digitales.

## Git

El desarrollo utiliza un flujo basado en ramas por funcionalidad y
Pull Requests hacia `main`.

Entre las ramas utilizadas:

- `feature/programas-cpt`
- `feature/cf7-program-select`
- `feature/cf7-crm-integration`
- `chore/gitignore`

Los cambios se organizaron mediante commits atómicos y descriptivos.

## Prueba de integración CRM

1. Configurar `VISIVA_CRM_WEBHOOK_URL`.
2. Acceder a la landing de admisión.
3. Completar el formulario.
4. Seleccionar un programa.
5. Enviar el formulario.
6. Revisar Webhook.site.
7. Verificar la recepción del payload JSON.

Ejemplo de payload:

{
  "nombre_completo": "Miguel",
  "correo_electronico": "miguel@example.com",
  "telefono_whatsapp": "999999999",
  "programa_interes": "Ingeniería de Sistemas",
  "utm_source": "facebook",
  "utm_medium": "paid_social",
  "utm_campaign": "admision_2026"
}

## Terminal Linux

Las respuestas correspondientes a los escenarios de administración
Linux solicitados en la prueba se encuentran documentadas en:

`TERMINAL.md`