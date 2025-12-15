<?php

require_once('settings.php');
require_once('security.php');

// Validate port number to prevent DSN injection
$port = $config['PORT'] ?? '5432';
if (!ctype_digit((string)$port) || $port < 1 || $port > 65535) {
    LogSecurityEvent('INVALID_PORT_CONFIG', 'Port must be numeric between 1-65535');
    die('Invalid database configuration. Please contact administrator.');
}

try
{
    $con = new PDO('pgsql:host=' . $config['HOST'] . ';dbname=' . $config['DB'] . ';port=' . $port, 
                   $config['USER'], 
                   $config['PASS'],
                   [
                       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                       PDO::ATTR_EMULATE_PREPARES => false,
                       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                   ]);
}
catch(PDOException $e)
{
    // Don't expose database errors to users
    LogSecurityEvent('DATABASE_CONNECTION_FAILED', $e->getMessage());
    die('Unable to connect to database. Please try again later.');
}

// Initialize secure session
InitSecureSession();

// Set security headers
SetSecurityHeaders();

?>