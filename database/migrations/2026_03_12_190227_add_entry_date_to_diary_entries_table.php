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
        Schema::table('diary_entries', function (Blueprint $table) {
            $table->date('entry_date')->nullable()->after('user_id');
            // One diary entry per trading date per user (NULL rows are exempt — undated general notes)
            $table->unique(['user_id', 'entry_date']);
        });
    }

    public function down(): void
    {
        Schema::table('diary_entries', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'entry_date']);
            $table->dropColumn('entry_date');
        });
    }
};
