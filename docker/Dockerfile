ARG PHP_VERSION
FROM php:${PHP_VERSION} as app

## Diretório da aplicação
ARG APP_DIR=/var/www/app

## Versão da Lib do Redis para PHP
ARG REDIS_LIB_VERSION=5.3.7

### apt-utils é um extensão de recursos do gerenciador de pacotes APT
RUN apt-get update -y && apt-get install -y --no-install-recommends \
    apt-utils \ 
    supervisor \
    nano

# dependências recomendadas de desenvolvido para ambiente linux
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libpng-dev \
    libpq-dev \
    libxml2-dev \
    libbrotli-dev

RUN docker-php-ext-install sockets mysqli pdo pdo_mysql pdo_pgsql pgsql session xml zip iconv simplexml pcntl gd fileinfo

# habilita instalação do Redis
RUN pecl install redis-${REDIS_LIB_VERSION} \
    && docker-php-ext-enable redis

### Instalar e Habilitar o Swoole
RUN pecl install swoole
RUN docker-php-ext-enable swoole

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./docker/supervisord/supervisord.octane.conf /etc/supervisor/conf.d/supervisord.conf
### Supervisor permite monitorar e controlar vários processos (LINUX)
### Bastante utilizado para manter processos em Daemon, ou seja, executando em segundo plano

COPY ./docker/php/extra-php.ini "$PHP_INI_DIR/conf.d"
#COPY ./docker/php/extra-php-fpm.conf /etc/php8/php-fpm.d/www.conf

WORKDIR $APP_DIR
RUN cd $APP_DIR
RUN chown www-data:www-data $APP_DIR

COPY --chown=www-data:www-data ./app .
RUN rm -rf vendor

RUN composer install --no-interaction
RUN php artisan octane:install --server=swoole
RUN composer update --no-interaction

RUN php artisan clear-compiled
RUN php artisan optimize

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN chown -R www-data:www-data /var/www/app/storage
RUN php artisan storage:link
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

FROM app as app_dev

RUN pecl install xdebug;
RUN docker-php-ext-enable xdebug
#RUN echo "zend_extension=xdebug.so" > "${PHP_INI_DIR}/conf.d/extra-php.ini"
