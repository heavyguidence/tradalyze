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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            
            // Broker information
            $table->string('broker')->default('interactive_broker');
            $table->string('trade_id')->unique(); // TradeID from CSV - ensures no duplicates
            
            // Essential Trade Information
            $table->string('symbol');
            $table->string('asset_class'); // STK, OPT, etc.
            $table->string('underlying_symbol')->nullable();
            $table->text('description')->nullable();
            $table->enum('open_close_indicator', ['O', 'C']); // O=Open, C=Close
            $table->enum('buy_sell', ['BUY', 'SELL']);
            $table->decimal('quantity', 15, 4);
            $table->decimal('trade_price', 15, 4);
            $table->date('trade_date');
            $table->dateTime('date_time'); // Exact execution time
            $table->decimal('fifo_pnl_realized', 15, 2)->nullable(); // Only on closed trades
            $table->decimal('net_cash', 15, 2);
            $table->string('currency_primary', 10)->default('USD');
            
            // Option-Specific Fields
            $table->string('put_call', 1)->nullable(); // C or P
            $table->decimal('strike', 15, 2)->nullable();
            $table->date('expiry')->nullable();
            $table->integer('multiplier')->nullable()->default(100);
            
            // Commission and Costs
            $table->decimal('ib_commission', 15, 4)->nullable();
            $table->string('ib_commission_currency', 10)->nullable();
            
            // Additional useful fields
            $table->decimal('trade_money', 15, 2)->nullable(); // Gross trade value
            $table->decimal('proceeds', 15, 2)->nullable();
            $table->string('exchange')->nullable();
            $table->decimal('cost_basis', 15, 2)->nullable();
            
            // Indexes for faster queries
            $table->index('symbol');
            $table->index('trade_date');
            $table->index('asset_class');
            $table->index('open_close_indicator');
            $table->index(['symbol', 'trade_date']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
