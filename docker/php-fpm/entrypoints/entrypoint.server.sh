#!/bin/sh
set -e

echo "Starting php-fpm..."
php-fpm -R -F -c /usr/local/etc/php-fpm.conf
