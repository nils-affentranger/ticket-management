  FROM php:8.2-fpm

  # Install system dependencies
  RUN apt-get update && apt-get install -y \
      libpng-dev \
      libjpeg-dev \
      libfreetype6-dev \
      zip \
      unzip \
      git \
      curl \
      libonig-dev \
      libxml2-dev \
      libzip-dev \
      && docker-php-ext-configure gd --with-freetype --with-jpeg \
      && docker-php-ext-install gd

  # Install PDO and PDO_MySQL
  RUN docker-php-ext-install pdo pdo_mysql

  # Set working directory
  WORKDIR /var/www
