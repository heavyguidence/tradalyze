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
        Schema::create('fills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->dateTime('datetime');
            $table->enum('side', ['BUY', 'SELL']);
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 12, 4);
            $table->decimal('fees', 10, 2)->default(0);
            $table->string('order_id')->nullable();
            $table->string('exec_id')->nullable();
            $table->timestamps();
            
            // Indexes for queries and sorting
            $table->index('instrument_id');
            $table->index('datetime');
            $table->index('side');
            $table->index('exec_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fills');
    }
};
