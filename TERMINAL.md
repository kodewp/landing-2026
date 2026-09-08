# TERMINAL.md

Comandos básicos para administración y diagnóstico de una instalación WordPress ubicada en `/var/www/html`.

## 1. Permisos y seguridad

### Asignar propietario y grupo

Asignar `www-data` como propietario y grupo de toda la instalación:

```bash
sudo chown -R www-data:www-data /var/www/html
```

### Permisos de directorios

Asignar permisos `755` únicamente a directorios:

```bash
sudo find /var/www/html -type d -exec chmod 755 {} \;
```

### Permisos de archivos

Asignar permisos `644` únicamente a archivos:

```bash
sudo find /var/www/html -type f -exec chmod 644 {} \;
```

Esto permite mantener correctamente la diferencia entre permisos de carpetas y archivos.

### Permisos para uploads

Asegurar propietario y permisos correctos para la subida de medios:

```bash
sudo chown -R www-data:www-data /var/www/html/wp-content/uploads

sudo find /var/www/html/wp-content/uploads -type d -exec chmod 755 {} \;

sudo find /var/www/html/wp-content/uploads -type f -exec chmod 644 {} \;
```

---

## 2. Búsqueda y detección de archivos

### Archivos PHP modificados en los últimos 7 días

```bash
find /var/www/html/wp-content/plugins/ -type f -name "*.php" -mtime -7 -print
```

Este comando permite identificar archivos PHP modificados recientemente dentro del directorio de plugins.

### Buscar código potencialmente malicioso

Buscar recursivamente la cadena `eval(base64_decode(` dentro de archivos PHP:

```bash
grep -RFn --include="*.php" "eval(base64_decode(" /var/www/html/wp-content/plugins/
```

La aparición de esta cadena no confirma automáticamente una infección, pero sirve como indicador para realizar una revisión del archivo.

---

## 3. Mantenimiento y espacio en disco

### Revisar uso general del disco

```bash
df -h
```

Muestra el espacio utilizado y disponible en formato legible.

### Identificar los 5 elementos más pesados en `/var/log`

```bash
sudo du -ah /var/log | sort -rh | head -n 5
```

### Identificar los 5 elementos más pesados en `/var/www`

```bash
sudo du -ah /var/www | sort -rh | head -n 5
```

Estos comandos ayudan a identificar rápidamente qué archivos o directorios están consumiendo mayor espacio.

### Comprimir logs antiguos

Ejemplo para archivos `.log` con más de 30 días:

```bash
sudo find /var/log -type f -name "*.log" -mtime +30 -print0 | \
sudo tar --null -czf /tmp/logs-antiguos.tar.gz --files-from=-
```

Verificar el contenido del archivo comprimido antes de eliminar los originales:

```bash
tar -tzf /tmp/logs-antiguos.tar.gz
```

Después de verificar correctamente el respaldo:

```bash
sudo find /var/log -type f -name "*.log" -mtime +30 -delete
```

> En producción se recomienda revisar previamente los archivos encontrados antes de ejecutar una eliminación definitiva.