cd /d %~dp0/../
set workdir="%cd%"
docker run --name toolbox -v %workdir%:/var/www/html  --rm -d registry.aliyuncs.com/syncxplus/php:7.1.7
docker exec -it toolbox composer install
docker exec -it toolbox php download_model.php
docker stop toolbox
