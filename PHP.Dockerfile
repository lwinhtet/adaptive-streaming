# This line sets the base image for the Docker container. In this case, it uses the official 
# PHP image with the PHP-FPM (FastCGI Process Manager) variant.
FROM php:fpm

# This block of code uses the package manager (apt-get in this case) to update the package lists, 
# install the ffmpeg package, and then install PHP extensions (pdo and pdo_mysql).
RUN apt-get update && \
    apt-get install -y ffmpeg && \
    docker-php-ext-install pdo pdo_mysql && \ 
    apt-get install -y nano

# If you need to install mysqli instead of pdo
# RUN docker-php-ext-install mysqli

# RUN pecl install xdebug && docker-php-ext-enable xdebug

# Set the working directory to /app
WORKDIR /app

# Copy the current directory contents into the container at /app
COPY . /app

# Install application dependencies (if using Composer)
# RUN composer install --no-interaction --no-plugins --no-scripts

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

# This is a Dockerfile, which is a set of instructions used to build a Docker image. 
# A Docker image is a lightweight, standalone, executable package that includes everything 
# needed to run a piece of software, including the code, runtime, libraries, and system tools.
# In summary, this Dockerfile is configuring a PHP-FPM environment with additional extensions 
# and dependencies needed for a specific application. It sets up the working directory, copies 
# the application code, and exposes the necessary ports for PHP-FPM to run.