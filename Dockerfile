FROM bryanlatten/docker-php:7.1-alpine

COPY . /app

WORKDIR /app
RUN composer install --no-dev
