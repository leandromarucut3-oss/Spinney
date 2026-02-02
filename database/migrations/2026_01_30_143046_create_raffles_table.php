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
        Schema::create('raffles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('prize_amount', 15, 2);
            $table->integer('total_entries')->default(0);
            $table->date('draw_date');
            $table->foreignId('winner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'active', 'drawn', 'completed'])->default('pending');
            $table->timestamp('drawn_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('draw_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raffles');
    }
};
