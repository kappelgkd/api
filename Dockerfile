FROM php:8.1-apache

RUN apt-get -y update --fix-missing && apt-get upgrade -y

# Install important libraries && useful tools
RUN apt-get -y install --fix-missing \
    build-essential git curl vim nano wget \
    libcurl4 libcurl4-openssl-dev zip zlib1g-dev \
    libzip-dev libicu-dev libz-dev libmemcached-dev \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install xdebug
# RUN pecl install xdebug-2.9.0 && docker-php-ext-enable xdebug

#PHP configure
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --enable-gd \
  && docker-php-ext-install -j "$(nproc)" gd

# RUN php -r 'var_dump(gd_info());'

# Other PHP7 Extensions
RUN apt-get -y install libsqlite3-dev libsqlite3-0 mariadb-client; \
    docker-php-ext-install pdo_mysql; \
    docker-php-ext-install mysqli; \
    docker-php-ext-install bcmath; \
    docker-php-ext-install curl; \
    docker-php-ext-install tokenizer; \
    docker-php-ext-install json; \
    docker-php-ext-install intl; \
    docker-php-ext-install zip

# RUN pecl install -o -f redis \
#     &&  rm -rf /tmp/pear \
#     &&  docker-php-ext-enable redis

# RUN apt-get install -y libz-dev libmemcached-dev && \
#     pecl install memcached && \
#     docker-php-ext-enable memcached

# Imagemagick
RUN apt-get install --yes --force-yes libmagickwand-dev libmagickcore-dev
RUN yes '' | pecl install -f imagick
RUN docker-php-ext-enable imagick

ENV APACHE_RUN_DIR /tmp/
ENV APACHE_LOG_DIR /tmp/

########################
# Ajusta php ini
RUN echo 'date.timezone = "America/Sao_Paulo"' > /usr/local/etc/php/conf.d/timezone.ini

RUN echo -e "\
file_uploads = On\n \
memory_limit = 512M\n \
upload_max_filesize = 512M\n \
post_max_size = 512M\n \
max_execution_time = 5000\n \
" > /usr/local/etc/php/conf.d/uploads.ini

# Some more ls aliases
RUN echo "alias ll='ls -alhF'" >> ~/.bashrc
RUN echo "alias la='ls -A'" >> ~/.bashrc
RUN echo "alias l='ls -CF'" >> ~/.bashrc

# Enable apache modules
RUN a2enmod rewrite headers
RUN a2enmod ssl

# WORKDIR /var/www/html

# COPY . .


# # RUN chown -R www-data:www-data /var/www

# EXPOSE 80