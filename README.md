<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## ProyectoAlberto-Steam-Laravel

Aplicación Laravel para gestionar juegos y reseñas.

## Requisitos

- PHP 8.2+
- Composer
- MySQL (XAMPP o similar)
- Node.js (para assets opcional)

## Instalación rápida

```bash
composer install
cp .env.example .env   # o copia manual
php artisan key:generate

# Configura .env con tu BD:
# DB_DATABASE=misteamdb_laravel
# DB_USERNAME=root
# DB_PASSWORD=

php artisan migrate --seed
```

## Base de datos

- Dump disponible en `database/dumps/misteamdb_laravel.sql`.
- Importe el archivo en una BD llamada `misteamdb_laravel` (p. ej., phpMyAdmin).

## Desarrollo

```bash
php artisan serve
# npm run dev  # si usas Vite/Tailwind
```

## Licencia

MIT.
