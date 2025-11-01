FROM php:8.2

# Обновляем список пакетов и устанавливаем необходимые системные зависимости
RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    libsqlite3-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev && \
    docker-php-ext-install pdo mbstring zip exif pcntl pdo_pgsql pgsql gd pdo_sqlite
RUN pecl install redis && docker-php-ext-enable redis
RUN apt-get clean && rm -rf /var/lib/apt/lists/*


# Устанавливаем Composer — менеджер зависимостей PHP
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию в контейнере
WORKDIR /var/www

# Копируем все файлы проекта из текущей папки на хосте в рабочую директорию контейнера
COPY . .

# Устанавливаем зависимости Laravel через Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Меняем владельца и права на папки для корректной работы веб-сервера и Laravel
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage




