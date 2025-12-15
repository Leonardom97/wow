# Quick Setup Guide

## 1. Database Setup
Create your auth database and ensure it has an `account` table with these columns:

### PostgreSQL Schema
```sql
CREATE TABLE account (
  id SERIAL PRIMARY KEY,
  username VARCHAR(32) NOT NULL UNIQUE,
  sha_pass_hash VARCHAR(40) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  last_ip VARCHAR(15) NOT NULL,
  expansion SMALLINT NOT NULL DEFAULT 0
);

CREATE INDEX idx_account_username ON account(username);
CREATE INDEX idx_account_email ON account(email);
```

### MySQL Schema (Legacy)
```sql
CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `sha_pass_hash` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `last_ip` varchar(15) NOT NULL,
  `expansion` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

## 2. Configure Settings
1. Copy `inc/settings.template.php` to `inc/settings.php`
2. Edit `inc/settings.php` with your values:
   - Database credentials (HOST, PORT, USER, PASS, DB)
     - For PostgreSQL: Default port is 5432, default user is 'postgres'
     - For MySQL: Default port is 3306, default user is 'root'
   - Google reCAPTCHA keys (get from https://www.google.com/recaptcha/admin)
   - Realmlist address
   - Success message
   - Expansion version (4 for Cataclysm)

## 3. Create Logs Directory
```bash
mkdir logs
chmod 755 logs
```

## 4. File Permissions
```bash
chmod 755 inc/
chmod 644 inc/*.php
chmod 600 inc/settings.php  # Extra protection for credentials
chmod 755 logs/
```

## 5. Web Server Configuration

### Apache
The `.htaccess` file is already configured. Ensure:
- `mod_rewrite` is enabled
- `AllowOverride All` in your VirtualHost
- `mod_headers` is enabled

### Nginx
Add these to your server block:
```nginx
location ~ /inc/ {
    deny all;
    return 403;
}

location ~ /logs/ {
    deny all;
    return 403;
}

location ~ \.(log)$ {
    deny all;
    return 403;
}

# Security headers
add_header X-Frame-Options "DENY" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header X-Content-Type-Options "nosniff" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
```

## 6. reCAPTCHA Setup
1. Go to https://www.google.com/recaptcha/admin
2. Register a new site
3. Choose reCAPTCHA v2 → "I'm not a robot" Checkbox
4. Add your domain(s)
5. Copy the Site Key and Secret Key to `inc/settings.php`

## 7. SSL/HTTPS (Recommended for Production)
1. Get an SSL certificate (Let's Encrypt is free)
2. Configure your web server for HTTPS
3. Update `inc/security.php` line 10:
   ```php
   ini_set('session.cookie_secure', 1);  // Change 0 to 1
   ```

## 8. Test Your Installation
1. Visit your registration page
2. Try to register with a test account
3. Check `logs/security.log` for any errors
4. Verify the account appears in your database

## 9. Production Checklist
- [ ] HTTPS enabled
- [ ] Real reCAPTCHA keys configured
- [ ] Database credentials secured
- [ ] File permissions set correctly
- [ ] Logs directory writable
- [ ] settings.php not publicly accessible
- [ ] PHP errors disabled in production
- [ ] Regular security log monitoring scheduled

## 10. Troubleshooting

### "Unable to connect to database"
- Check database credentials in `inc/settings.php`
- Verify PostgreSQL (or MySQL) is running
- Ensure user has proper permissions
- Check database exists
- For PostgreSQL, verify port is 5432 (or custom port if configured)
- For MySQL, verify port is 3306

### "Captcha verification failed"
- Verify reCAPTCHA keys are correct
- Check domain is registered with Google
- Ensure site key matches secret key
- Test with different browser

### "Too many attempts"
- Rate limit triggered (5 per 5 minutes)
- Wait 5 minutes or clear session
- Adjust rate limit in `inc/functions.php` if needed

### Logs not being created
- Check `logs/` directory exists
- Verify write permissions: `chmod 755 logs`
- Check PHP error log for issues

## Support
- Read `SECURITY_README.md` for detailed documentation
- View `security-info.html` for visual security guide
- Check security logs in `logs/security.log`

---

**Remember:** Always test in a development environment before deploying to production!
