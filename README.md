**Запуск:**
1. Установка docker
2. В файле `docker-compose.yml` меняем бинды для папок (`/home/evgeniy/computer/lab/insta`) на свой локальный путь
3. Если нужно, перебиндить порты для mysql контейнера, если порт 3306 занят
4. `docker-compose up ./docker-compose.yml`
5. `localhosh:80`