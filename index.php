<?php

include('inc/db.php');
include('inc/functions.php');

// Generate CSRF token for the form
$csrf_token = GenerateCSRFToken();

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>World of Warcraft - Registro de Cataclysm</title>
	<meta name="description" content="Únete a nuestro servidor privado de World of Warcraft Cataclysm. ¡Crea tu cuenta y comienza tu aventura épica!">
	<meta name="keywords" content="world of warcraft, cataclysm, servidor privado, wow, registro">
	
	<!-- Favicon -->
	<link rel="icon" type="image/x-icon" href="img/favicon.ico">

	<!-- CSS Stylesheets -->
	<link rel="stylesheet" type="text/css" href="css/foundation.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="css/main.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="css/content.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="css/fonts.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="css/colors.css" media="screen" />
	
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Spectral+SC:wght@700&display=swap" rel="stylesheet">
</head>
<body>

<div class="row">
	<div class="content">
		<div class="content-header">
			<div class="content-logo">
				World<span class="orange">of</span>Warcraft
			</div>
			<div class="content-subtitle">
				<span class="cataclysm-text">C A T A C L Y S M</span>
			</div>
		</div>

		<div class="content-box">
			<div class="content-box-header">
				<h2>Crea tu Cuenta</h2>
			</div>
			<div class="content-box-content">
				<form method="POST" id="registrationForm" autocomplete="off">
					<!-- CSRF Token -->
					<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
					
					<!-- Honeypot field (hidden from users, bots will fill it) -->
					<input type="text" name="website" class="honeypot" tabindex="-1" autocomplete="off">
					
					<label class="orange">Usuario</label>
					<input type="text" name="username" required minlength="3" maxlength="32" pattern="[A-Za-z0-9]+" title="El usuario debe tener entre 3 y 32 caracteres alfanuméricos" autocomplete="username" />

					<label class="orange">Correo Electrónico</label>
					<input type="email" name="email" required maxlength="255" autocomplete="email" />

					<label class="orange">Contraseña</label>
					<input type="password" name="password" required minlength="8" maxlength="72" autocomplete="new-password" />
					<div class="password-requirements">
						<small>La contraseña debe contener: mayúsculas, minúsculas y números (mínimo 8 caracteres)</small>
					</div>

					<label class="orange">Confirmar Contraseña</label>
					<input type="password" name="re-password" required minlength="8" maxlength="72" autocomplete="new-password" />

					<center>
						<div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars(CAPTCHA_CLIENT_ID, ENT_QUOTES, 'UTF-8'); ?>"></div>
						<br>
						<button type="submit" name="register" class="small button">
							<span class="button-text">Únete a la Batalla</span>
						</button>
					</center>
				</form>
			</div>
		</div>

		<div class="response">
			<?php Register(); ?>
		</div>
		
		<div class="content-footer">
			<p>World of Warcraft y todas las marcas relacionadas son © Blizzard Entertainment.</p>
			<p>Este es un servidor privado hecho por fans.</p>
		</div>
	</div>
</div>

<!-- Javascript Files -->
<script type="text/javascript" src="js/vendor/jquery.js"></script>
<script type="text/javascript" src="js/vendor/what-input.js"></script>
<script type="text/javascript" src="js/vendor/foundation.js"></script>
<script type="text/javascript" src="js/app.js"></script>
<script type="text/javascript" src='https://www.google.com/recaptcha/api.js'></script>

<script>
// Client-side validation
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    var password = document.querySelector('input[name="password"]').value;
    var repassword = document.querySelector('input[name="re-password"]').value;
    
    if (password !== repassword) {
        e.preventDefault();
        alert('¡Las contraseñas no coinciden!');
        return false;
    }
    
    // Check password strength
    var hasUpperCase = /[A-Z]/.test(password);
    var hasLowerCase = /[a-z]/.test(password);
    var hasNumber = /[0-9]/.test(password);
    
    if (!hasUpperCase || !hasLowerCase || !hasNumber) {
        e.preventDefault();
        alert('La contraseña debe contener al menos una letra mayúscula, una letra minúscula y un número.');
        return false;
    }
});
</script>

</body>
</html>