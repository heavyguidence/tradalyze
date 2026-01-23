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
        Schema::create('ledger', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->dateTime('datetime');
            $table->enum('type', ['commission', 'fee', 'pnl', 'dividend', 'expiry', 'assignment']);
            $table->decimal('amount', 12, 2);
            $table->timestamps();
            
            // Indexes
            $table->index('instrument_id');
            $table->index('datetime');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger');
    }
};
