# Guía de Instalación y Uso - Proyecto Alberto Steam

## Requisitos Previos

- XAMPP instalado (Apache, MySQL/MariaDB, PHP)
- Composer instalado
- Git instalado
- PowerShell (Windows)

---

## Instalación Rápida

### 1. Clonar o descargar el proyecto
```bash
cd c:\xampp\htdocs
git clone https://github.com/ARuiz117/Proyecto-Alberto-Laravel.git
cd Proyecto-Alberto-Laravel
```

### 2. Instalar dependencias
```bash
composer install
```

### 3. Configurar variables de entorno
```bash
# Copiar archivo de ejemplo
copy .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 4. Ejecutar instalación completa
```powershell
# Ejecutar script de instalación (Windows PowerShell)
.\install.ps1
```

O manualmente:
```bash
php artisan migrate:fresh --seed
php artisan cache:clear
php artisan config:clear
```

### 5. Iniciar la aplicación
```bash
php artisan serve
```

Accede a: `http://127.0.0.1:8000`

---

## Credenciales de Prueba

### Usuario Regular
- **Usuario:** usuario1
- **Contraseña:** usuario1
- **Email:** usuario1@steamhrg.com

### Administrador
- **Usuario:** admin1
- **Contraseña:** admin1
- **Email:** admin1@steamhrg.com

---

## Estructura del Proyecto

```
Proyecto-Alberto-Laravel/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php      # Autenticación
│   │       ├── BibliotecaController.php # Biblioteca del usuario
│   │       ├── JuegoController.php     # Catálogo de juegos
│   │       └── ...
│   └── Models/
│       ├── Usuario.php                 # Modelo de usuario
│       ├── Juego.php                   # Modelo de juego
│       ├── Biblioteca.php              # Relación usuario-juego
│       ├── Resena.php                  # Reseñas
│       └── Carrito.php                 # Carrito de compras
├── database/
│   ├── migrations/                     # Migraciones de BD
│   └── seeders/
│       ├── UsuarioSeeder.php           # Crea usuarios de prueba
│       └── MisteamImportSeeder.php     # Crea juegos de prueba
├── resources/
│   └── views/                          # Vistas Blade
│       ├── auth/                       # Login y registro
│       ├── biblioteca/                 # Biblioteca del usuario
│       ├── juegos/                     # Catálogo de juegos
│       └── ...
├── routes/
│   └── web.php                         # Rutas de la aplicación
├── .env                                # Variables de entorno
├── ESTRUCTURA_BD.md                    # Documentación de BD
├── install.ps1                         # Script de instalación
└── README.md                           # Documentación general
```

---

## Configuración de Base de Datos

El archivo `.env` debe contener:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=misteamdb_laravel
DB_USERNAME=root
DB_PASSWORD=
```

---

## Comandos Útiles

### Migraciones
```bash
# Ejecutar todas las migraciones
php artisan migrate

# Revertir última migración
php artisan migrate:rollback

# Recrear BD desde cero con seeders
php artisan migrate:fresh --seed

# Ver estado de migraciones
php artisan migrate:status
```

### Seeders
```bash
# Ejecutar todos los seeders
php artisan db:seed

# Ejecutar un seeder específico
php artisan db:seed --class=UsuarioSeeder
```

### Caché
```bash
# Limpiar caché de aplicación
php artisan cache:clear

# Limpiar configuración en caché
php artisan config:clear

# Limpiar vistas compiladas
php artisan view:clear

# Limpiar todo
php artisan optimize:clear
```

### Servidor
```bash
# Iniciar servidor de desarrollo
php artisan serve

# Iniciar en puerto específico
php artisan serve --port=8001
```

---

## Solución de Problemas

### Error: "Base table or view not found"
**Solución:** Ejecutar migraciones
```bash
php artisan migrate:fresh --seed
```

### Error: "Access denied for user 'root'"
**Solución:** Verificar credenciales en `.env` y que MySQL esté corriendo

### Error: "Class not found"
**Solución:** Ejecutar composer
```bash
composer install
composer dump-autoload
```

### Error: "SQLSTATE[HY000]: General error"
**Solución:** Limpiar caché y reinstalar
```bash
php artisan optimize:clear
php artisan migrate:fresh --seed
```

---

## Acceso a phpMyAdmin

1. Asegúrate de que Apache y MySQL estén corriendo en XAMPP
2. Accede a: `http://localhost/phpmyadmin`
3. Usuario: `root`
4. Contraseña: (vacía)

---

## Desarrollo

### Crear un nuevo modelo con migración
```bash
php artisan make:model NombreModelo -m
```

### Crear un nuevo controlador
```bash
php artisan make:controller NombreController
```

### Crear un nuevo seeder
```bash
php artisan make:seeder NombreSeeder
```

---

## Notas Importantes

- **Nunca** subas el archivo `.env` a Git (está en `.gitignore`)
- Las contraseñas se almacenan encriptadas con bcrypt
- Las migraciones son versionadas en Git
- Los seeders son para datos de prueba
- Usa `php artisan tinker` para debugging interactivo

---

## Contacto y Soporte

Para problemas o preguntas, consulta la documentación en:
- `ESTRUCTURA_BD.md` - Estructura de base de datos
- `README.md` - Documentación general del proyecto
