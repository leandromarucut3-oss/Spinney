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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('investment_packages');
            $table->decimal('amount', 15, 2);
            $table->decimal('daily_interest', 15, 2);
            $table->decimal('total_return', 15, 2)->default(0);
            $table->decimal('total_earned', 15, 2)->default(0);
            $table->integer('duration_days');
            $table->date('start_date');
            $table->date('maturity_date');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->string('receipt_number')->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('package_id');
            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
