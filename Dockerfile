FROM php:8.0-fpm

ARG PHP_CONFIG=php.prod.ini
ARG PHP_XDEBUG=0
ARG NGINX_VHOST=vhost.conf

# install utilities
RUN apt-get update && \
    apt-get install -y curl default-mysql-client git wget

# install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# install Yarn package manager
RUN npm i -g yarn

# install nginx
RUN apt-get update && \
    apt-get install -y nginx

# install php-curl
RUN apt-get update && \
    apt-get install -y libcurl3-dev && \
    docker-php-ext-install curl

# install php-gd extension
RUN apt-get update && \
    apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
	docker-php-ext-configure gd --with-freetype --with-jpeg && \
	docker-php-ext-install -j$(nproc) gd

# install php-imagick extension
RUN apt-get update && \
    apt-get install -y libmagickwand-dev && \
    pecl install imagick && \
    docker-php-ext-enable imagick

# install php-intl extension
RUN apt-get update && \
    apt-get -y install libicu-dev && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl

# install php-redis extension
RUN pecl install redis && \
    docker-php-ext-enable redis

# install php-xdebug extension (optionally)
RUN if [ "$PHP_XDEBUG" -eq "1" ]; then pecl install xdebug && docker-php-ext-enable xdebug; fi

# install php-zip extension
RUN apt-get update && \
    apt-get install -y libzip-dev && \
    docker-php-ext-install -j$(nproc) zip

# install other extensions
RUN docker-php-ext-install bcmath ctype fileinfo opcache pdo_mysql xml

# install composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# set a consistent uid
RUN usermod -u 1000 www-data

# set working directory
WORKDIR /var/www/html

# install project deps
COPY --chown=www-data composer.json .
COPY --chown=www-data composer.lock .
COPY --chown=www-data package.json .
COPY --chown=www-data yarn.lock .

# install composer deps
RUN composer install --no-autoloader --no-dev --no-interaction --no-scripts

# install Node.js deps
RUN yarn install --frozen-lockfile

# override nginx vhost
COPY .docker/$NGINX_VHOST /etc/nginx/sites-available/default

# override php config
COPY .docker/$PHP_CONFIG /usr/local/etc/php/conf.d/99-overrides.ini

# copy project files
COPY --chown=www-data . .

# generate autoload files
RUN composer dump-autoload --optimize

# compile static assets
RUN yarn build

# override default entrypoint
COPY docker-entrypoint.sh /
RUN chmod +x /docker-entrypoint.sh
ENTRYPOINT ["/docker-entrypoint.sh"]

# run php-fpm (bg) and nginx (fg)
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
