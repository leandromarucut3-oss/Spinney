<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pin', 255)->nullable()->after('password');
            $table->string('phone')->nullable()->after('email');
            $table->decimal('balance', 15, 2)->default(0)->after('phone');
            $table->string('referral_code', 20)->unique()->after('balance');
            $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete()->after('referral_code');
            $table->enum('tier', ['basic', 'silver', 'gold', 'platinum'])->default('basic')->after('referred_by');
            $table->boolean('is_verified')->default(false)->after('tier');
            $table->boolean('is_suspended')->default(false)->after('is_verified');
            $table->boolean('is_admin')->default(false)->after('is_suspended');
            $table->timestamp('last_login_at')->nullable()->after('is_admin');
            $table->string('email_verification_token')->nullable()->after('email_verified_at');
            $table->timestamp('email_verification_token_expires_at')->nullable()->after('email_verification_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'pin', 'phone', 'balance', 'referral_code', 'referred_by',
                'tier', 'is_verified', 'is_suspended', 'is_admin',
                'last_login_at', 'email_verification_token',
                'email_verification_token_expires_at'
            ]);
        });
    }
};
