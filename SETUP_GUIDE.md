# Guía Rápida de Configuración

## 1. Configuración de Base de Datos
Crea tu base de datos de autenticación y asegúrate de que tenga una tabla `account` con estas columnas:

### Esquema PostgreSQL
```sql
CREATE TABLE account (
  id SERIAL PRIMARY KEY,
  username VARCHAR(32) NOT NULL UNIQUE,
  sha_pass_hash VARCHAR(40) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  last_ip VARCHAR(15) NOT NULL,
  expansion SMALLINT NOT NULL DEFAULT 0
);

CREATE INDEX idx_account_username ON account(username);
CREATE INDEX idx_account_email ON account(email);
```

### Esquema MySQL (Legado)
```sql
CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `sha_pass_hash` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `last_ip` varchar(15) NOT NULL,
  `expansion` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

## 2. Configurar Ajustes
1. Copiar `inc/settings.template.php` a `inc/settings.php`
2. Editar `inc/settings.php` con tus valores:
   - Credenciales de base de datos (HOST, PORT, USER, PASS, DB)
     - Para PostgreSQL: Puerto predeterminado es 5432, usuario predeterminado es 'postgres'
     - Para MySQL: Puerto predeterminado es 3306, usuario predeterminado es 'root'
   - Claves de Google reCAPTCHA (obtener de https://www.google.com/recaptcha/admin)
   - Dirección de realmlist
   - Mensaje de éxito
   - Versión de expansión (4 para Cataclysm)

## 3. Crear Directorio de Registros
```bash
mkdir logs
chmod 755 logs
```

## 4. Permisos de Archivos
```bash
chmod 755 inc/
chmod 644 inc/*.php
chmod 600 inc/settings.php  # Protección extra para credenciales
chmod 755 logs/
```

## 5. Configuración del Servidor Web

### Apache
El archivo `.htaccess` ya está configurado. Asegúrate de:
- `mod_rewrite` está habilitado
- `AllowOverride All` en tu VirtualHost
- `mod_headers` está habilitado

### Nginx
Agrega esto a tu bloque de servidor:
```nginx
location ~ /inc/ {
    deny all;
    return 403;
}

location ~ /logs/ {
    deny all;
    return 403;
}

location ~ \.(log)$ {
    deny all;
    return 403;
}

# Cabeceras de seguridad
add_header X-Frame-Options "DENY" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header X-Content-Type-Options "nosniff" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
```

## 6. Configuración de reCAPTCHA
1. Ir a https://www.google.com/recaptcha/admin
2. Registrar un nuevo sitio
3. Elegir reCAPTCHA v2 → Casilla de verificación "No soy un robot"
4. Agregar tu(s) dominio(s)
5. Copiar la Clave del Sitio y la Clave Secreta a `inc/settings.php`

## 7. SSL/HTTPS (Recomendado para Producción)
1. Obtener un certificado SSL (Let's Encrypt es gratuito)
2. Configurar tu servidor web para HTTPS
3. Actualizar `inc/security.php` línea 10:
   ```php
   ini_set('session.cookie_secure', 1);  // Cambiar 0 a 1
   ```

## 8. Probar tu Instalación
1. Visitar tu página de registro
2. Intentar registrarse con una cuenta de prueba
3. Verificar `logs/security.log` para cualquier error
4. Verificar que la cuenta aparezca en tu base de datos

## 9. Lista de Verificación de Producción
- [ ] HTTPS habilitado
- [ ] Claves reales de reCAPTCHA configuradas
- [ ] Credenciales de base de datos aseguradas
- [ ] Permisos de archivos establecidos correctamente
- [ ] Directorio de registros escribible
- [ ] settings.php no accesible públicamente
- [ ] Errores de PHP deshabilitados en producción
- [ ] Monitoreo regular de registros de seguridad programado

## 10. Solución de Problemas

### "No se puede conectar a la base de datos"
- Verificar credenciales de base de datos en `inc/settings.php`
- Verificar que PostgreSQL (o MySQL) esté ejecutándose
- Asegurar que el usuario tenga los permisos adecuados
- Verificar que la base de datos exista
- Para PostgreSQL, verificar que el puerto sea 5432 (o puerto personalizado si está configurado)
- Para MySQL, verificar que el puerto sea 3306

### "Falló la verificación del captcha"
- Verificar que las claves de reCAPTCHA sean correctas
- Verificar que el dominio esté registrado con Google
- Asegurar que la clave del sitio coincida con la clave secreta
- Probar con un navegador diferente

### "Demasiados intentos"
- Límite de tasa activado (5 por 5 minutos)
- Esperar 5 minutos o limpiar sesión
- Ajustar límite de tasa en `inc/functions.php` si es necesario

### No se están creando registros
- Verificar que el directorio `logs/` existe
- Verificar permisos de escritura: `chmod 755 logs`
- Verificar registro de errores de PHP para problemas

## Soporte
- Leer `SECURITY_README.md` para documentación detallada
- Ver `security-info.html` para guía visual de seguridad
- Verificar registros de seguridad en `logs/security.log`

---

**Recuerda:** ¡Siempre prueba en un ambiente de desarrollo antes de desplegar a producción!
