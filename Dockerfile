FROM php:8.4-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    nginx \
    supervisor \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Set default environment variables for standalone container
# Note: APP_KEY is generated at runtime by entrypoint.sh if not provided
ENV APP_NAME=Tradalyze \
    APP_ENV=production \
    APP_DEBUG=false \
    APP_URL=http://localhost \
    LOG_LEVEL=error \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/var/www/html/database/database.sqlite \
    SESSION_DRIVER=database \
    QUEUE_CONNECTION=database \
    CACHE_STORE=database \
    FILESYSTEM_DISK=local

# Get latest Composer
COPY --from=docker.io/library/composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www/html

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Install Node dependencies and build assets
RUN npm ci --include=dev && npm run build && rm -rf node_modules

# Create SQLite database and set permissions
RUN mkdir -p /var/www/html/database /var/www/html/storage/app /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views /var/www/html/storage/logs \
    && touch /var/www/html/database/database.sqlite \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/database \
    && chmod 664 /var/www/html/database/database.sqlite

# Expose port 80
EXPOSE 80

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
