# Use the official PHP 8 Apache image
FROM php:8.0-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite for .htaccess support
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy the project files to the working directory
COPY . .

# Expose port 8088
EXPOSE 8088

# Start the Apache server
CMD ["apache2-foreground"]