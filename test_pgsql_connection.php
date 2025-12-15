#!/usr/bin/env php
<?php
/**
 * Script de Prueba de Conexión PostgreSQL
 * 
 * Este script prueba la conexión a la base de datos PostgreSQL
 * Ejecutar: php test_pgsql_connection.php
 */

echo "=================================\n";
echo "Prueba de Conexión PostgreSQL\n";
echo "=================================\n\n";

// Verificar si existe settings.php
if (!file_exists(__DIR__ . '/inc/settings.php')) {
    echo "❌ Error: inc/settings.php no encontrado\n";
    echo "   Por favor copia inc/settings.template.php a inc/settings.php\n";
    echo "   y configura tus ajustes de base de datos.\n\n";
    exit(1);
}

require_once(__DIR__ . '/inc/settings.php');

// Validar número de puerto para prevenir inyección DSN
$port = $config['PORT'] ?? '5432';
if (!ctype_digit((string)$port) || $port < 1 || $port > 65535) {
    echo "❌ Error: Configuración de puerto inválida\n";
    echo "   El puerto debe ser numérico entre 1-65535\n";
    echo "   Valor actual: " . var_export($port, true) . "\n\n";
    exit(1);
}

// Verificar si la extensión pgsql está cargada
if (!extension_loaded('pdo_pgsql')) {
    echo "❌ Error: Extensión PDO PostgreSQL no instalada\n";
    echo "   Instálala con:\n";
    echo "   - Ubuntu/Debian: sudo apt install php-pgsql\n";
    echo "   - CentOS/RHEL: sudo yum install php-pgsql\n\n";
    exit(1);
}

echo "✅ Extensión PDO PostgreSQL está cargada\n\n";

// Mostrar configuración
echo "Configuración:\n";
echo "  Host: " . $config['HOST'] . "\n";
echo "  Puerto: " . $port . "\n";
echo "  Base de datos: " . $config['DB'] . "\n";
echo "  Usuario: " . $config['USER'] . "\n\n";

// Probar conexión
echo "Probando conexión...\n";
try {
    $con = new PDO(
        'pgsql:host=' . $config['HOST'] . ';dbname=' . $config['DB'] . ';port=' . $port,
        $config['USER'],
        $config['PASS'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "✅ ¡Conectado exitosamente a PostgreSQL!\n\n";
    
    // Obtener versión de PostgreSQL
    $stmt = $con->query('SELECT version()');
    $version = $stmt->fetchColumn();
    echo "Versión de PostgreSQL:\n";
    echo "  " . $version . "\n\n";
    
    // Verificar si existe la tabla account
    echo "Verificando tabla 'account'...\n";
    $stmt = $con->prepare("SELECT EXISTS (
        SELECT FROM information_schema.tables 
        WHERE table_name = 'account'
    )");
    $stmt->execute();
    $tableExists = $stmt->fetchColumn();
    
    if ($tableExists) {
        echo "✅ La tabla 'account' existe\n\n";
        
        // Obtener estructura de tabla
        $stmt = $con->prepare("
            SELECT column_name, data_type, is_nullable, column_default
            FROM information_schema.columns
            WHERE table_name = 'account'
            ORDER BY ordinal_position
        ");
        $stmt->execute();
        $columns = $stmt->fetchAll();
        
        echo "Estructura de tabla:\n";
        foreach ($columns as $col) {
            $nullable = $col['is_nullable'] === 'YES' ? 'NULL' : 'NOT NULL';
            $default = $col['column_default'] ? " DEFAULT {$col['column_default']}" : '';
            echo "  - {$col['column_name']}: {$col['data_type']} {$nullable}{$default}\n";
        }
        echo "\n";
        
        // Obtener conteo de filas
        $stmt = $con->query("SELECT COUNT(*) FROM account");
        $count = $stmt->fetchColumn();
        echo "Total de cuentas: {$count}\n\n";
        
    } else {
        echo "⚠️  Advertencia: La tabla 'account' no existe\n";
        echo "   Ejecuta el esquema de SETUP_GUIDE.md o POSTGRESQL_MIGRATION.md\n\n";
    }
    
    echo "=================================\n";
    echo "✅ ¡Todas las pruebas pasaron!\n";
    echo "=================================\n\n";
    
} catch (PDOException $e) {
    echo "❌ ¡Falló la conexión!\n\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    
    echo "Solución de problemas:\n";
    echo "1. Verificar que PostgreSQL esté ejecutándose: sudo systemctl status postgresql\n";
    echo "2. Verificar credenciales de base de datos en inc/settings.php\n";
    echo "3. Verificar autenticación PostgreSQL en pg_hba.conf\n";
    echo "4. Asegurar que la base de datos existe: psql -U postgres -c '\\l'\n";
    echo "5. Ver POSTGRESQL_MIGRATION.md para solución de problemas detallada\n\n";
    
    exit(1);
}
?>
