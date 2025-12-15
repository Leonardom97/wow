# Lista de Verificación de Seguridad para Administradores

Usa esta lista de verificación para asegurar que tu sistema de registro de WoW esté correctamente asegurado.

## Lista de Verificación Pre-Despliegue

### Configuración
- [ ] `inc/settings.php` configurado con credenciales correctas de base de datos
- [ ] Claves de Google reCAPTCHA agregadas (tanto clave del sitio como clave secreta)
- [ ] Realmlist configurado correctamente
- [ ] Versión de expansión establecida correctamente (4 para Cataclysm)
- [ ] Mensaje de éxito personalizado para tu servidor

### Configuración de Seguridad
- [ ] Directorio `logs/` creado con permisos de escritura (chmod 755)
- [ ] `inc/settings.php` protegido (chmod 600)
- [ ] Archivo `.htaccess` en su lugar (Apache) o reglas equivalentes de Nginx
- [ ] Certificado HTTPS/SSL instalado
- [ ] Seguridad de sesión habilitada (`session.cookie_secure = 1` cuando se usa HTTPS)

### Pruebas
- [ ] Probar registro con datos válidos (debería tener éxito)
- [ ] Probar registro con usuario inválido (debería fallar)
- [ ] Probar registro con correo electrónico inválido (debería fallar)
- [ ] Probar registro con contraseña débil (debería fallar)
- [ ] Probar registro sin reCAPTCHA (debería fallar)
- [ ] Probar limitación de tasa (intentar 6 registros rápidamente, el 6º debería fallar)
- [ ] Verificar `logs/security.log` para registro adecuado
- [ ] Verificar cuenta creada en base de datos
- [ ] Probar en dispositivo móvil (diseño adaptable)

### Servidor Web
- [ ] PHP 7.4+ instalado
- [ ] MySQL 5.7+ instalado
- [ ] Extensiones PHP requeridas habilitadas (PDO, PDO_MySQL, session, json)
- [ ] `display_errors = Off` en producción
- [ ] `log_errors = On` en producción
- [ ] Firma del servidor oculta
- [ ] Listado de directorios deshabilitado

## Lista de Verificación Post-Despliegue

### Monitoreo
- [ ] Registros de seguridad revisados diariamente (`logs/security.log`)
- [ ] Base de datos monitoreada para cuentas sospechosas
- [ ] Recursos del servidor monitoreados (CPU, memoria, conexiones)
- [ ] Efectividad de limitación de tasa revisada
- [ ] Intentos de registro fallidos analizados

### Mantenimiento
- [ ] Rotación de registros configurada (los registros pueden crecer mucho)
- [ ] Respaldos regulares de base de datos
- [ ] Actualizaciones de PHP y MySQL programadas
- [ ] Suscripciones a avisos de seguridad activas
- [ ] Lista de dominios de correo desechable actualizada periódicamente

### Fortalecimiento de Seguridad
- [ ] Firewall configurado (permitir solo puertos necesarios)
- [ ] fail2ban o similar instalado (opcional pero recomendado)
- [ ] Usuario de base de datos tiene solo privilegios mínimos requeridos
- [ ] Zona horaria del servidor configurada correctamente
- [ ] Auditorías de seguridad regulares programadas

## Revisión Mensual de Seguridad

### Verificar Estos Elementos Mensualmente:
- [ ] Revisar `logs/security.log` para patrones inusuales
- [ ] Actualizar lista de dominios de correo desechable si está configurada
- [ ] Verificar actualizaciones de seguridad de PHP y MySQL
- [ ] Revisar intentos de registro fallidos
- [ ] Verificar que el certificado HTTPS no esté por expirar
- [ ] Probar sistema de registro de extremo a extremo
- [ ] Revisar configuración de limitación de tasa (ajustar si es necesario)
- [ ] Verificar base de datos para cualquier cuenta sospechosa
- [ ] Verificar que el sistema de respaldo esté funcionando

## Respuesta a Incidentes

### Si Detectas un Problema de Seguridad:

