# World of Warcraft Cataclysm - Secure Registration System

A secure and beautifully designed registration page for World of Warcraft Cataclysm private servers with comprehensive protection against common web attacks.

## Security Features

### Protection Against Common Attacks

✅ **SQL Injection Protection**
- PDO prepared statements with parameter binding
- No direct SQL string concatenation
- PDO error mode set to exceptions

✅ **XSS (Cross-Site Scripting) Protection**
- Input sanitization on all user inputs
- Output encoding using htmlspecialchars with ENT_QUOTES
- Content Security Policy headers

✅ **CSRF (Cross-Site Request Forgery) Protection**
- CSRF tokens on all forms
- Token validation on form submission
- Time-limited tokens (1 hour expiration)

✅ **DDoS & Brute Force Protection**
- Rate limiting (5 attempts per 5 minutes per IP)
- Session-based throttling
- IP address tracking

✅ **Bot Protection**
- Google reCAPTCHA v2 integration
- Honeypot field for additional bot detection
- Client-side and server-side validation

✅ **Password Security**
- Minimum 8 characters required
- Must contain uppercase, lowercase, and numbers
- Password strength validation
- SHA1 hashing (compatible with WoW authentication)

✅ **Additional Security Measures**
- Security headers (X-Frame-Options, X-XSS-Protection, etc.)
- Session security (httponly, secure flags)
- Session regeneration to prevent fixation
- Input length validation
- Email validation with disposable email blocking
- Secure error handling (no sensitive data exposure)
- Security event logging

## Design Features

### Cataclysm-Themed UI
- Epic World of Warcraft aesthetic
- Golden glow effects and animations
- Fire-themed Cataclysm branding
- Responsive design for all devices
- Smooth animations and transitions
- Professional game-inspired interface

## Installation

### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- SSL certificate (recommended for production)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd wow
   ```

2. **Configure database settings**
   Edit `inc/settings.php` with your database credentials:
   ```php
   $config = array(
       'HOST' => 'localhost',
       'USER' => 'your_db_user',
       'PASS' => 'your_db_password',
       'DB'   => 'auth',
       'CORE' => '7878'
   );
   ```

3. **Configure Google reCAPTCHA**
   - Get your reCAPTCHA keys from https://www.google.com/recaptcha/admin
   - Add them to `inc/settings.php`:
   ```php
   define('CAPTCHA_SECRET', 'your_secret_key');
   define('CAPTCHA_CLIENT_ID', 'your_site_key');
   ```

4. **Set file permissions**
   ```bash
   chmod 755 inc/
   chmod 644 inc/*.php
   mkdir -p logs
   chmod 755 logs
   ```

5. **Configure your web server**
   - Point document root to the project directory
   - Enable mod_rewrite if using Apache
   - Configure SSL (highly recommended)

6. **Update expansion and realm settings**
   Edit `inc/settings.php`:
   ```php
   define('EXPANSION', 4); // 4 = Cataclysm
   define('REALMLIST', 'your-realm.com');
   define('SUCCESS_MESSAGE', 'Your success message');
   ```

## Security Best Practices

### For Production Deployment

1. **Enable HTTPS**
   - Get an SSL certificate (Let's Encrypt is free)
   - Update session settings in `inc/security.php`:
     ```php
     ini_set('session.cookie_secure', 1);
     ```

2. **Secure settings.php**
   - Keep database credentials secure
   - Set restrictive file permissions: `chmod 600 inc/settings.php`
   - Never commit real credentials to version control

3. **Monitor security logs**
   - Check `logs/security.log` regularly
   - Set up log rotation
   - Monitor for suspicious activity

4. **Regular Updates**
   - Keep PHP and MySQL updated
   - Update dependencies regularly
   - Review security advisories

5. **Additional Hardening**
   - Disable directory listing
   - Hide PHP version in headers
   - Implement fail2ban or similar
   - Use a Web Application Firewall (WAF)
   - Regular security audits

## File Structure

```
wow/
├── css/              # Stylesheets
│   ├── content.css   # Main styling with Cataclysm theme
│   ├── colors.css
│   ├── fonts.css
│   ├── main.css
│   └── ...
├── inc/              # PHP includes
│   ├── db.php        # Database connection
│   ├── functions.php # Registration logic
│   ├── security.php  # Security utilities
│   └── settings.php  # Configuration
├── img/              # Images and assets
├── js/               # JavaScript files
├── logs/             # Security logs (auto-created)
├── index.php         # Main registration page
└── README.md         # This file
```

## Customization

### Changing Colors
Edit `css/content.css` to modify the color scheme:
- Gold/Orange theme: `#FFD700`, `#FF8C00`, `#FF4500`
- Background gradients
- Button styles

### Changing Fonts
The design uses Google Fonts:
- **Cinzel**: For titles and headings
- **Spectral SC**: For the Cataclysm subtitle

Modify in `index.php` `<head>` section.

### Modifying Validation Rules
Edit `inc/security.php`:
- `ValidateUsername()`: Username rules
- `ValidatePasswordStrength()`: Password requirements
- `ValidateEmail()`: Email validation
- `CheckRateLimit()`: Rate limiting parameters

## Troubleshooting

### reCAPTCHA not working
- Verify your site key and secret key are correct
- Check domain is registered with Google reCAPTCHA
- Ensure JavaScript is enabled

### Rate limiting too strict
Adjust parameters in `inc/functions.php`:
```php
CheckRateLimit($ip_address, 5, 300)  // 5 attempts per 300 seconds
```

### Database connection errors
- Verify MySQL is running
- Check credentials in `inc/settings.php`
- Ensure database exists
- Check user permissions

### Logs not being created
- Ensure `logs/` directory exists
- Check write permissions: `chmod 755 logs`

## Security Incident Response

If you detect suspicious activity:
1. Review `logs/security.log`
2. Check for unauthorized access attempts
3. Verify database integrity
4. Update passwords if compromised
5. Consider implementing additional firewall rules

## Credits

- Original design inspiration: World of Warcraft by Blizzard Entertainment
- Security implementations: Industry-standard best practices
- reCAPTCHA: Google

## License

This is a fan-made project. World of Warcraft and all related trademarks are © Blizzard Entertainment.

## Support

For issues and questions:
- Check the security logs
- Review this README
- Ensure all requirements are met

## Screenshots

![Preview](https://puu.sh/xwIms/233d6cc51f.jpg)

---

**Note**: This is a private server registration system. Always comply with Blizzard Entertainment's Terms of Service and local laws.
