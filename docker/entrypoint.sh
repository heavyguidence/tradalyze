#!/bin/sh

# Entrypoint script for Laravel application

# Generate APP_KEY if not provided
if [ -z "$APP_KEY" ]; then
    echo "No APP_KEY provided, generating one..."
    export APP_KEY=$(php artisan key:generate --show)
    echo "Generated APP_KEY: $APP_KEY"
fi

# Create SQLite database if it doesn't exist
if [ "$DB_CONNECTION" = "sqlite" ] && [ ! -f "$DB_DATABASE" ]; then
    echo "Creating SQLite database file..."
    touch "$DB_DATABASE"
    chown www-data:www-data "$DB_DATABASE"
    chmod 664 "$DB_DATABASE"
fi

# Ensure proper permissions on database directory
if [ "$DB_CONNECTION" = "sqlite" ]; then
    chown -R www-data:www-data /var/www/html/database
    chmod -R 775 /var/www/html/database
fi

# Run migrations automatically if DB is empty
php artisan migrate --force 2>/dev/null || true

# Execute the main command (supervisord)
exec "$@"
