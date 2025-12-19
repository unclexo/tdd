# PHP runtime
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    git \
    unzip \
    sqlite3 \
    libcurl4-openssl-dev \
    libxml2-dev \
    libonig-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libzip-dev \
    libicu-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install \
        curl \
        mbstring \
        xml \
        pdo_mysql \
        gd \
        zip \
        intl && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Application user
# Do provide these user info while building this image
# Do make sure APP_UID/APP_GID matches host to avoid permission isssues
ARG APP_USER
ARG APP_UID
ARG APP_GID

RUN groupadd -g ${APP_GID} ${APP_USER} && \
    useradd -u ${APP_UID} -g ${APP_GID} -m ${APP_USER}

# Change working dir
WORKDIR /var/www/html

# Switch to non-root user
USER ${APP_USER}

# Expose PHP-FPM port
EXPOSE 9000

# Run PHP-FPM in foreground
CMD ["php-fpm", "-F"]
