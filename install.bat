@echo off
REM Script de instalación para Proyecto Alberto Steam Laravel
REM Este script reinicia completamente la aplicación

echo.
echo ================================
echo Instalación - Proyecto Alberto
echo ================================
echo.

REM 1. Limpiar caché de Laravel
echo [1/5] Limpiando caché de Laravel...
php artisan cache:clear
php artisan config:clear
php artisan view:clear
echo OK - Caché limpiado
echo.

REM 2. Eliminar base de datos y recrearla
echo [2/5] Recreando base de datos...
cd C:\xampp\mysql\bin
mysql -u root -e "DROP DATABASE IF EXISTS misteamdb_laravel; CREATE DATABASE misteamdb_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
cd %~dp0
echo OK - Base de datos recreada
echo.

REM 3. Ejecutar migraciones
echo [3/5] Ejecutando migraciones...
php artisan migrate:fresh
echo OK - Migraciones completadas
echo.

REM 4. Ejecutar seeders
echo [4/5] Insertando datos de prueba...
php artisan db:seed
echo OK - Datos insertados
echo.

REM 5. Limpiar caché nuevamente
echo [5/5] Limpiando caché final...
php artisan cache:clear
php artisan config:clear
echo OK - Caché limpiado
echo.

echo ================================
echo OK - Instalación completada
echo ================================
echo.
echo Credenciales de prueba:
echo   Usuario: usuario1
echo   Contraseña: usuario1
echo.
echo   Admin: admin1
echo   Contraseña: admin1
echo.
echo Inicia la aplicación con: php artisan serve
echo.
pause
