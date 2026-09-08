# Prueba Técnica - Webmaster & Desarrollador WordPress

Implementación de una landing de admisión desarrollada con WordPress,
Bricks Builder y Contact Form 7, incluyendo contenido dinámico,
integración con CRM mediante API y captura de parámetros UTM.

## Requisitos

- WordPress 6.x o superior
- PHP 8.x
- Bricks Builder
- Contact Form 7

## Estructura del desarrollo

El proyecto utiliza un Child Theme para centralizar las personalizaciones
realizadas mediante código y mantenerlas independientes del theme principal.

Las funcionalidades personalizadas se organizaron en módulos dentro de
la carpeta `inc/` para evitar concentrar toda la lógica en `functions.php`.

## Custom Post Type

Se registró mediante código el Custom Post Type:

- Nombre: Programas / Carreras
- Slug: `programa`
- Soportes:
  - Título
  - Editor
  - Imagen destacada

También se registró la taxonomía personalizada:

- Nombre: Tipo de Programa
- Slug: `tipo-de-programa`

El código correspondiente se encuentra en:

`inc/cpt/programa.php`

Esto permite administrar los programas desde WordPress y utilizarlos
dinámicamente desde Bricks Builder.

## Landing Page - Bricks Builder

Se creó la página:

`Admisión - Conoce Nuestros Programas`

La landing está compuesta por:

- Header y navegación
- Hero principal con CTA
- Sección dinámica de programas
- Formulario de captación

La sección de programas utiliza Query Loop de Bricks Builder para consultar
dinámicamente el CPT `programas`.

Cada programa muestra:

- Título
- Imagen destacada
- Tipo de programa
- Enlace de acceso

La estructura utiliza elementos semánticos y clases CSS reutilizables,
considerando adaptación responsive para desktop, tablet y móvil.

## Contact Form 7

El formulario de admisión captura:

- Nombre
- Correo electrónico
- Teléfono / WhatsApp
- Programa de interés
- Información de origen / UTM

El campo Programa de interés se genera dinámicamente a partir de los
programas registrados en WordPress, evitando mantener manualmente las
opciones del formulario.

## Integración con CRM

La integración fue desarrollada mediante PHP utilizando hooks de
Contact Form 7 y la HTTP API nativa de WordPress.

Flujo de integración:

```text
Contact Form 7
        ↓
WPCF7_Submission
        ↓
Sanitización
        ↓
Payload JSON
        ↓
wp_remote_post()
        ↓
CRM / Webhook
```

Para las pruebas se utilizó Webhook.site como endpoint de captura,
permitiendo verificar en tiempo real la información enviada desde
el formulario.

Antes de generar el payload, los datos son sanitizados utilizando
funciones nativas de WordPress como:

- `sanitize_text_field()`
- `sanitize_email()`

La respuesta del endpoint es validada mediante:

- `is_wp_error()`
- Código de estado HTTP

En caso de error en la comunicación con el CRM, el incidente se registra
sin interrumpir el flujo normal del formulario.

## Configuración del endpoint

La URL del CRM se mantiene fuera del código del Child Theme para evitar
almacenar configuraciones específicas del entorno dentro del repositorio.

Debe definirse en `wp-config.php`:

```php
define( 'VISIVA_CRM_WEBHOOK_URL', 'https://webhook.site/...' );
```

### SSL en entorno local

Durante el desarrollo local con WAMP se incorporó una configuración
opcional para deshabilitar temporalmente la validación SSL debido a la
configuración de certificados CA del entorno local.

```php
define( 'VISIVA_CRM_SSLVERIFY', false );
```

Esta configuración es exclusivamente para desarrollo local.

En producción la validación SSL debe permanecer habilitada.

## Parámetros UTM

La implementación permite capturar información de atribución de campañas
y enviarla junto con el lead hacia el CRM.

Parámetros utilizados:

- `utm_source`
- `utm_medium`
- `utm_campaign`

Ejemplo de acceso a la landing:

```text
/admision-conoce-nuestros-programas/?utm_source=facebook&utm_medium=paid_social&utm_campaign=admision_2026
```

Esta información permite identificar el origen de los leads generados
desde campañas digitales.

## Git y control de versiones

El desarrollo utiliza un flujo basado en ramas por funcionalidad y
Pull Requests hacia `main`.

Entre las ramas utilizadas:

- `feature/programas-cpt`
- `feature/cf7-program-select`
- `feature/cf7-crm-integration`
- `chore/gitignore`
- `docs/documentation`

Los cambios se organizaron mediante commits atómicos y descriptivos,
separando las principales funcionalidades del desarrollo.

El archivo `.gitignore` evita versionar archivos temporales, logs,
dependencias y configuraciones propias del entorno de desarrollo.

## Prueba de integración CRM

Para reproducir la integración:

1. Crear un endpoint de prueba en Webhook.site.
2. Configurar la constante `VISIVA_CRM_WEBHOOK_URL` en `wp-config.php`.
3. Acceder a la landing de admisión.
4. Completar los datos del formulario.
5. Seleccionar un programa.
6. Enviar el formulario.
7. Revisar Webhook.site y verificar la recepción del payload JSON.

Para probar la atribución UTM se puede acceder a la landing agregando
parámetros como:

```text
?utm_source=facebook&utm_medium=paid_social&utm_campaign=admision_2026
```

Ejemplo simplificado del payload recibido:

```json
{
  "nombre_completo": "Usuario Prueba",
  "correo_electronico": "usuario@example.com",
  "telefono_whatsapp": "999999999",
  "programa_interes": "Ingeniería de Sistemas",
  "utm_source": "facebook",
  "utm_medium": "paid_social",
  "utm_campaign": "admision_2026"
}
```

## Terminal Linux

Los comandos correspondientes a los escenarios de permisos,
seguridad, detección de archivos y mantenimiento del servidor
solicitados en la prueba se encuentran documentados en:

[`TERMINAL.md`](TERMINAL.md)

## Consideraciones técnicas

- El contenido académico se administra mediante un CPT en lugar de estar
  definido directamente en la landing.
- Bricks Builder consume dinámicamente los programas mediante Query Loop.
- El listado de programas del formulario se genera desde WordPress para
  evitar duplicación de información.
- La integración con el CRM utiliza `wp_remote_post()` y no depende de
  plugins externos de automatización.
- Los datos son sanitizados antes de construir el payload.
- La URL del CRM se mantiene fuera del repositorio.
- La validación SSL solo puede deshabilitarse en el entorno local de desarrollo.
- Los parámetros UTM permiten conservar información de atribución del lead.

## Documentación

- `README.md`: descripción, arquitectura y reproducción del proyecto.
- `TERMINAL.md`: comandos de administración y diagnóstico Linux.