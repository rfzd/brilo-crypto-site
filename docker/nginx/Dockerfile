FROM nginx:1.25.3-alpine3.18

COPY "./configs/nginx/nginx.conf" "/etc/nginx/nginx.conf"
COPY "./configs/nginx/conf.d/default.conf" "/etc/nginx/conf.d/default.conf"

WORKDIR /var/www

CMD ["nginx"]
