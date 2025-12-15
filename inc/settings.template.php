<?php

/**
 * Settings Template
 * Copy this to settings.php and configure with your values
 */

// Database Configuration (PostgreSQL)
$config = array(
    'HOST' => 'localhost',              // Database host
    'PORT' => '5432',                   // Database port (default: 5432 for PostgreSQL)
    'USER' => 'postgres',               // Database username
    'PASS' => '',                       // Database password
    'DB'   => 'auth',                   // Database name (typically 'auth' for TrinityCore)
    'CORE' => '7878'                    // Core version identifier
);


// General Settings
define('EXPANSION', 4);                 // 1 = Vanilla / 2 = TBC / 3 = WOTLK / 4 = Cataclysm
define('REALMLIST', 'your-realm.com');  // Your realmlist address

// Google reCAPTCHA Settings
// Get your keys from: https://www.google.com/recaptcha/admin
define('CAPTCHA_SECRET', '');           // Your reCAPTCHA secret key
define('CAPTCHA_CLIENT_ID', '');        // Your reCAPTCHA site key

// Message Settings
define('SUCCESS_MESSAGE', 'Account created successfully! Download the client and start playing!');

// Optional: Define custom disposable email domains to block (comma-separated)
// If not defined, a default list will be used
// define('DISPOSABLE_EMAIL_DOMAINS', ['tempmail.com', 'throwaway.email', '10minutemail.com', 'guerrillamail.com']);

?>
