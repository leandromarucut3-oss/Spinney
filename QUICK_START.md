# SPINNEYS - Quick Start Guide

## 🚀 Get Started in 5 Minutes

### 1. Prerequisites Check
```bash
php -v    # Should be 8.2+
composer -v
node -v   # Should be 18+
npm -v
```

### 2. Installation
```bash
# Already in project directory
composer install
npm install
```

### 3. Environment Setup
```bash
# .env already configured with SQLite
# Just generate the key
php artisan key:generate
```

### 4. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed
```

### 5. Build Assets
```bash
npm run build
```

### 6. Start Application
```bash
# Terminal 1: Application Server
php artisan serve

# Terminal 2: Queue Worker (Required for events/jobs)
php artisan queue:work

# Terminal 3: Task Scheduler (Required for daily interest)
php artisan schedule:work
```

## 🎯 Access the Platform

Open browser: `http://localhost:8000`

### Admin Login
- **Email**: admin@spinneys.com
- **Password**: password
- **PIN**: 123456

### Test User Registration
1. Click "Register"
2. Fill form with your details
3. Use referral code: `ADMIN001` (optional)
4. Set 6-digit PIN
5. Verify email (check logs in development)

## 📊 Verify Installation

### Check Database
```bash
php artisan tinker

# Check seeded data
>>> \App\Models\InvestmentPackage::count()
=> 4

>>> \App\Models\Achievement::count()
=> 5

>>> \App\Models\User::where('is_admin', true)->first()->email
=> "admin@spinneys.com"
```

### Test Queue System
```bash
# Dispatch a test job
php artisan tinker

>>> \App\Jobs\ProcessDailyInterest::dispatch()
```

Check queue worker terminal for processing confirmation.

### Test Scheduler
```bash
# List scheduled tasks
php artisan schedule:list

# Run scheduler once
php artisan schedule:run
```

## 🔑 Key Features to Test

### 1. Investment Creation
- Login as admin
- Navigate to Investments
- Create new investment
- Verify balance deduction
- Check receipt generation

### 2. Referral System
- Register new user with referral code
- Check referrer's balance increased
- Verify referral record created

### 3. Daily Interest (Manual Trigger)
```bash
php artisan tinker
>>> \App\Jobs\ProcessDailyInterest::dispatchSync()
```

Check interest logs and balance updates.

## 🐛 Common Issues

### "Queue connection not working"
```bash
# Make sure queue worker is running
php artisan queue:work
```

### "Assets not loading"
```bash
npm run build
php artisan view:clear
```

### "Database locked"
```bash
# Close all database connections
# Restart application
```

## 📁 Important Files

- **Models**: `app/Models/`
- **Migrations**: `database/migrations/`
- **Seeders**: `database/seeders/`
- **Jobs**: `app/Jobs/`
- **Events**: `app/Events/`
- **Middleware**: `app/Http/Middleware/`
- **Routes**: `routes/web.php`, `routes/console.php`

## 🎨 Customization

### Update Branding Colors
Edit: `tailwind.config.js`

### Modify Investment Packages
Edit: `database/seeders/InvestmentPackageSeeder.php`
Run: `php artisan db:seed --class=InvestmentPackageSeeder`

### Add New Achievements
Edit: `database/seeders/AchievementSeeder.php`

### Change Interest Rates
Edit investment package records in database or create new migration.

## 📱 Next Steps

1. Customize Blade templates in `resources/views/`
2. Add controllers for investment operations
3. Create admin dashboard views
4. Implement user dashboard
5. Add withdrawal/deposit forms
6. Build referral tracking UI

## 🔐 Security Notes

- Change admin password immediately in production
- Update `.env` with production database credentials
- Set `APP_DEBUG=false` in production
- Configure proper mail server
- Set up SSL certificate
- Enable rate limiting on sensitive routes

## 📞 Need Help?

1. Check `storage/logs/laravel.log` for errors
2. Review README.md for detailed documentation
3. Verify all 3 terminals are running (server, queue, scheduler)

---

**Happy Coding! 🚀**
