# DEMO_PROJECT
## Структура

### ./etc
Данная папка является хранилищем конфигурационных файлов для настройки серверного окружения для Docker.

### ./data
Данная папка является хранилищем-копией базы данных внутри контейнера pgsql. 

Благодаря такому хранилищу при сбросе и ребилде контейнера pgsql данные не теряются.

### ./web
Корневая папка самого проекта и является еквивалентом /var/www/html

### .env 
Данный файл является хранилищем переменных для первичной сборки докер контейнеров с нужными параметрами.

Хранит в себе переменные:
 - Базы данных
 - Версии PHP
 - Префикса для наименования контейнеров
 - Наименования хоста в nginx

### docker-compose.yml
Хранит в себе готовые настройки для быстрого разворота проекта с нужным окружением.

В текущем проекте окружение состоит из:
- nginx:1.20.1-alpine
- php:8.1
- adminer
- pgsql:latest

## Описание проекта

