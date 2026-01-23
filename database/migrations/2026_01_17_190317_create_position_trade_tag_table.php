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
        Schema::create('position_trade_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained()->onDelete('cascade');
            $table->foreignId('trade_tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['position_id', 'trade_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_trade_tag');
    }
};
