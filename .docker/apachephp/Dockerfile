FROM php:8.4-apache
LABEL maintainer="J.GAUTHI <github.com/jgauthi>"

# Some libs + Supervisor
RUN apt-get update && apt-get install -y --no-install-recommends vim curl locales apt-utils supervisor

# https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    apcu \
    amqp \
    intl \
    memcached \
    mysqli pdo_mysql \
    mbstring \
    opcache \
    openssl \
    soap \
    tidy \
    xdebug \
    zip

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install symfony cli
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/
RUN chown -R www-data:www-data /var/www

# PHP Conf
COPY 97-memcached.ini $PHP_INI_DIR/conf.d/97-memcached.ini
COPY 98-xdebug.ini $PHP_INI_DIR/conf.d/98-xdebug.ini
COPY 99-custom-php-conf.ini $PHP_INI_DIR/conf.d/99-custom-php-conf.ini

# Apache conf
COPY apache_default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite headers

# Purge
RUN rm -rf /var/lib/apt/lists/* \
    && apt-get purge --auto-remove -y g++ \
    && apt-get clean

# Supervisor conf for apache and worker
COPY supervisord-async.conf /etc/supervisor/conf.d/supervisord-async.conf
COPY supervisord-scheduler.conf /etc/supervisor/conf.d/supervisord-scheduler.conf

# USER www-data
WORKDIR /var/www/project/poc-messenger

CMD ["/usr/bin/supervisord", "-n"]
