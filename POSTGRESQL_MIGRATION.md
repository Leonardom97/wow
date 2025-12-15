# Guía de Migración a PostgreSQL

Esta guía explica cómo usar PostgreSQL en lugar de MySQL con tu sistema de registro de WoW.

## Qué Cambió

El sistema ahora soporta **PostgreSQL** como base de datos principal, manteniendo compatibilidad con MySQL.

### Archivos Modificados
- `inc/db.php` - Database connection now uses PostgreSQL PDO driver
- `inc/settings.template.php` - Updated with PostgreSQL default configuration
- `SETUP_GUIDE.md` - Added PostgreSQL schema and instructions
- `README.md` - Updated requirements to reflect PostgreSQL support

## PostgreSQL vs MySQL

| Feature | PostgreSQL | MySQL |
|---------|-----------|-------|
| Connection String | `pgsql:host=...;dbname=...;port=5432` | `mysql:host=...;dbname=...` |
| Default Port | 5432 | 3306 |
| Default User | postgres | root |
| Auto Increment | SERIAL | AUTO_INCREMENT |
| Data Types | VARCHAR, SMALLINT | varchar, tinyint |

## Pasos de Instalación

### 1. Instalar PostgreSQL

#### Ubuntu/Debian
```bash
sudo apt update
sudo apt install postgresql postgresql-contrib
sudo systemctl start postgresql
sudo systemctl enable postgresql
```

#### CentOS/RHEL
```bash
sudo yum install postgresql-server postgresql-contrib
sudo postgresql-setup initdb
sudo systemctl start postgresql
sudo systemctl enable postgresql
```

#### Windows
Download and install from: https://www.postgresql.org/download/windows/

### 2. Crear Base de Datos y Usuario

```bash
# Cambiar al usuario postgres
sudo -u postgres psql

# En la consola PostgreSQL:
CREATE DATABASE auth;
CREATE USER wowuser WITH PASSWORD 'your_password';
GRANT ALL PRIVILEGES ON DATABASE auth TO wowuser;
\q
```

### 3. Crear Tabla Account

Conectar a tu base de datos:
```bash
psql -U wowuser -d auth
```

Ejecutar el esquema:
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

### 4. Configurar Ajustes

Editar `inc/settings.php`:
```php
$config = array(
    'HOST' => 'localhost',
    'PORT' => '5432',              // PostgreSQL default port
    'USER' => 'wowuser',           // Your PostgreSQL user
    'PASS' => 'your_password',     // Your PostgreSQL password
    'DB'   => 'auth',
    'CORE' => '7878'
);
```

### 5. Instalar Extensión PHP PostgreSQL

#### Ubuntu/Debian
```bash
sudo apt install php-pgsql
sudo systemctl restart apache2  # or nginx
```

#### CentOS/RHEL
```bash
sudo yum install php-pgsql
sudo systemctl restart httpd
```

#### Check if installed
```bash
php -m | grep pgsql
```

### 6. Probar Conexión

Create a test file `test_connection.php`:
```php
<?php
require_once('inc/settings.php');

// Validate port number to prevent DSN injection
$port = $config['PORT'] ?? '5432';
if (!ctype_digit((string)$port) || $port < 1 || $port > 65535) {
    die("❌ Invalid port configuration\n");
}

try {
    $con = new PDO(
        'pgsql:host=' . $config['HOST'] . ';dbname=' . $config['DB'] . ';port=' . $port,
        $config['USER'],
        $config['PASS']
    );
    echo "✅ Connected successfully to PostgreSQL!\n";
    echo "Database: " . $config['DB'] . "\n";
} catch(PDOException $e) {
    echo "❌ Falló la conexión: " . $e->getMessage() . "\n";
}
?>
```

Run: `php test_connection.php`

## Migrating from MySQL to PostgreSQL

If you're migrating existing data from MySQL:

### 1. Export MySQL Data

```bash
mysqldump -u root -p auth account > account_backup.sql
```

### 2. Convert SQL Syntax

MySQL to PostgreSQL conversions needed:
- `AUTO_INCREMENT` → `SERIAL`
- `tinyint` → `SMALLINT`
- Backticks `` ` `` → Remove or use quotes `"`
- `ENGINE=InnoDB` → Remove

### 3. Import to PostgreSQL

Manually recreate the table structure with PostgreSQL syntax, then import data:

```bash
# Export data only (no schema)
mysqldump -u root -p auth account --no-create-info --complete-insert > data_only.sql

# Editar data_only.sql to remove MySQL-specific syntax if needed

# Import to PostgreSQL
psql -U wowuser -d auth < data_only.sql
```

## Solución de Problemas

### Connection Failed
- **Error**: `could not connect to server`
- **Solution**: Check PostgreSQL is running: `sudo systemctl status postgresql`

### Authentication Failed
- **Error**: `FATAL: password authentication failed`
- **Solution**: Check `pg_hba.conf` and ensure user has correct permissions
  ```bash
  sudo nano /etc/postgresql/*/main/pg_hba.conf
  # Change 'peer' to 'md5' for local connections
  sudo systemctl restart postgresql
  ```

### PHP Extension Missing
- **Error**: `could not find driver`
- **Solution**: Install php-pgsql extension (see step 5 above)

### Port Already in Use
- **Error**: `port 5432 is already in use`
- **Solution**: Check if PostgreSQL is already running or change port in config

## Performance Tips

1. **Create Indexes**: Already included in schema for username and email
2. **Connection Pooling**: Consider using PgBouncer for high-traffic sites
3. **Vacuum**: Regularly run `VACUUM ANALYZE` to optimize performance
4. **Tune Settings**: Editar `postgresql.conf` for production optimization

## Reverting to MySQL

If you need to revert to MySQL:

1. Editar `inc/db.php` line 8:
   ```php
   $con = new PDO('mysql:host=' . $config['HOST'] . ';dbname=' . $config['DB'] . ';charset=UTF8',
   ```

2. Editar `inc/settings.php`:
   ```php
   $config = array(
       'HOST' => 'localhost',
       'USER' => 'root',
       'PASS' => '',
       'DB'   => 'auth',
       'CORE' => '7878'
   );
   ```

## Additional Resources

- [PostgreSQL Documentation](https://www.postgresql.org/docs/)
- [PHP PDO PostgreSQL](https://www.php.net/manual/en/ref.pdo-pgsql.php)
- [PostgreSQL Performance](https://wiki.postgresql.org/wiki/Performance_Optimization)
- [pg_hba.conf Configuration](https://www.postgresql.org/docs/current/auth-pg-hba-conf.html)

## Support

- Check connection with `test_connection.php` script
- Review PostgreSQL logs: `/var/log/postgresql/`
- Test credentials: `psql -U wowuser -d auth`
- Verify PHP extension: `php -m | grep pgsql`

---

**Note**: This migration maintains full compatibility with TrinityCore authentication system using SHA1 password hashing.
