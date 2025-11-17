# Script de instalación para Proyecto Alberto Steam Laravel
# Este script reinicia completamente la aplicación

Write-Host "================================" -ForegroundColor Cyan
Write-Host "Instalación - Proyecto Alberto" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

# 1. Limpiar caché de Laravel
Write-Host "[1/5] Limpiando caché de Laravel..." -ForegroundColor Yellow
php artisan cache:clear
php artisan config:clear
php artisan view:clear
Write-Host "✓ Caché limpiado" -ForegroundColor Green
Write-Host ""

# 2. Eliminar base de datos y recrearla
Write-Host "[2/5] Recreando base de datos..." -ForegroundColor Yellow
cmd /c mysql -u root -e "DROP DATABASE IF EXISTS misteamdb_laravel; CREATE DATABASE misteamdb_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
Write-Host "✓ Base de datos recreada" -ForegroundColor Green
Write-Host ""

# 3. Ejecutar migraciones
Write-Host "[3/5] Ejecutando migraciones..." -ForegroundColor Yellow
php artisan migrate:fresh
Write-Host "✓ Migraciones completadas" -ForegroundColor Green
Write-Host ""

# 4. Ejecutar seeders
Write-Host "[4/5] Insertando datos de prueba..." -ForegroundColor Yellow
php artisan db:seed
Write-Host "✓ Datos insertados" -ForegroundColor Green
Write-Host ""

# 5. Limpiar caché nuevamente
Write-Host "[5/5] Limpiando caché final..." -ForegroundColor Yellow
php artisan cache:clear
php artisan config:clear
Write-Host "✓ Caché limpiado" -ForegroundColor Green
Write-Host ""

Write-Host "================================" -ForegroundColor Green
Write-Host "✓ Instalación completada" -ForegroundColor Green
Write-Host "================================" -ForegroundColor Green
Write-Host ""
Write-Host "Credenciales de prueba:" -ForegroundColor Cyan
Write-Host "  Usuario: usuario1" -ForegroundColor White
Write-Host "  Contraseña: usuario1" -ForegroundColor White
Write-Host ""
Write-Host "  Admin: admin1" -ForegroundColor White
Write-Host "  Contraseña: admin1" -ForegroundColor White
Write-Host ""
Write-Host "Inicia la aplicación con: php artisan serve" -ForegroundColor Cyan
