FROM php:8.3-fpm

COPY xdebug.so /usr/local/lib/php/extensions/no-debug-non-zts-20230831/
RUN touch /usr/local/etc/php/conf.d/99-xdebug.ini &&  \
    echo "zend_extension = xdebug" > /usr/local/etc/php/conf.d/99-xdebug.ini && \
    echo "xdebug.mode = coverage" >> /usr/local/etc/php/conf.d/99-xdebug.ini && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer &&  \
    composer self-update && \
    apt update && \
    apt install git -y \