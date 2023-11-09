FROM php:8.2.12-fpm-alpine3.18 AS shared

COPY --from=mlocati/php-extension-installer:2.1.64 /usr/bin/install-php-extensions /usr/local/bin/

RUN echo "Installing shared dependencies" \
    && apk add --no-cache --virtual .app-php-deps \
        bash=5.2.15-r5 \
    && install-php-extensions \
        intl-3.0.0 \
        opcache \
    && docker-php-source delete \
    && rm -rf /var/cache/apk/* /root/.cache

COPY ./docker/php-fpm/entrypoints/entrypoint.server.sh /
RUN chmod +x /entrypoint.server.sh

COPY ./docker/php-fpm/configs/php.ini ${PHP_INI_DIR}/conf.d/my-app.ini
COPY ./docker/php-fpm/configs/www.conf /usr/local/etc/php-fpm.d/zz-crypto-site.conf

ENV COMPOSER_VERSION="2.6.5"
ENV XDEBUG_VERSION="3.2.2"

RUN echo "Installing dev specific dependencies" \
    && install-php-extensions \
      @composer-"${COMPOSER_VERSION}" \
      xdebug-"${XDEBUG_VERSION}" \
    && rm -rf /var/cache/apk/* /root/.cache

WORKDIR /var/www

CMD ["/entrypoint.server.sh"]
