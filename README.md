
# 模擬案件_フリマアプリ

## 環境構築

## Dockerビルド

```
git clone https://github.com/sekirubeg/keita-free.git
docker-compose up -d --build
```

※MySQL は OS によって起動しない場合があります。必要に応じて docker-compose.yml を各自の環境に合わせて編集してください。

## Laravel 環境構築

```
docker-compose exec php bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```
## stripeによる決済
Stripeテストモードでの決済が可能です。必要に応じて .env に以下のように設定してください：

```
STRIPE_KEY=your_test_key
STRIPE_SECRET=your_test_secret
```

## 使用技術
```
PHP 8.2.28
Laravel 11.44.2
MySQL 8.0.40
```
## URL
```
開発環境: http://localhost/
phpMyAdmin: http://localhost:8080/
```
## ER図
![ER図](src/ER.png)