# Use the official PHP image as a base image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo pdo_mysql

# Expose port 9000 and start PHP-FPM server
EXPOSE 9000
CMD ["php-fpm"]
