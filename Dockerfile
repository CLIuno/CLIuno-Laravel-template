# Use the official Ubuntu base image
FROM ubuntu:latest

# Set environment variables
ENV DEBIAN_FRONTEND=noninteractive

# Install necessary packages
RUN apt-get update && apt-get install -y \
    software-properties-common \
    curl \
    zip \
    unzip \
    git \
    && add-apt-repository ppa:ondrej/php \
    && apt-get update && apt-get install -y \
    php8.4 \
    php8.4-cli \
    php8.4-fpm \
    php8.4-sqlite3 \
    php8.4-redis \
    php8.4-mbstring \
    php8.4-xml \
    php8.4-curl \
    php8.4-zip \
    php8.4-bcmath \
    php8.4-intl \
    php8.4-gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Download and install nvm:
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash

# Download and install Node.js:
RUN nvm install 22

# Download and install pnpm:
RUN corepack enable pnpm

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install pnpm dependencies
RUN pnpm install

# Install Laravel dependencies
RUN composer install

# Expose port 3000 and start php-fpm server
EXPOSE 3000
CMD ["composer", "dev"]
