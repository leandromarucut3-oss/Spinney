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
        Schema::create('investment_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('image')->nullable();
            $table->decimal('min_amount', 15, 2);
            $table->decimal('max_amount', 15, 2);
            $table->decimal('daily_interest_rate', 5, 2);
            $table->integer('duration_days');
            $table->integer('total_slots');
            $table->integer('available_slots');
            $table->boolean('is_active')->default(true);
            $table->enum('tier_required', ['basic', 'silver', 'gold', 'platinum'])->default('basic');
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index('tier_required');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_packages');
    }
};
