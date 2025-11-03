# Plataforma de Videojuegos

## Descripción
Plataforma web para comprar y valorar videojuegos, inspirada en Steam. Los usuarios pueden explorar juegos, ver detalles y dejar reseñas.

## Características Actuales
- Visualización del catálogo de juegos
- Lista de juegos disponibles
- Vista detallada de cada juego
- Sistema básico de navegación

## Tecnologías
- Backend: PHP 8.2+ con Laravel
- Frontend: HTML, CSS, JavaScript, Bootstrap
- Base de datos: MySQL (XAMPP con phpMyAdmin)
- Servidor: XAMPP

## Instalación

1. **Preparar XAMPP**
   - Inicia los servicios de Apache y MySQL desde el panel de control de XAMPP
   - Abre phpMyAdmin en tu navegador: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Crea una nueva base de datos llamada `misteamdb_laravel`

2. Clonar el repositorio:
   ```bash
   git clone [URL_DEL_REPOSITORIO]
   cd ProyectoAlberto-Steam-Laravel
   ```

3. Instalar dependencias de PHP:
   ```bash
   composer install
   ```

4. Configurar el entorno:
   ```bash
   copy .env.example .env
   ```
   Edita el archivo `.env` con los siguientes valores:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=misteamdb_laravel
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Generar clave de la aplicación:
   ```bash
   php artisan key:generate
   ```

6. Ejecutar migraciones y seeders:
   ```bash
   php artisan migrate --seed
   ```
   Esto creará las tablas necesarias y cargará los juegos de ejemplo.

7. Iniciar el servidor de desarrollo:
   ```bash
   php artisan serve
   ```
   La aplicación estará disponible en [http://127.0.0.1:8000](http://127.0.0.1:8000)

## Acceso a la aplicación
- **URL:** [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **phpMyAdmin:** [http://localhost/phpmyadmin](http://localhost/phpmyadmin)

## Próximas Mejoras
- Carrito de compras
- Sistema de búsqueda
- Mejoras en el diseño

---
*Documentación actualizada: Octubre 2025*
*Por Alberto*
