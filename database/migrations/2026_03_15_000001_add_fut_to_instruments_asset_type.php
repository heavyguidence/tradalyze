<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite stores enum as varchar with no CHECK constraint — no change needed.
        // MySQL requires an explicit MODIFY to expand the ENUM definition.
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE instruments MODIFY COLUMN asset_type ENUM('STK', 'OPT', 'FUT') NOT NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE instruments MODIFY COLUMN asset_type ENUM('STK', 'OPT') NOT NULL");
        }
    }
};
