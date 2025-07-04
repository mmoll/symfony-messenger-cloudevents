FROM php:8.3-fpm

COPY xdebug.so /usr/local/lib/php/extensions/no-debug-non-zts-20230831/
RUN touch /usr/local/etc/php/conf.d/99-xdebug.ini && echo "zend_extension = xdebug" > /usr/local/etc/php/conf.d/99-xdebug.ini