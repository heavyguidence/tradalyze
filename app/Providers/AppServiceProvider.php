<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-create SQLite database file if it doesn't exist
        if (config('database.default') === 'sqlite') {
            $database = config('database.connections.sqlite.database');
            if ($database !== ':memory:' && !file_exists($database)) {
                $directory = dirname($database);
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                touch($database);
                chmod($database, 0664);
            }
        }
    }
}
