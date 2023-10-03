# Use the official PHP-FPM image as a base image
FROM php:8.1-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory to the Laravel application path
WORKDIR /var/www/html

# Copy the Laravel project files to the container
COPY . .

# Install project dependencies
RUN composer install

# Expose port 9000 to communicate with the web server
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
