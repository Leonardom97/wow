<?php

/**
 * Settings Template
 * Copy this to settings.php and configure with your values
 */

// Database Configuration
$config = array(
    'HOST' => 'localhost',              // Database host
    'USER' => 'root',                   // Database username
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

?>
