# SPINNEYS - Development Guide

## 🏗️ Architecture Overview

### Laravel 12 Structure
```
app/
├── Events/              # Domain events
├── Jobs/                # Queued background jobs
├── Listeners/           # Event handlers
├── Models/              # Eloquent models
├── Http/
│   ├── Controllers/     # Request handlers
│   ├── Middleware/      # HTTP middleware
│   └── Requests/        # Form request validation
└── Providers/           # Service providers

database/
├── migrations/          # Database schema
└── seeders/            # Data seeding

resources/
├── views/              # Blade templates
├── js/                 # JavaScript/Alpine.js
└── css/                # Tailwind CSS

routes/
├── web.php             # Web routes
├── auth.php            # Authentication routes
└── console.php         # CLI commands & schedule
```

## 📝 Coding Standards

### PHP (PSR-12)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Example extends Model
{
    // Properties
    protected $fillable = ['name', 'email'];
    
    // Relationships (type-hinted)
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
    
    // Methods (type-hinted)
    public function calculateTotal(): float
    {
        return $this->items->sum('amount');
    }
}
```

### Blade Templates
```blade
{{-- Use kebab-case for component names --}}
<x-investment-card :package="$package" />

{{-- Always escape output --}}
{{ $user->name }}

{{-- Use @auth, @guest directives --}}
@auth
    <p>Welcome, {{ auth()->user()->name }}</p>
@endauth
```

### JavaScript/Alpine.js
```javascript
// Use Alpine.js for interactive components
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open">Content</div>
</div>
```

## 🔄 Adding New Features

### 1. Create a New Investment Type

#### Step 1: Update Model (Optional)
```php
// app/Models/InvestmentPackage.php
public function isFlexible(): bool
{
    return $this->type === 'flexible';
}
```

#### Step 2: Create Migration
```bash
php artisan make:migration add_type_to_investment_packages_table
```

```php
Schema::table('investment_packages', function (Blueprint $table) {
    $table->enum('type', ['fixed', 'flexible'])->default('fixed');
});
```

#### Step 3: Create Seeder
```php
// database/seeders/FlexiblePackageSeeder.php
InvestmentPackage::create([
    'name' => 'Flexible Plan',
    'type' => 'flexible',
    // ... other fields
]);
```

### 2. Add New Event & Listener

#### Create Event
```bash
php artisan make:event WithdrawalApproved
```

```php
<?php

namespace App\Events;

use App\Models\Withdrawal;
use Illuminate\Foundation\Events\Dispatchable;

class WithdrawalApproved
{
    use Dispatchable;

    public function __construct(
        public Withdrawal $withdrawal
    ) {}
}
```

#### Create Listener
```bash
php artisan make:listener SendWithdrawalNotification
```

```php
<?php

namespace App\Listeners;

use App\Events\WithdrawalApproved;
use Illuminate\Support\Facades\Mail;

class SendWithdrawalNotification
{
    public function handle(WithdrawalApproved $event): void
    {
        // Send notification email
        Mail::to($event->withdrawal->user)
            ->send(new WithdrawalApprovedMail($event->withdrawal));
    }
}
```

#### Register in AppServiceProvider
```php
Event::listen(
    WithdrawalApproved::class,
    SendWithdrawalNotification::class
);
```

### 3. Create New Scheduled Job

```bash
php artisan make:job SendWeeklyReport
```

```php
<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendWeeklyReport implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        // Generate and send weekly report
    }
}
```

#### Schedule It
```php
// routes/console.php
Schedule::job(new SendWeeklyReport)
    ->weekly()
    ->sundays()
    ->at('08:00');
```

### 4. Add New Middleware

```bash
php artisan make:middleware CheckInvestmentLimit
```

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckInvestmentLimit
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        if ($user->activeInvestments()->count() >= 5) {
            return redirect()->back()
                ->with('error', 'Maximum 5 active investments allowed');
        }
        
        return $next($request);
    }
}
```

#### Register It
```php
// bootstrap/app.php
$middleware->alias([
    'investment.limit' => \App\Http\Middleware\CheckInvestmentLimit::class,
]);
```

## 🎨 UI Development

### Creating Components

#### Blade Component
```bash
php artisan make:component InvestmentCard
```

```blade
{{-- resources/views/components/investment-card.blade.php --}}
<div class="bg-spinneys-green rounded-lg p-6 shadow-lg">
    <h3 class="text-spinneys-gold text-xl font-bold">
        {{ $package->name }}
    </h3>
    <p class="text-spinneys-off-white mt-2">
        {{ $package->description }}
    </p>
    <div class="mt-4">
        <span class="text-spinneys-gold text-2xl font-bold">
            {{ $package->daily_interest_rate }}%
        </span>
        <span class="text-spinneys-off-white">daily</span>
    </div>
</div>
```

#### Usage
```blade
<x-investment-card :package="$package" />
```

### Tailwind Customization

```javascript
// tailwind.config.js
export default {
    theme: {
        extend: {
            colors: {
                'brand': {
                    'primary': '#0B4C2D',
                    'secondary': '#D4AF37',
                }
            },
            spacing: {
                '128': '32rem',
            }
        }
    }
}
```

