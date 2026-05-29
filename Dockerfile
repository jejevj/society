# Dockerfile untuk Laravel 12 dengan PHP 8.4 dan Apache
FROM php:8.4-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    nano \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PostgreSQL dependencies
RUN apt-get update && apt-get install -y libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first for better caching
COPY composer.json composer.lock /var/www/html/

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy application files
COPY . /var/www/html

# Run post-install scripts
RUN composer dump-autoload --optimize

# Copy setup script
COPY docker/scripts/setup-storage.sh /usr/local/bin/setup-storage.sh
COPY docker/scripts/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/setup-storage.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

# Configure Apache
RUN a2enmod rewrite headers

# Copy Apache configuration
COPY docker/apache/laravel.conf /etc/apache2/sites-available/000-default.conf

# Run storage setup
RUN /usr/local/bin/setup-storage.sh

# Create storage symbolic link di public/ldt-asset/storage
RUN mkdir -p /var/www/html/public/ldt-asset \
    && ln -s /var/www/html/storage/app/public /var/www/html/public/ldt-asset/storage

# Expose port 80
EXPOSE 80

# Use entrypoint script
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
