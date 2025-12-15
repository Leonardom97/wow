# Security Checklist for Administrators

Use this checklist to ensure your WoW registration system is properly secured.

## Pre-Deployment Checklist

### Configuration
- [ ] `inc/settings.php` configured with correct database credentials
- [ ] Google reCAPTCHA keys added (both site key and secret key)
- [ ] Realmlist configured correctly
- [ ] Expansion version set correctly (4 for Cataclysm)
- [ ] Success message customized for your server

### Security Setup
- [ ] `logs/` directory created with write permissions (chmod 755)
- [ ] `inc/settings.php` protected (chmod 600)
- [ ] `.htaccess` file in place (Apache) or equivalent Nginx rules
- [ ] HTTPS/SSL certificate installed
- [ ] Session security enabled (`session.cookie_secure = 1` when using HTTPS)

### Testing
- [ ] Test registration with valid data (should succeed)
- [ ] Test registration with invalid username (should fail)
- [ ] Test registration with invalid email (should fail)
- [ ] Test registration with weak password (should fail)
- [ ] Test registration without reCAPTCHA (should fail)
- [ ] Test rate limiting (try 6 registrations quickly, 6th should fail)
- [ ] Check `logs/security.log` for proper logging
- [ ] Verify account created in database
- [ ] Test on mobile device (responsive design)

### Web Server
- [ ] PHP 7.4+ installed
- [ ] MySQL 5.7+ installed
- [ ] Required PHP extensions enabled (PDO, PDO_MySQL, session, json)
- [ ] `display_errors = Off` in production
- [ ] `log_errors = On` in production
- [ ] Server signature hidden
- [ ] Directory listing disabled

## Post-Deployment Checklist

### Monitoring
- [ ] Security logs reviewed daily (`logs/security.log`)
- [ ] Database monitored for suspicious accounts
- [ ] Server resources monitored (CPU, memory, connections)
- [ ] Rate limiting effectiveness reviewed
- [ ] Failed registration attempts analyzed

### Maintenance
- [ ] Log rotation configured (logs can grow large)
- [ ] Regular backups of database
- [ ] PHP and MySQL updates scheduled
- [ ] Security advisory subscriptions active
- [ ] Disposable email domains list updated periodically

### Security Hardening
- [ ] Firewall configured (allow only necessary ports)
- [ ] fail2ban or similar installed (optional but recommended)
- [ ] Database user has minimum required privileges only
- [ ] Server time zone configured correctly
- [ ] Regular security audits scheduled

## Monthly Security Review

### Check These Items Monthly:
- [ ] Review `logs/security.log` for unusual patterns
- [ ] Update disposable email domains list if configured
- [ ] Check for PHP and MySQL security updates
- [ ] Review failed registration attempts
- [ ] Verify HTTPS certificate is not expiring
- [ ] Test registration system end-to-end
- [ ] Review rate limiting settings (adjust if needed)
- [ ] Check database for any suspicious accounts
- [ ] Verify backup system is working

## Incident Response

### If You Detect a Security Issue:

1. **Immediate Actions:**
   - [ ] Review `logs/security.log` for details
   - [ ] Block suspicious IP addresses at firewall level
   - [ ] Check database for unauthorized accounts
   - [ ] Verify no data has been exfiltrated

2. **Investigation:**
   - [ ] Identify the attack vector
   - [ ] Determine scope of compromise
   - [ ] Check for any modified files
   - [ ] Review web server access logs

3. **Remediation:**
   - [ ] Patch the vulnerability
   - [ ] Change database credentials
   - [ ] Update reCAPTCHA keys
   - [ ] Remove any malicious accounts
   - [ ] Notify affected users if needed

4. **Prevention:**
   - [ ] Update security measures
   - [ ] Add additional logging
   - [ ] Implement additional firewall rules
   - [ ] Schedule more frequent security reviews

## Performance Optimization

### If Registration is Slow:
- [ ] Check database indexes (username and email should be indexed)
- [ ] Verify adequate server resources
- [ ] Consider CDN for static assets
- [ ] Enable opcode caching (OPcache)
- [ ] Optimize database queries
- [ ] Check network latency to reCAPTCHA servers

## Common Issues & Solutions

### Issue: "Too many attempts" error
- **Solution:** User needs to wait 5 minutes, or adjust rate limit in `inc/functions.php`

### Issue: reCAPTCHA not appearing
- **Solution:** Check reCAPTCHA site key, verify domain is registered, ensure JavaScript is enabled

### Issue: Database connection failed
- **Solution:** Verify credentials, check MySQL is running, ensure user has permissions

### Issue: Logs not being created
- **Solution:** Create `logs/` directory, set write permissions (chmod 755)

### Issue: Rate limiting too strict
- **Solution:** Adjust parameters in `inc/functions.php` line 12: `CheckRateLimit($ip_address, 5, 300)`

## Recommended Tools

### Monitoring
- **Fail2ban:** Automatic IP banning for suspicious activity
- **Monit:** Server monitoring and alerting
- **Logwatch:** Daily email summaries of log files
- **New Relic/DataDog:** Application performance monitoring

### Security
- **ModSecurity:** Web Application Firewall (WAF)
- **Cloudflare:** DDoS protection and CDN
- **Let's Encrypt:** Free SSL certificates
- **Qualys SSL Labs:** Test SSL configuration

### Backup
- **mysqldump:** Database backups
- **rsync:** File backups
- **BorgBackup:** Encrypted, deduplicated backups
- **AWS S3/Backblaze B2:** Off-site backup storage

## Contact & Support

### Resources
- Read `SECURITY_README.md` for detailed security documentation
- View `SETUP_GUIDE.md` for setup instructions
- Check `security-info.html` for visual security guide
- Review `COMPARISON.md` for feature comparison

### Getting Help
1. Check security logs: `logs/security.log`
2. Review documentation files
3. Test in development environment first
4. Check PHP error logs
5. Verify all configuration settings

---

**Remember:** Security is an ongoing process, not a one-time setup. Regular monitoring and updates are essential!

**Last Updated:** Check your git commit date for the latest version of this checklist.