## 🧪 Testing

### Feature Test Example
```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\InvestmentPackage;
use Tests\TestCase;

class InvestmentTest extends TestCase
{
    public function test_user_can_create_investment(): void
    {
        $user = User::factory()->create(['balance' => 1000]);
        $package = InvestmentPackage::factory()->create([
            'min_amount' => 100,
            'available_slots' => 10,
        ]);

        $response = $this->actingAs($user)
            ->post('/investments', [
                'package_id' => $package->id,
                'amount' => 500,
            ]);

        $response->assertRedirect();
        $this->assertEquals(500, $user->fresh()->balance);
        $this->assertEquals(1, $user->investments()->count());
    }
}
```

### Model Test Example
```php
public function test_investment_calculates_daily_interest_correctly(): void
{
    $package = InvestmentPackage::factory()->create([
        'daily_interest_rate' => 2.0,
    ]);

    $interest = $package->calculateDailyInterest(1000);

    $this->assertEquals(20.00, $interest);
}
```

## 📊 Database Tips

### Using Transactions
```php
use Illuminate\Support\Facades\DB;

DB::transaction(function () use ($user, $amount) {
    $user->deductBalance($amount, 'withdrawal', 'Withdrawal request');
    Withdrawal::create([...]);
});
```

### Eager Loading
```php
// Bad - N+1 queries
$investments = Investment::all();
foreach ($investments as $investment) {
    echo $investment->user->name;
}

// Good - Eager loading
$investments = Investment::with('user')->get();
foreach ($investments as $investment) {
    echo $investment->user->name;
}
```

### Query Optimization
```php
// Use indexes
Investment::where('user_id', $userId)
    ->where('status', 'active')
    ->get();

// Use select to limit columns
User::select('id', 'name', 'email')->get();

// Use chunk for large datasets
Investment::chunk(100, function ($investments) {
    foreach ($investments as $investment) {
        // Process
    }
});
```

## 🔒 Security Best Practices

### Input Validation
```php
// app/Http/Requests/CreateInvestmentRequest.php
public function rules(): array
{
    return [
        'package_id' => 'required|exists:investment_packages,id',
        'amount' => 'required|numeric|min:100|max:100000',
        'pin' => 'required|digits:6',
    ];
}
```

### Authorization
```php
// app/Policies/InvestmentPolicy.php
public function create(User $user, InvestmentPackage $package): bool
{
    return $user->canInvestInPackage($package)
        && !$user->is_suspended
        && $user->is_verified;
}
```

### Mass Assignment Protection
```php
// Always define $fillable or $guarded
protected $fillable = ['name', 'email', 'balance'];

// Never use except in seeders
Model::unguard();
```

## 📦 Package Integration

### Installing New Package
```bash
composer require vendor/package

# For dev dependencies
composer require --dev vendor/package
```

### Publishing Assets
```bash
php artisan vendor:publish --provider="Vendor\Package\ServiceProvider"
```

## 🐛 Debugging

### Logging
```php
use Illuminate\Support\Facades\Log;

Log::info('Investment created', ['investment_id' => $investment->id]);
Log::error('Failed to process', ['error' => $e->getMessage()]);
Log::debug('Debug info', ['data' => $data]);
```

### Query Debugging
```php
DB::enableQueryLog();
// Run queries
dd(DB::getQueryLog());
```

### Tinker Usage
```bash
php artisan tinker

>>> $user = User::find(1)
>>> $user->investments()->count()
>>> App\Jobs\ProcessDailyInterest::dispatchSync()
```

## 🚀 Performance Optimization

### Caching
```php
// Cache expensive queries
$packages = Cache::remember('packages', 3600, function () {
    return InvestmentPackage::where('is_active', true)->get();
});

// Clear specific cache
Cache::forget('packages');

// Clear all cache
php artisan cache:clear
```

### Queue Optimization
```php
// High priority queue
ProcessCriticalJob::dispatch()->onQueue('high');

// Delayed job
ProcessReport::dispatch()->delay(now()->addHours(2));
```

## 📚 Useful Artisan Commands

```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate IDE helper
php artisan ide-helper:models
php artisan ide-helper:generate

# Database
php artisan db:show
php artisan migrate:status
php artisan migrate:fresh --seed

# Queue
php artisan queue:work --queue=high,default
php artisan queue:failed
php artisan queue:retry all

# Maintenance mode
php artisan down --secret="maintenance-secret"
php artisan up
```

## 🔄 Git Workflow

```bash
# Feature branch
git checkout -b feature/new-investment-type
git add .
git commit -m "feat: Add flexible investment type"
git push origin feature/new-investment-type

# Pull request review
# Merge to main

# Deploy
git checkout main
git pull origin main
composer install --no-dev
npm run build
php artisan migrate --force
php artisan config:cache
```

## 📖 Additional Resources

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)
- [Alpine.js Guide](https://alpinejs.dev/start-here)
- [PHP The Right Way](https://phptherightway.com/)

---

**Happy Developing! 🚀**
