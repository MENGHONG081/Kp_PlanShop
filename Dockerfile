FROM php:8.2-apache

RUN a2enmod rewrite

# PostgreSQL on Render (because your Render DB is Postgres)
RUN docker-php-ext-install pdo pdo_pgsql

COPY . /var/www/html/
WORKDIR /var/www/html

# ✅ Point web root to /User_Page
ENV APACHE_DOCUMENT_ROOT=/var/www/html/User_Page
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# permissions
RUN chown -R www-data:www-data /var/www/html \
 && find /var/www/html -type d -exec chmod 755 {} \; \
 && find /var/www/html -type f -exec chmod 644 {} \;
