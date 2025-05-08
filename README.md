# keita-free

# 確認テスト_もぎたて

## 環境構築

## Dockerビルド

git clone https://github.com/sekirubeg/keita_kadai.git
docker-compose up -d --build

※MySQL は OS によって起動しない場合があります。必要に応じて docker-compose.yml を各自の環境に合わせて編集してください。

## Laravel 環境構築

docker-compose exec php bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link

## 使用技術

PHP 8.0
Laravel 10.0
MySQL 8.0

## URL
開発環境: http://localhost/

phpMyAdmin: http://localhost:8080/