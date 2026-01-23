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
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('underlying_symbol')->nullable();
            $table->enum('asset_type', ['STK', 'OPT']);
            $table->date('expiry')->nullable();
            $table->decimal('strike', 10, 2)->nullable();
            $table->enum('put_call', ['P', 'C'])->nullable();
            $table->integer('multiplier')->default(100);
            $table->string('currency', 10)->default('USD');
            $table->timestamps();
            
            // Uniqueness constraint: symbol + expiry + strike + put_call + asset_type
            $table->unique(['symbol', 'expiry', 'strike', 'put_call', 'asset_type'], 'instruments_unique');
            
            // Indexes for common queries
            $table->index('symbol');
            $table->index('underlying_symbol');
            $table->index('asset_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instruments');
    }
};
