FROM redbus/php:7-apache

# Install packages
RUN apt-get update && apt-get install --no-install-recommends -y \
    php-soap \
    php7.0-gd

COPY docker/php.ini /etc/php/7.0/mods-available/app.ini

# Project sources
RUN usermod -u 1000 www-data \
    && chown www-data -R /composer
VOLUME ["/srv/app", "/composer"]
