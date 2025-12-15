<?php

require_once('settings.php');
require_once('security.php');

try
{
    $con = new PDO('mysql:host=' . $config['HOST'] . ';dbname=' . $config['DB'] . ';charset=UTF8', 
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