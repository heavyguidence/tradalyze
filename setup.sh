#!/bin/bash

echo "🚀 Tradalyze Setup Script"
echo "========================="
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "✅ .env file created"
else
    echo "✅ .env file already exists"
fi

# Check if APP_KEY is set
if grep -q "APP_KEY=$" .env || grep -q "APP_KEY=\"\"" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate
    echo "✅ Application key generated"
else
    echo "✅ Application key already set"
fi

# Check if SQLite database exists
if grep -q "DB_CONNECTION=sqlite" .env; then
    DB_FILE=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
    
    # Default to database/database.sqlite if not set
    if [ -z "$DB_FILE" ]; then
        DB_FILE="database/database.sqlite"
    fi
    
    if [ ! -f "$DB_FILE" ]; then
        echo "📦 Creating SQLite database..."
        touch "$DB_FILE"
        chmod 664 "$DB_FILE"
        echo "✅ SQLite database created at $DB_FILE"
    else
        echo "✅ SQLite database already exists"
    fi
    
    # Run migrations
    echo "🗄️  Running database migrations..."
    php artisan migrate --force
    echo "✅ Migrations completed"
    
    # Seed trade tags
    echo "🏷️  Seeding trade tags..."
    php artisan db:seed --class=TradeTagSeeder --force
    echo "✅ Trade tags seeded"
fi

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link
echo "✅ Storage link created"

echo ""
echo "✨ Setup complete! You can now run:"
echo "   php artisan serve"
echo ""
