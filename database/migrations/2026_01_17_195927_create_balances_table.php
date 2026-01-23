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
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['initial', 'deposit', 'withdrawal']);
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->string('description')->nullable();
            $table->timestamps();
            
            // Ensure only one initial balance per user
            $table->unique(['user_id', 'type'], 'unique_initial_balance')
                  ->where('type', 'initial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balances');
    }
};
