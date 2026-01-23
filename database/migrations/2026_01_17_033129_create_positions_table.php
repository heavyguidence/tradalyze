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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->dateTime('open_datetime');
            $table->dateTime('close_datetime')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->decimal('cost_basis', 12, 4);
            $table->decimal('realized_pnl', 12, 2)->nullable();
            $table->timestamps();
            
            // Indexes for queries
            $table->index('instrument_id');
            $table->index('open_datetime');
            $table->index('close_datetime');
            $table->index(['close_datetime', 'realized_pnl']); // For PnL queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
