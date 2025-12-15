# World of Warcraft Cataclysm - Sistema de Registro Seguro

Un sistema de registro seguro y bellamente diseñado para servidores privados de World of Warcraft Cataclysm con protección integral contra ataques web comunes.

## Características de Seguridad

### Protección Contra Ataques Comunes

✅ **Protección contra Inyección SQL**
- Declaraciones preparadas PDO con vinculación de parámetros
- Sin concatenación directa de cadenas SQL
- Modo de error PDO establecido a excepciones

✅ **Protección XSS (Cross-Site Scripting)**
- Sanitización de entrada en todas las entradas de usuario
- Codificación de salida usando htmlspecialchars con ENT_QUOTES
- Cabeceras de Content Security Policy

✅ **Protección CSRF (Cross-Site Request Forgery)**
- Tokens CSRF en todos los formularios
- Validación de tokens al enviar formularios
- Tokens con límite de tiempo (expiración de 1 hora)

✅ **Protección contra DDoS y Fuerza Bruta**
- Limitación de tasa (5 intentos por 5 minutos por IP)
- Throttling basado en sesión
- Rastreo de direcciones IP

✅ **Protección contra Bots**
- Integración de Google reCAPTCHA v2
- Campo honeypot para detección adicional de bots
- Validación del lado del cliente y del servidor

✅ **Seguridad de Contraseñas**
- Mínimo 8 caracteres requeridos
- Debe contener mayúsculas, minúsculas y números
- Validación de fortaleza de contraseña
- Hashing SHA1 (compatible con autenticación de WoW)

✅ **Medidas de Seguridad Adicionales**
- Cabeceras de seguridad (X-Frame-Options, X-XSS-Protection, etc.)
- Seguridad de sesión (flags httponly, secure)
- Regeneración de sesión para prevenir fijación
- Validación de longitud de entrada
- Validación de correo electrónico con bloqueo de correos desechables
- Manejo seguro de errores (sin exposición de datos sensibles)
- Registro de eventos de seguridad

## Características de Diseño

### Interfaz Temática de Cataclysm
- Estética épica de World of Warcraft
- Efectos de brillo dorado y animaciones
- Marca de Cataclysm con tema de fuego
- Diseño adaptable para todos los dispositivos
- Animaciones y transiciones suaves
- Interfaz profesional inspirada en el juego

## Instalación

### Requisitos
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Certificado SSL (recomendado para producción)

### Instrucciones de Configuración

1. **Clonar el repositorio**
   ```bash
   git clone <repository-url>
   cd wow
   ```

2. **Configurar ajustes de base de datos**
   Editar `inc/settings.php` con tus credenciales de base de datos:
   ```php
   $config = array(
       'HOST' => 'localhost',
       'USER' => 'tu_usuario_bd',
       'PASS' => 'tu_contraseña_bd',
       'DB'   => 'auth',
       'CORE' => '7878'
   );
   ```

3. **Configurar Google reCAPTCHA**
   - Obtener tus claves de reCAPTCHA de https://www.google.com/recaptcha/admin
   - Agregarlas a `inc/settings.php`:
   ```php
   define('CAPTCHA_SECRET', 'tu_clave_secreta');
   define('CAPTCHA_CLIENT_ID', 'tu_clave_del_sitio');
   ```

4. **Establecer permisos de archivos**
   ```bash
   chmod 755 inc/
   chmod 644 inc/*.php
   mkdir -p logs
   chmod 755 logs
   ```

5. **Configurar tu servidor web**
   - Apuntar la raíz del documento al directorio del proyecto
   - Habilitar mod_rewrite si usas Apache
   - Configurar SSL (altamente recomendado)

6. **Actualizar configuración de expansión y reino**
   Editar `inc/settings.php`:
   ```php
   define('EXPANSION', 4); // 4 = Cataclysm
   define('REALMLIST', 'tu-reino.com');
   define('SUCCESS_MESSAGE', 'Tu mensaje de éxito');
   ```

## Mejores Prácticas de Seguridad

### Para Despliegue en Producción

