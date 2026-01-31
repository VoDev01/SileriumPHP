#!/bin/bash

php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan migrate:fresh --seed
rm -rf node_modules/ 
npm install
npm run build

exec "$@"