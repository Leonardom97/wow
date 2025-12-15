#!/usr/bin/env php
<?php
/**
 * PostgreSQL Connection Test Script
 * 
 * This script tests the PostgreSQL database connection
 * Run: php test_pgsql_connection.php
 */

echo "=================================\n";
echo "PostgreSQL Connection Test\n";
echo "=================================\n\n";

// Check if settings.php exists
if (!file_exists(__DIR__ . '/inc/settings.php')) {
    echo "❌ Error: inc/settings.php not found\n";
    echo "   Please copy inc/settings.template.php to inc/settings.php\n";
    echo "   and configure your database settings.\n\n";
    exit(1);
}

require_once(__DIR__ . '/inc/settings.php');

// Check if pgsql extension is loaded
if (!extension_loaded('pdo_pgsql')) {
    echo "❌ Error: PDO PostgreSQL extension not installed\n";
    echo "   Install it with:\n";
    echo "   - Ubuntu/Debian: sudo apt install php-pgsql\n";
    echo "   - CentOS/RHEL: sudo yum install php-pgsql\n\n";
    exit(1);
}

echo "✅ PDO PostgreSQL extension is loaded\n\n";

// Display configuration
echo "Configuration:\n";
echo "  Host: " . $config['HOST'] . "\n";
echo "  Port: " . ($config['PORT'] ?? '5432') . "\n";
echo "  Database: " . $config['DB'] . "\n";
echo "  User: " . $config['USER'] . "\n\n";

// Test connection
echo "Testing connection...\n";
try {
    $con = new PDO(
        'pgsql:host=' . $config['HOST'] . ';dbname=' . $config['DB'] . ';port=' . ($config['PORT'] ?? '5432'),
        $config['USER'],
        $config['PASS'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "✅ Connected successfully to PostgreSQL!\n\n";
    
    // Get PostgreSQL version
    $stmt = $con->query('SELECT version()');
    $version = $stmt->fetchColumn();
    echo "PostgreSQL Version:\n";
    echo "  " . $version . "\n\n";
    
    // Check if account table exists
    echo "Checking for 'account' table...\n";
    $stmt = $con->prepare("SELECT EXISTS (
        SELECT FROM information_schema.tables 
        WHERE table_name = 'account'
    )");
    $stmt->execute();
    $tableExists = $stmt->fetchColumn();
    
    if ($tableExists) {
        echo "✅ Table 'account' exists\n\n";
        
        // Get table structure
        $stmt = $con->prepare("
            SELECT column_name, data_type, is_nullable, column_default
            FROM information_schema.columns
            WHERE table_name = 'account'
            ORDER BY ordinal_position
        ");
        $stmt->execute();
        $columns = $stmt->fetchAll();
        
        echo "Table structure:\n";
        foreach ($columns as $col) {
            $nullable = $col['is_nullable'] === 'YES' ? 'NULL' : 'NOT NULL';
            $default = $col['column_default'] ? " DEFAULT {$col['column_default']}" : '';
            echo "  - {$col['column_name']}: {$col['data_type']} {$nullable}{$default}\n";
        }
        echo "\n";
        
        // Get row count
        $stmt = $con->query("SELECT COUNT(*) FROM account");
        $count = $stmt->fetchColumn();
        echo "Total accounts: {$count}\n\n";
        
    } else {
        echo "⚠️  Warning: Table 'account' does not exist\n";
        echo "   Run the schema from SETUP_GUIDE.md or POSTGRESQL_MIGRATION.md\n\n";
    }
    
    echo "=================================\n";
    echo "✅ All tests passed!\n";
    echo "=================================\n\n";
    
} catch (PDOException $e) {
    echo "❌ Connection failed!\n\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    
    echo "Troubleshooting:\n";
    echo "1. Check PostgreSQL is running: sudo systemctl status postgresql\n";
    echo "2. Verify database credentials in inc/settings.php\n";
    echo "3. Check PostgreSQL authentication in pg_hba.conf\n";
    echo "4. Ensure database exists: psql -U postgres -c '\\l'\n";
    echo "5. See POSTGRESQL_MIGRATION.md for detailed troubleshooting\n\n";
    
    exit(1);
}
?>
