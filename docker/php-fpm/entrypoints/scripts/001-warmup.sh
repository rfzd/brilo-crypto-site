#!/bin/sh

echo "Warming up cache..."

php /var/www/bin/console cache:warmup --env "${APP_ENV}" --no-debug --no-interaction
