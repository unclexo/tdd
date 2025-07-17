# STAGE 1: Composer-only base for optimized builds
FROM php:8.2-cli AS composer

# Install system dependencies for composer
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    zip \
    libzip-dev && \
    docker-php-ext-install zip

# Copy Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# STAGE 2: PHP Runtime for dev
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Accept build-time user info
ARG USER
ARG UID
ARG GID

# Install dependencies
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libgd-dev \
    libpq-dev \
    libzip-dev \
    git \
    unzip \
    sudo \
    sqlite3 \
    openssh-client && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install \
        pdo \
        pdo_mysql \
        gd \
        zip \
        exif \
        intl \
        pcntl \
        posix \
        sockets && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Add non-root user with host-matching UID
RUN groupadd -g "${GID}" "${USER}" && \
    useradd --create-home -u "${UID}" -g "${GID}" -s /bin/bash "${USER}"

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Switch to non-root user
USER ${USER}

# Run FPM as root
CMD ["php-fpm", "-R"]
