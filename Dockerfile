FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    curl unzip git libzip-dev libsqlite3-dev libexif-dev \
    && docker-php-ext-install zip pdo pdo_sqlite exif

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build
RUN php artisan storage:link


RUN touch database/database.sqlite
RUN php artisan migrate --force --seed

EXPOSE 10000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]