1. **Acciones Inmediatas:**
   - [ ] Revisar `logs/security.log` para detalles
   - [ ] Bloquear direcciones IP sospechosas a nivel de firewall
   - [ ] Verificar base de datos para cuentas no autorizadas
   - [ ] Verificar que no se hayan exfiltrado datos

2. **Investigación:**
   - [ ] Identificar el vector de ataque
   - [ ] Determinar alcance del compromiso
   - [ ] Verificar archivos modificados
   - [ ] Revisar registros de acceso del servidor web

3. **Remediación:**
   - [ ] Parchear la vulnerabilidad
   - [ ] Cambiar credenciales de base de datos
   - [ ] Actualizar claves de reCAPTCHA
   - [ ] Eliminar cualquier cuenta maliciosa
   - [ ] Notificar a usuarios afectados si es necesario

4. **Prevención:**
   - [ ] Actualizar medidas de seguridad
   - [ ] Agregar registro adicional
   - [ ] Implementar reglas de firewall adicionales
   - [ ] Programar revisiones de seguridad más frecuentes

## Optimización de Rendimiento

### Si el Registro es Lento:
- [ ] Verificar índices de base de datos (usuario y correo deben estar indexados)
- [ ] Verificar recursos adecuados del servidor
- [ ] Considerar CDN para recursos estáticos
- [ ] Habilitar caché de opcode (OPcache)
- [ ] Optimizar consultas de base de datos
- [ ] Verificar latencia de red a servidores de reCAPTCHA

## Problemas Comunes y Soluciones

### Problema: Error "Demasiados intentos"
- **Solución:** El usuario necesita esperar 5 minutos, o ajustar límite de tasa en `inc/functions.php`

### Problema: reCAPTCHA no aparece
- **Solución:** Verificar clave del sitio de reCAPTCHA, verificar que el dominio esté registrado, asegurar que JavaScript esté habilitado

### Problema: Falló la conexión a base de datos
- **Solución:** Verificar credenciales, verificar que MySQL esté ejecutándose, asegurar que el usuario tenga permisos

### Problema: No se están creando registros
- **Solución:** Crear directorio `logs/`, establecer permisos de escritura (chmod 755)

### Problema: Limitación de tasa demasiado estricta
- **Solución:** Ajustar parámetros en `inc/functions.php` línea 12: `CheckRateLimit($ip_address, 5, 300)`

## Herramientas Recomendadas

### Monitoreo
- **Fail2ban:** Bloqueo automático de IP para actividad sospechosa
- **Monit:** Monitoreo y alertas del servidor
- **Logwatch:** Resúmenes diarios por correo de archivos de registro
- **New Relic/DataDog:** Monitoreo de rendimiento de aplicaciones

### Seguridad
- **ModSecurity:** Web Application Firewall (WAF)
- **Cloudflare:** Protección DDoS y CDN
- **Let's Encrypt:** Certificados SSL gratuitos
- **Qualys SSL Labs:** Probar configuración SSL

### Respaldo
- **mysqldump:** Respaldos de base de datos
- **rsync:** Respaldos de archivos
- **BorgBackup:** Respaldos cifrados y deduplicados
- **AWS S3/Backblaze B2:** Almacenamiento de respaldo fuera del sitio

## Contacto y Soporte

### Recursos
- Leer `SECURITY_README.md` para documentación detallada de seguridad
- Ver `SETUP_GUIDE.md` para instrucciones de configuración
- Verificar `security-info.html` para guía visual de seguridad
- Revisar `COMPARISON.md` para comparación de características

### Obtener Ayuda
1. Verificar registros de seguridad: `logs/security.log`
2. Revisar archivos de documentación
3. Probar primero en ambiente de desarrollo
4. Verificar registros de errores de PHP
5. Verificar todas las configuraciones

---

**Recuerda:** ¡La seguridad es un proceso continuo, no una configuración única! ¡El monitoreo y las actualizaciones regulares son esenciales!

**Última Actualización:** Verifica la fecha de tu commit de git para la versión más reciente de esta lista.
