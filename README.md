# SPINNEYS - Production-Ready Financial Investment Platform

![SPINNEYS](https://via.placeholder.com/800x150/0B4C2D/D4AF37?text=SPINNEYS+FINANCIAL+PLATFORM)

A secure, scalable, enterprise-grade financial investment platform built with Laravel 12, featuring automated daily interest calculations, multi-level referral systems, and comprehensive admin management.

## 🎯 Features

### Core Investment System
- **Investment Packages** - Multiple tier-based packages with configurable returns
- **Atomic Slot Management** - Race-condition-free slot allocation using database-level atomicity
- **Automated Daily Interest** - Scheduled jobs for daily interest calculation and distribution
- **Investment Receipts** - Branded, auditable investment receipts with unique tracking numbers
- **Maturity Processing** - Automatic investment completion and balance settlement

### Financial Operations
- **Deposits** - Admin-approved deposit system with proof of payment
- **Withdrawals** - Multi-stage withdrawal approval workflow
- **User-to-User Transfers** - Direct fund transfers between users
- **Fund Requests** - Request funds from other platform users
- **Full Transaction Audit Trail** - Complete history with balance tracking

### Referral & Rewards
- **Multi-Level Referrals** - Up to 3 levels of referral tracking
- **Instant Signup Bonuses** - Automated bonus distribution on registration
- **Investment Commissions** - Level-based commission structure (5% / 2% / 1%)
- **Unique Referral Codes** - Auto-generated per-user referral codes

### Gamification
- **Daily Attendance** - Check-in system with streak bonuses
- **Achievements System** - Multiple achievement types with rewards
- **Monthly Raffles** - Automated lucky draw system
- **Leaderboards** - User rankings and statistics

### Security
- **Dual Authentication** - Email + 6-digit PIN verification
- **Email Verification** - 24-hour token expiry
- **Session Management** - 12-hour secure sessions
- **User Suspension** - Admin-controlled account suspension
- **Rate Limiting** - Built-in Laravel rate limiting
- **CSRF Protection** - Auto-refresh CSRF tokens
- **bcrypt PIN Hashing** - Secure PIN storage

### Administration
- **User Management** - Verify, suspend, upgrade user tiers
- **Deposit/Withdrawal Approval** - Multi-stage approval workflow
- **Investment Management** - Create, edit, manage packages
- **Fund Transfers** - Admin-initiated transfers
- **Analytics Dashboard** - Comprehensive platform statistics
- **Audit Logs** - Complete activity tracking

## 🛠 Tech Stack

- **Backend**: Laravel 12.0 (PHP 8.2+)
- **Database**: MySQL/SQLite with Eloquent ORM
- **Frontend**: Blade Templates, Tailwind CSS 3, Alpine.js 3
- **Build**: Vite 7
- **Authentication**: Laravel Breeze + Custom PIN Auth
- **Queue**: Laravel Queue (Database driver)
- **Scheduler**: Laravel Task Scheduling
- **Testing**: PHPUnit
- **Deployment**: Docker (Laravel Sail ready)

## 📋 Requirements

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ & NPM
- MySQL 8.0+ or SQLite
- Docker & Docker Compose (optional, for Sail)

## 🚀 Quick Start

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Start application
php artisan serve

# Start queue worker (separate terminal)
php artisan queue:work

# Start scheduler (separate terminal)
php artisan schedule:work
```

**Default Admin Login:**
- Email: `admin@spinneys.com`
- Password: `password`
- PIN: `123456`

Visit: `http://localhost:8000`

## 📊 Database Schema Overview

### Core Tables
- `users` - User accounts (balance, tier, referrals)
- `investment_packages` - Investment plans (slots, rates)
- `investments` - Active investments
- `deposits` / `withdrawals` - Financial operations
- `transactions` - Complete audit trail

### Referral & Gamification
- `referrals` - Multi-level tracking
- `achievements` - Achievement definitions
- `user_achievements` - User achievements
- `attendance` - Daily check-ins
- `raffles` - Monthly draws

### System
- `interest_logs` - Interest calculations
- `investment_receipts` - Receipt archives
- `audit_logs` - Activity logging

## 🎨 Branding Colors

```css
Primary (Deep Green): #0B4C2D
Secondary (Gold): #D4AF37
Off-White: #F8F8F6
Charcoal: #2C2C2C
```

Tailwind classes available:
```html
<div class="bg-spinneys-green text-spinneys-gold">
  <button class="bg-spinneys-gold hover:bg-spinneys-gold-600">
    Invest Now
  </button>
</div>
```

## ⚙️ Key Configuration

### .env Settings
```env
APP_NAME="SPINNEYS"
SESSION_LIFETIME=720  # 12 hours
QUEUE_CONNECTION=database
```

### Scheduled Jobs
- **00:30 Daily** - Process daily interest
- **01:00 Daily** - Process investment maturity
- **02:00 Monthly** - Draw monthly raffle

## 🔒 Security Features

- ✅ Email + PIN dual authentication
- ✅ 24-hour email verification tokens
- ✅ bcrypt password/PIN hashing
- ✅ CSRF protection on all forms
- ✅ SQL injection prevention (Eloquent)
- ✅ XSS protection (Blade templating)
- ✅ Rate limiting enabled
- ✅ User suspension system
- ✅ Complete audit logging

### Middleware
- `CheckSuspended` - Block suspended users
- `EnsureUserIsAdmin` - Admin-only access
- `EnsureUserIsVerified` - Verified users only
- `CheckPinVerification` - PIN required

## 🤖 Automated Workflows

### Investment Creation
1. Verify user tier eligibility
2. Atomic slot decrement
3. Deduct balance
4. Fire `InvestmentCreated` event
5. Process 3-level referral commissions
6. Generate branded receipt

### Daily Interest
1. Query active investments
2. Prevent duplicate calculations
3. Calculate interest by package rate
4. Add to user balance
5. Create transaction record
6. Log interest calculation

### Referral Commissions
```
Level 1 (Direct): 5%
Level 2: 2%
Level 3: 1%
```

## 🚀 Production Deployment

### 1. Optimize
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### 2. Set Environment
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 3. Database
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 4. Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 5. Queue & Scheduler
Setup Supervisor for queue workers and add cron job:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Docker Deployment
```bash
docker-compose up -d
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# With coverage
php artisan test --coverage
```

## 🐛 Troubleshooting

### Queue not processing
```bash
php artisan queue:restart
php artisan queue:work
```

### Assets not loading
```bash
npm run build
php artisan view:clear
php artisan cache:clear
```

### Permission errors
```bash
chmod -R 755 storage bootstrap/cache
```

## 📁 Project Structure

```
app/
├── Events/              # InvestmentCreated, UserRegistered
├── Jobs/                # ProcessDailyInterest, ProcessInvestmentMaturity
├── Listeners/           # ProcessReferralBonus, GenerateInvestmentReceipt
├── Models/              # All Eloquent models with relationships
└── Http/
    ├── Controllers/     # Application controllers
    └── Middleware/      # Security middleware

database/
├── migrations/          # All database schemas
└── seeders/            # Initial data seeding

resources/
├── views/              # Blade templates
└── js/                 # Alpine.js components

routes/
├── web.php             # Web routes
├── console.php         # Scheduled tasks
└── auth.php            # Authentication routes
```

## 📚 Core Models

- **User** - Balance management, PIN auth, referrals
- **InvestmentPackage** - Atomic slot management
- **Investment** - Complete lifecycle management
- **Transaction** - Polymorphic audit trail
- **Referral** - Multi-level commission tracking

## 🎯 Investment Package Types

1. **Starter Plan** (Basic)
   - Amount: $100 - $999.99
   - Daily Rate: 1.5%
   - Duration: 30 days
   
2. **Growth Plan** (Silver)
   - Amount: $1,000 - $4,999.99
   - Daily Rate: 2.0%
   - Duration: 60 days

3. **Premium Plan** (Gold)
   - Amount: $5,000 - $19,999.99
   - Daily Rate: 2.5%
   - Duration: 90 days

4. **Platinum Elite** (Platinum)
   - Amount: $20,000 - $100,000
   - Daily Rate: 3.0%
   - Duration: 120 days

## 📈 Future Roadmap

- [ ] Mobile app (Flutter/React Native)
- [ ] Two-factor authentication
- [ ] Advanced analytics
- [ ] KYC verification
- [ ] Cryptocurrency payments
- [ ] Multi-currency support
- [ ] RESTful API
- [ ] Webhook notifications

## 📄 License

Proprietary - All Rights Reserved

## 🙏 Acknowledgments

Built with Laravel 12, Tailwind CSS, and Alpine.js

---

**SPINNEYS Financial Platform** | Enterprise-Grade Investment Solution
