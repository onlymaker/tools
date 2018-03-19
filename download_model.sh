#!/usr/bin/env bash
docker run --name toolbox -v $(cd `dirname $0`; pwd):/var/www/html  --rm -d registry.aliyuncs.com/syncxplus/php:7.1.14
docker exec -it toolbox composer install
docker exec -it toolbox php download_model.php
docker stop toolbox
