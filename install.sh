#!/bin/sh
chmod -R 777 runtime
chmod -R 777 web/assets
chmod -R 777 puplic_html/upload
chmod 777 ./composer.phar
./composer.phar self-update
./composer.phar update