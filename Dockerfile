FROM php:8.2-apache

WORKDIR /app

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy project files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Fix MPM conflict
RUN rm -f /etc/apache2/mods-enabled/mpm_*.conf \
          /etc/apache2/mods-enabled/mpm_*.load && \
    a2enmod mpm_prefork

# Setup Apache
RUN a2enmod rewrite
RUN sed -i 's|/var/www/html|/app/public|g' /etc/apache2/sites-available/000-default.conf

# Fix port
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 8080

CMD ["apache2-foreground"]