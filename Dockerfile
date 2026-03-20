FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    postgresql-dev \
    curl \
    git \
    openssl \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    libxml2-dev \
    oniguruma-dev \
    libpq

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_pgsql curl zip mbstring exif pcntl bcmath intl xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . /app

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Configure Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Remove default Nginx configuration
RUN rm /etc/nginx/conf.d/default.conf

# Copy custom Nginx configuration
COPY default.conf /etc/nginx/conf.d/default.conf

# Fix permissions
RUN chown -R www-data:www-data /app/User_Page/uploads \
    && chmod -R 775 /app/User_Page/uploads \
    && chown -R www-data:www-data /app/User_Page/state \
    && chmod -R 775 /app/User_Page/state \
    && chown -R www-data:www-data /app/User_Page/telegram_log.txt \
    && chmod -R 775 /app/User_Page/telegram_log.txt \
    && chown -R www-data:www-data /app/User_Page/gemini_tell.json \
    && chmod -R 775 /app/User_Page/gemini_tell.json \
    && chown -R www-data:www-data /app/User_Page/vendor \
    && chmod -R 775 /app/User_Page/vendor \
    && chown -R www-data:www-data /app/vendor \
    && chmod -R 775 /app/vendor \
    && chown -R www-data:www-data /app/User_Page \
    && chmod -R 775 /app/User_Page \
    && chown -R www-data:www-data /app

# Expose port 80
EXPOSE 80

# Start Nginx and PHP-FPM
CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"
