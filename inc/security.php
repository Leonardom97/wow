<?php

/**
 * Security Utilities
 * Comprehensive security functions to protect against various attacks
 */

// Start session securely
function InitSecureSession()
{
    if (session_status() == PHP_SESSION_NONE) {
        // Set secure session parameters
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
        ini_set('session.cookie_samesite', 'Strict');
        session_name('WOW_SESSION');
        session_start();
        
        // Regenerate session ID periodically to prevent session fixation
        if (!isset($_SESSION['created'])) {
            $_SESSION['created'] = time();
        } else if (time() - $_SESSION['created'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['created'] = time();
        }
    }
}

// Generate CSRF token
function GenerateCSRFToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION['csrf_token'];
}

// Validate CSRF token
function ValidateCSRFToken($token)
{
    if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
        return false;
    }
    
    // Token expires after 1 hour
    if (time() - $_SESSION['csrf_token_time'] > 3600) {
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Rate limiting function
function CheckRateLimit($identifier, $max_attempts = 5, $time_window = 300)
{
    InitSecureSession();
    
    $key = 'rate_limit_' . $identifier;
    $current_time = time();
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['attempts' => 1, 'first_attempt' => $current_time];
        return true;
    }
    
    $data = $_SESSION[$key];
    
    // Reset if time window has passed
    if ($current_time - $data['first_attempt'] > $time_window) {
        $_SESSION[$key] = ['attempts' => 1, 'first_attempt' => $current_time];
        return true;
    }
    
    // Check if limit exceeded
    if ($data['attempts'] >= $max_attempts) {
        return false;
    }
    
    // Increment attempts
    $_SESSION[$key]['attempts']++;
    return true;
}

// Sanitize input to prevent XSS
function SanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// Validate and sanitize username
function ValidateUsername($username)
{
    $username = trim($username);
    
    // Check length
    if (strlen($username) < 3 || strlen($username) > 32) {
        return false;
    }
    
    // Allow only alphanumeric characters
    if (!ctype_alnum($username)) {
        return false;
    }
    
    return true;
}

// Validate email
function ValidateEmail($email)
{
    $email = trim($email);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    if (strlen($email) > 255) {
        return false;
    }
    
    // Additional check for disposable email domains (optional)
    $domain = substr(strrchr($email, "@"), 1);
    $disposable_domains = ['tempmail.com', 'throwaway.email', '10minutemail.com'];
    
    if (in_array($domain, $disposable_domains)) {
        return false;
    }
    
    return true;
}

// Validate password strength
function ValidatePasswordStrength($password)
{
    // Minimum 8 characters
    if (strlen($password) < 8) {
        return ['valid' => false, 'message' => 'Password must be at least 8 characters long'];
    }
    
    // Maximum 72 characters (bcrypt limitation)
    if (strlen($password) > 72) {
        return ['valid' => false, 'message' => 'Password must be less than 72 characters'];
    }
    
    // Require at least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        return ['valid' => false, 'message' => 'Password must contain at least one uppercase letter'];
    }
    
    // Require at least one lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        return ['valid' => false, 'message' => 'Password must contain at least one lowercase letter'];
    }
    
    // Require at least one number
    if (!preg_match('/[0-9]/', $password)) {
        return ['valid' => false, 'message' => 'Password must contain at least one number'];
    }
    
    return ['valid' => true, 'message' => 'Password is strong'];
}

// Verify reCAPTCHA
function VerifyCaptcha($secret, $captcha, $ip)
{
    if (empty($captcha)) {
        return false;
    }
    
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secret,
        'response' => $captcha,
        'remoteip' => $ip
    ];
    
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
            'timeout' => 10
        ]
    ];
    
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        return false;
    }
    
    $result = json_decode($response, true);
    return isset($result['success']) && $result['success'] === true;
}

// Get client IP address (considering proxies)
function GetClientIP()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Validate IP
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        return '0.0.0.0';
    }
    
    return $ip;
}

// Set security headers
function SetSecurityHeaders()
{
    // Prevent clickjacking
    header('X-Frame-Options: DENY');
    
    // XSS Protection
    header('X-XSS-Protection: 1; mode=block');
    
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Referrer Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Content Security Policy
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://www.google.com https://www.gstatic.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; frame-src https://www.google.com; connect-src 'self'");
    
    // Feature Policy
    header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
}

// Log security events (for monitoring)
function LogSecurityEvent($event_type, $details)
{
    $log_file = __DIR__ . '/../logs/security.log';
    $log_dir = dirname($log_file);
    
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = GetClientIP();
    $log_entry = sprintf("[%s] IP: %s | Type: %s | Details: %s\n", 
        $timestamp, $ip, $event_type, $details);
    
    @file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

// Check for honeypot (bot detection)
function CheckHoneypot($honeypot_value)
{
    // Honeypot field should be empty (hidden from users, but bots fill it)
    return empty($honeypot_value);
}

?>