1. **Habilitar HTTPS**
   - Obtener un certificado SSL (Let's Encrypt es gratuito)
   - Actualizar configuración de sesión en `inc/security.php`:
     ```php
     ini_set('session.cookie_secure', 1);
     ```

2. **Asegurar settings.php**
   - Mantener las credenciales de base de datos seguras
   - Establecer permisos de archivo restrictivos: `chmod 600 inc/settings.php`
   - Nunca hacer commit de credenciales reales al control de versiones

3. **Monitorear registros de seguridad**
   - Verificar `logs/security.log` regularmente
   - Configurar rotación de registros
   - Monitorear actividad sospechosa

4. **Actualizaciones Regulares**
   - Mantener PHP y MySQL actualizados
   - Actualizar dependencias regularmente
   - Revisar avisos de seguridad

5. **Fortalecimiento Adicional**
   - Deshabilitar listado de directorios
   - Ocultar versión de PHP en cabeceras
   - Implementar fail2ban o similar
   - Usar un Web Application Firewall (WAF)
   - Auditorías de seguridad regulares

## Estructura de Archivos

```
wow/
├── css/              # Hojas de estilo
│   ├── content.css   # Estilo principal con tema Cataclysm
│   ├── colors.css
│   ├── fonts.css
│   ├── main.css
│   └── ...
├── inc/              # Includes PHP
│   ├── db.php        # Conexión a base de datos
│   ├── functions.php # Lógica de registro
│   ├── security.php  # Utilidades de seguridad
│   └── settings.php  # Configuración
├── img/              # Imágenes y recursos
├── js/               # Archivos JavaScript
├── logs/             # Registros de seguridad (creado automáticamente)
├── index.php         # Página principal de registro
└── README.md         # Este archivo
```

## Personalización

### Cambiar Colores
Editar `css/content.css` para modificar el esquema de colores:
- Tema Dorado/Naranja: `#FFD700`, `#FF8C00`, `#FF4500`
- Degradados de fondo
- Estilos de botones

### Cambiar Fuentes
El diseño usa Google Fonts:
- **Cinzel**: Para títulos y encabezados
- **Spectral SC**: Para el subtítulo de Cataclysm

Modificar en la sección `<head>` de `index.php`.

### Modificar Reglas de Validación
Editar `inc/security.php`:
- `ValidateUsername()`: Reglas de usuario
- `ValidatePasswordStrength()`: Requisitos de contraseña
- `ValidateEmail()`: Validación de correo electrónico
- `CheckRateLimit()`: Parámetros de limitación de tasa

## Solución de Problemas

### reCAPTCHA no funciona
- Verificar que tu clave del sitio y clave secreta sean correctas
- Verificar que el dominio esté registrado con Google reCAPTCHA
- Asegurar que JavaScript esté habilitado

### Limitación de tasa demasiado estricta
Ajustar parámetros en `inc/functions.php`:
```php
CheckRateLimit($ip_address, 5, 300)  // 5 intentos por 300 segundos
```

### Errores de conexión a base de datos
- Verificar que MySQL esté ejecutándose
- Verificar credenciales en `inc/settings.php`
- Asegurar que la base de datos existe
- Verificar permisos de usuario

### No se están creando registros
- Asegurar que el directorio `logs/` existe
- Verificar permisos de escritura: `chmod 755 logs`

## Respuesta a Incidentes de Seguridad

Si detectas actividad sospechosa:
1. Revisar `logs/security.log`
2. Verificar intentos de acceso no autorizados
3. Verificar integridad de la base de datos
4. Actualizar contraseñas si están comprometidas
5. Considerar implementar reglas de firewall adicionales

## Créditos

- Inspiración del diseño original: World of Warcraft por Blizzard Entertainment
- Implementaciones de seguridad: Mejores prácticas estándar de la industria
- reCAPTCHA: Google

## Licencia

Este es un proyecto hecho por fans. World of Warcraft y todas las marcas relacionadas son © Blizzard Entertainment.

## Soporte

Para problemas y preguntas:
- Verificar los registros de seguridad
- Revisar este README
- Asegurar que se cumplan todos los requisitos

## Capturas de Pantalla

![Vista Previa](https://puu.sh/xwIms/233d6cc51f.jpg)

---

**Nota**: Este es un sistema de registro para servidores privados. Siempre cumple con los Términos de Servicio de Blizzard Entertainment y las leyes locales.
