Steam HRG - Tienda de Videojuegos Laravel

COMO ARRANCAR EL PROYECTO

Requisitos Previos
- PHP 8.2+
- Composer
- Servidor web (Apache/Nginx) o usar PHP Artisan Serve

Pasos para poner en marcha:

1. Instalar Dependencias
   composer install

2. Configurar entorno
   copy .env.profesor .env
   php artisan key:generate

3. Configurar base de datos
   # SQLite viene configurado por defecto
   php artisan migrate
   php artisan db:seed

4. Iniciar servidor
   php artisan serve

5. Acceder a la aplicacion
   - URL: http://localhost:8000
   - Usuario admin: admin1 / admin1
   - Usuario normal: usuario1 / usuario1