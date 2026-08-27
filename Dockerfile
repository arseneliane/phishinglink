FROM php:8.3-apache

COPY index.html capture.php /var/www/html/
RUN php -l /var/www/html/capture.php
RUN mkdir -p /var/lib/location-demo \
    && chown www-data:www-data /var/lib/location-demo \
    && chmod 700 /var/lib/location-demo

EXPOSE 80
