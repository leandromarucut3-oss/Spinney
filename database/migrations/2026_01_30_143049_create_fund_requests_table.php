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
        Schema::create('fund_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('requested_from_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index('requester_id');
            $table->index('requested_from_id');
            $table->index('status');
            $table->index(['requested_from_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_requests');
    }
};
