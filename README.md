# KompMind — Kompozitsion fikrlash platformasi

AI-asosidagi o'quv platformasi. PHP 8.1 + MySQL + Anthropic Claude API.

## Talablar
- PHP 8.1+, MySQL 8.0+, Apache (mod_rewrite), Composer

## O'rnatish
```bash
git clone ... && cd kompmind
composer install
cp .env.example .env   # .env ni to'ldiring
mysql -u root -p < database/schema.sql
mysql -u root -p kompmind_db < database/seed.sql
```
Apache `DocumentRoot` ni `public/` ga ko'rsating.

## Test foydalanuvchilar (parol: `password`)
| Email | Rol |
|---|---|
| admin@kompmind.uz | Admin |
| shohruh@kompmind.uz | O'qituvchi |
| talaba@kompmind.uz | Talaba |
