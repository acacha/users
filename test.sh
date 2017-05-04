#!/bin/bash
./sandbox_setup.sh
cd ../sandbox
php artisan dusk:install
php artisan serve --env=dusk.local &
php artisan dusk
php artisan adminlte:admin
#./vendor/bin/phpunit --coverage-text --coverage-clover=../coverage.clover
./vendor/bin/phpunit tests/Feature/UsersManagementTest.php --coverage-text --coverage-clover=../coverage.clover
#./vendor/bin/phpunit --group=failing
cd ..
./sandbox_destroy.sh

