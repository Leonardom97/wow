<?php

require_once('security.php');

function Register()
{
	if(isset($_POST['register']))
	{
		global $con;
		
		// Get client IP for rate limiting
		$ip_address = GetClientIP();
		
		// Check rate limiting (5 attempts per 5 minutes)
		if (!CheckRateLimit($ip_address, 5, 300)) {
			LogSecurityEvent('RATE_LIMIT_EXCEEDED', 'Registration attempt blocked');
			echo '<div class="callout alert">Too many attempts. Please try again later.</div>';
			return;
		}
		
		// Validate CSRF token
		if (!isset($_POST['csrf_token']) || !ValidateCSRFToken($_POST['csrf_token'])) {
			LogSecurityEvent('CSRF_VALIDATION_FAILED', 'Invalid or missing CSRF token');
			echo '<div class="callout alert">Security validation failed. Please refresh the page and try again.</div>';
			return;
		}
		
		// Check honeypot field (bot detection)
		if (!CheckHoneypot($_POST['website'] ?? '')) {
			LogSecurityEvent('HONEYPOT_TRIGGERED', 'Bot detected via honeypot field');
			echo '<div class="callout alert">Registration failed. Please try again.</div>';
			return;
		}
		
		// Check if all required fields are present and not empty
		if(empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['re-password']))
		{
			echo '<div class="callout alert">All fields are required!</div>';
			return;
		}
		
		// Sanitize inputs
		$username   = trim($_POST['username']);
		$email      = trim($_POST['email']);
		$password   = $_POST['password'];
		$repassword = $_POST['re-password'];
		$captcha    = $_POST['g-recaptcha-response'] ?? '';
		$secret     = CAPTCHA_SECRET;
		$expansion  = EXPANSION;
		
		// Validate username
		if(!ValidateUsername($username))
		{
			echo '<div class="callout alert">Username must be 3-32 alphanumeric characters!</div>';
			return;
		}
		
		// Validate email
		if(!ValidateEmail($email))
		{
			echo '<div class="callout alert">Please provide a valid email address!</div>';
			return;
		}
		
		// Validate password strength
		$passwordValidation = ValidatePasswordStrength($password);
		if(!$passwordValidation['valid'])
		{
			echo '<div class="callout alert">' . SanitizeInput($passwordValidation['message']) . '</div>';
			return;
		}
		
		// Check if passwords match
		if($password !== $repassword)
		{
			echo '<div class="callout alert">Passwords do not match!</div>';
			return;
		}
		
		// Verify reCAPTCHA
		if(!VerifyCaptcha($secret, $captcha, $ip_address))
		{
			LogSecurityEvent('CAPTCHA_FAILED', 'User: ' . $username);
			echo '<div class="callout alert">Captcha verification failed. Please try again.</div>';
			return;
		}
		
		// Check if username or email already exists
		try {
			$stmt = $con->prepare('SELECT COUNT(*) FROM account WHERE username = :username OR email = :email');
			$stmt->execute([
				':username' => $username,
				':email'    => $email
			]);
			
			if($stmt->fetchColumn() > 0)
			{
				echo '<div class="callout alert">Username or Email is already in use!</div>';
				return;
			}
			
			// Insert new account
			$stmt = $con->prepare('INSERT INTO account (username, sha_pass_hash, email, last_ip, expansion) 
				VALUES(:username, :password, :email, :ip, :expansion)');
			$stmt->execute([
				':username'  => $username,
				':password'  => sha1(strtoupper($username) . ':' . strtoupper($password)),
				':email'     => $email,
				':ip'        => $ip_address,
				':expansion' => $expansion
			]);
			
			LogSecurityEvent('REGISTRATION_SUCCESS', 'User: ' . $username);
			echo '<div class="callout success">' . SanitizeInput(SUCCESS_MESSAGE) . '</div>';
			echo '<div class="callout warning">Realmlist: ' . SanitizeInput(REALMLIST) . '</div>';
			
		} catch (PDOException $e) {
			LogSecurityEvent('DATABASE_ERROR', 'Registration failed for user: ' . $username);
			echo '<div class="callout alert">Registration failed. Please try again later.</div>';
		}
	}
}

?>
