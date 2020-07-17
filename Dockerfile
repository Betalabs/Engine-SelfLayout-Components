FROM ubuntu:18.04

ENV BACKEND_PATH=/code

MAINTAINER Betalabs

## Update
RUN apt-get update -y

## Upgrade
RUN apt-get install -y software-properties-common && \
    add-apt-repository -y ppa:certbot/certbot && \
    apt-get update -y && \
    apt-get upgrade -y && \
    add-apt-repository ppa:ondrej/php && \
    apt-get dist-upgrade -y && \
    apt-get autoremove -y && \
    apt-get update -y

## CURL
RUN apt-get install -y curl

## Timezone
RUN echo "America/Sao_Paulo" > /etc/timezone && \
    apt-get install -y tzdata && \
    rm /etc/localtime && \
    ln -snf /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime && \
    dpkg-reconfigure -f noninteractive tzdata && \
    apt-get clean

## Git
RUN apt-get install -y git

## NGINX
RUN apt-get install -y nginx
COPY ./scripts/build/nginx/app/sites-available /etc/nginx/sites-available
COPY ./scripts/build/nginx/app/sites-available /etc/nginx/sites-enabled

## PHP
RUN apt-get install -y php7.3-cli php7.3-fpm php7.3-curl php7.3-mbstring
COPY ./scripts/build/php/php.ini /etc/php/7.3/fpm/php.ini

# Install libs
RUN apt-get update -y && \
    apt-get install -y php7.3-zip php7.3-mysql php7.3-gd pngquant gifsicle jpegoptim libicu-dev g++ php7.3-intl php7.3-xml

## Supervisor
RUN apt-get install -y supervisor
COPY ./scripts/build/supervisord /etc/supervisor/conf.d
RUN sed -i "s@BACKEND_PATH@$BACKEND_PATH@g" /etc/supervisor/conf.d/supervisord.conf

## Install
COPY ./ ${BACKEND_PATH}

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN rm -f ${BACKEND_PATH}/bootstrap/cache/routes.php && \
    rm -f ${BACKEND_PATH}/bootstrap/cache/packages.php && \
    rm -f ${BACKEND_PATH}/bootstrap/cache/services.php && \
    rm -f ${BACKEND_PATH}/bootstrap/cache/config.php && \
    php /usr/local/bin/composer install --working-dir=${BACKEND_PATH}

# Artisan
RUN chmod -Rf 777 ${BACKEND_PATH}/storage
RUN chmod -Rf 777 ${BACKEND_PATH}/resources
RUN chmod 600 ${BACKEND_PATH}/storage/oauth-*
RUN chown www-data ${BACKEND_PATH}/storage/oauth-*
RUN php ${BACKEND_PATH}/artisan app:deploy
RUN php ${BACKEND_PATH}/artisan cache:clear
RUN php ${BACKEND_PATH}/artisan route:clear

## Run!
EXPOSE 80

RUN service php7.3-fpm start
CMD /etc/init.d/cron start && /usr/bin/supervisord
