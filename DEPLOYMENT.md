# SPINNEYS - Production Deployment Checklist

## 🚀 Pre-Deployment

### Code Quality
- [ ] All models have proper relationships
- [ ] Migrations are tested and reversible
- [ ] Seeders provide realistic data
- [ ] Events and listeners are working
- [ ] Jobs are queued and processed correctly
- [ ] Middleware is properly configured
- [ ] Routes are protected appropriately

### Testing
- [ ] Unit tests pass
- [ ] Feature tests pass
- [ ] Manual testing completed
- [ ] Edge cases handled
- [ ] Error handling implemented

## 🔧 Environment Configuration

### .env Settings
```env
# Application
APP_NAME=SPINNEYS
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=spinneys_prod
DB_USERNAME=your-db-user
DB_PASSWORD=your-secure-password

# Session (12 hours)
SESSION_LIFETIME=720
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true

# Queue
QUEUE_CONNECTION=database

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=587
MAIL_USERNAME=your-mail-username
MAIL_PASSWORD=your-mail-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="SPINNEYS"

# Security
BCRYPT_ROUNDS=12
```

## 🗄️ Database Setup

### Migration
```bash
# Backup existing data (if any)
php artisan backup:database

# Run migrations
php artisan migrate --force

# Seed production data
php artisan db:seed --force --class=InvestmentPackageSeeder
php artisan db:seed --force --class=AchievementSeeder
php artisan db:seed --force --class=AdminUserSeeder
```

### Indexes Check
```bash
# Verify all indexes are created
php artisan db:show

# Check table structures
php artisan schema:dump
```

## 🔒 Security Hardening

### Application
- [ ] Change admin default password
- [ ] Update admin PIN
- [ ] Review all user permissions
- [ ] Enable HTTPS enforcement
- [ ] Configure CORS if needed
- [ ] Set up rate limiting
- [ ] Configure CSP headers

### Server
- [ ] Firewall configured
- [ ] SSL certificate installed
- [ ] Database access restricted
- [ ] SSH key authentication
- [ ] Fail2ban configured
- [ ] Regular security updates

### Code
```bash
# Clear and cache configs
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

## 📦 Asset Optimization

```bash
# Build production assets
npm run build

# Clear old assets
rm -rf public/hot
rm -rf public/build/manifest.json.old

# Verify assets
ls -la public/build/
```

## 🔄 Queue & Scheduler Setup

### Supervisor Configuration
Create: `/etc/supervisor/conf.d/spinneys-queue.conf`

```ini
[program:spinneys-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/spinneys/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/spinneys/storage/logs/queue.log
stopwaitsecs=3600
```

```bash
# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start spinneys-queue:*
```

### Cron Job
```bash
# Edit crontab
crontab -e

# Add this line
* * * * * cd /path/to/spinneys && php artisan schedule:run >> /dev/null 2>&1
```

## 🌐 Web Server Configuration

### Nginx Example
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    root /path/to/spinneys/public;
    index index.php;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Test and reload
sudo nginx -t
sudo systemctl reload nginx
```

## 📊 Monitoring Setup

### Application Logs
```bash
# Set proper permissions
chmod -R 755 storage
chown -R www-data:www-data storage bootstrap/cache

# Monitor logs
tail -f storage/logs/laravel.log
```

### Queue Monitoring
```bash
# Check queue status
php artisan queue:monitor

# Check failed jobs
php artisan queue:failed
```

### Scheduled Tasks
```bash
# List all scheduled tasks
php artisan schedule:list

# Test scheduler
php artisan schedule:run
```

## 🧪 Post-Deployment Testing

### Application
- [ ] Homepage loads correctly
- [ ] Admin login works
- [ ] User registration works
- [ ] Email verification sends
- [ ] PIN authentication works
- [ ] Investment creation works
- [ ] Deposits can be submitted
- [ ] Withdrawals can be requested

### Automated Systems
- [ ] Queue jobs process
- [ ] Daily interest calculates
- [ ] Investment maturity processes
- [ ] Referral bonuses apply
- [ ] Receipts generate
- [ ] Emails send correctly

### Performance
- [ ] Page load times < 2s
- [ ] Database queries optimized
- [ ] Assets load quickly
- [ ] No memory leaks
- [ ] Queue processes promptly

## 📱 Monitoring & Alerts

### Set Up
- [ ] Error tracking (Sentry/Bugsnag)
- [ ] Uptime monitoring
- [ ] Performance monitoring
- [ ] Queue monitoring
- [ ] Database monitoring
- [ ] Disk space alerts
- [ ] SSL expiry alerts

### Health Checks
```bash
# Laravel health check
curl https://yourdomain.com/up

# Custom health endpoint
php artisan make:command HealthCheck
```

## 💾 Backup Strategy

### Database
```bash
# Daily automated backup
0 2 * * * /usr/bin/mysqldump -u user -p'password' database > /backups/db_$(date +\%Y\%m\%d).sql

# Weekly full backup
0 3 * * 0 tar -czf /backups/full_$(date +\%Y\%m\%d).tar.gz /path/to/spinneys
```

### Application Files
- [ ] Code repository (Git)
- [ ] Environment files (.env)
- [ ] Upload directories
- [ ] SSL certificates

## 🔄 Rollback Plan

```bash
# Database rollback
php artisan migrate:rollback --step=1

# Code rollback
git checkout previous-tag
composer install
npm run build
php artisan config:cache
```

## 📋 Launch Checklist

### Pre-Launch (24h before)
- [ ] All tests passing
- [ ] Code reviewed
- [ ] Database backed up
- [ ] SSL certificate valid
- [ ] DNS configured
- [ ] Monitoring active

### Launch
- [ ] Deploy code
- [ ] Run migrations
- [ ] Clear caches
- [ ] Start queue workers
- [ ] Verify cron jobs
- [ ] Test critical paths
- [ ] Monitor error logs

### Post-Launch (1h after)
- [ ] No critical errors
- [ ] Queue processing normally
- [ ] Users can register
- [ ] Investments working
- [ ] Emails sending
- [ ] Performance acceptable

## 🚨 Emergency Contacts

- **System Admin**: [Contact Info]
- **Database Admin**: [Contact Info]
- **Developer**: [Contact Info]
- **Hosting Support**: [Contact Info]

## 📝 Documentation

- [ ] Admin user guide created
- [ ] API documentation (if applicable)
- [ ] Deployment procedures documented
- [ ] Troubleshooting guide written
- [ ] Emergency procedures documented

---

## 🎯 Success Criteria

- ✅ Application accessible via HTTPS
- ✅ No errors in logs
- ✅ Queue workers running
- ✅ Scheduler executing
- ✅ Admin can login
- ✅ Users can register
- ✅ Investments can be created
- ✅ Daily interest processes
- ✅ Emails send successfully
- ✅ Backups running
- ✅ Monitoring active

---

**Deployment Date**: _____________

**Deployed By**: _____________

**Verified By**: _____________

**Status**: ⬜ Successful  ⬜ Issues Found  ⬜ Rolled Back

**Notes**:
_______________________________________________________________
_______________________________________________________________
_______________________________________________________________
