@echo off
echo 🚀 Tradalyze Setup Script
echo =========================
echo.

REM Check if .env exists
if not exist .env (
    echo 📝 Creating .env file...
    copy .env.example .env
    echo ✅ .env file created
) else (
    echo ✅ .env file already exists
)

REM Generate APP_KEY if not set
findstr /C:"APP_KEY=" .env | findstr /C:"APP_KEY=$" >nul
if %errorlevel%==0 (
    echo 🔑 Generating application key...
    php artisan key:generate
    echo ✅ Application key generated
) else (
    echo ✅ Application key already set
)

REM Check if using SQLite
findstr /C:"DB_CONNECTION=sqlite" .env >nul
if %errorlevel%==0 (
    if not exist database\database.sqlite (
        echo 📦 Creating SQLite database...
        type nul > database\database.sqlite
        echo ✅ SQLite database created
    ) else (
        echo ✅ SQLite database already exists
    )
    
    REM Run migrations
    echo 🗄️  Running database migrations...
    php artisan migrate --force
    echo ✅ Migrations completed
    
    REM Seed trade tags
    echo 🏷️  Seeding trade tags...
    php artisan db:seed --class=TradeTagSeeder --force
    echo ✅ Trade tags seeded
)

REM Create storage link
echo 🔗 Creating storage link...
php artisan storage:link
echo ✅ Storage link created

echo.
echo ✨ Setup complete! You can now run:
echo    php artisan serve
echo.
pause
