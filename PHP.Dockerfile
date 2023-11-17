# To interact PHP with MySQL database server, we need to install PDO_MySQL Driver.
# php-fpm is our base PHP image.
# We will install the PDO package into the php-fpm image. We need to build our own 
# image using the php-fpm image as the base image. We need to update the docker-compose.yaml file.
FROM php:fpm

RUN docker-php-ext-install pdo pdo_mysql

# If you need to install mysqli instead of pdo
# RUN docker-php-ext-install mysqli

RUN pecl install xdebug && docker-php-ext-enable xdebug