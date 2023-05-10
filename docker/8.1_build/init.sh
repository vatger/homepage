#!/bin/bash

cd /var/www/${URL}

# install composer.json
# composer install
# install and build package.json
# npm install && npm run dev

# start php-fpm service
service php8.1-fpm start
service redis-server start


# Start the nginx service
nginx -g 'daemon off;'

./deploy.sh init --dev