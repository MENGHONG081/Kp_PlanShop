FROM php:8.2-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy all project files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# (Optional) If your entry file is index.php at root, comment below
# If you have /public/index.php, keep this
# ENV APACHE_DOCUMENT_ROOT /var/www/html/public
# RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
#     /etc/apache2/sites-available/*.conf \
#     /etc/apache2/apache2.conf
