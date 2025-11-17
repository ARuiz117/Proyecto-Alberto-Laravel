# Estructura de Base de Datos - Proyecto Alberto Steam

## Descripción General
Sistema de tienda de videojuegos con usuarios, biblioteca personal y reseñas.

---

## Tablas

### 1. **usuarios**
Almacena información de los usuarios registrados.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | BIGINT UNSIGNED | ID único (PK, Auto-increment) |
| nombre | VARCHAR(50) | Nombre de usuario único |
| email | VARCHAR(255) | Email único |
| clave | VARCHAR(255) | Contraseña encriptada (bcrypt) |
| rol | ENUM('user','admin') | Rol del usuario (user o admin) |
| saldo | DECIMAL(10,2) | Saldo disponible para compras |
| created_at | TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | Fecha de última actualización |

**Relaciones:**
- `1 usuario` → `muchas bibliotecas`
- `1 usuario` → `muchas reseñas`
- `1 usuario` → `muchos carritos`

---

### 2. **juegos**
Catálogo de videojuegos disponibles.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | BIGINT UNSIGNED | ID único (PK, Auto-increment) |
| titulo | VARCHAR(100) | Nombre del juego |
| descripcion | TEXT | Descripción detallada |
| precio | DECIMAL(6,2) | Precio del juego |
| imagen_url | VARCHAR(255) | URL de la imagen |
| created_at | TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | Fecha de última actualización |

**Relaciones:**
- `1 juego` → `muchas bibliotecas` (a través de tabla pivote)
- `1 juego` → `muchas reseñas`

---

### 3. **bibliotecas**
Tabla pivote que relaciona usuarios con juegos que poseen.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| usuario_id | BIGINT UNSIGNED | FK a usuarios (parte de PK compuesta) |
| juego_id | BIGINT UNSIGNED | FK a juegos (parte de PK compuesta) |
| created_at | TIMESTAMP | Fecha de compra/adquisición |
| updated_at | TIMESTAMP | Fecha de última actualización |

**Clave Primaria:** Compuesta por `(usuario_id, juego_id)` - Evita duplicados

**Relaciones:**
- `usuario_id` → `usuarios.id` (ON DELETE CASCADE)
- `juego_id` → `juegos.id` (ON DELETE CASCADE)

**Propósito:** Registra qué juegos tiene cada usuario en su biblioteca. La clave primaria compuesta garantiza que un usuario no pueda tener el mismo juego dos veces.

---

### 4. **resenas**
Reseñas y comentarios de usuarios sobre juegos.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | BIGINT UNSIGNED | ID único (PK, Auto-increment) |
| usuario_id | BIGINT UNSIGNED | FK a usuarios |
| juego_id | BIGINT UNSIGNED | FK a juegos |
| contenido | TEXT | Texto de la reseña |
| created_at | TIMESTAMP | Fecha de creación |
| updated_at | TIMESTAMP | Fecha de última actualización |

**Relaciones:**
- `usuario_id` → `usuarios.id` (ON DELETE CASCADE)
- `juego_id` → `juegos.id` (ON DELETE CASCADE)

---

### 5. **carritos**
Carrito de compras de los usuarios.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | BIGINT UNSIGNED | ID único (PK, Auto-increment) |
| usuario_id | BIGINT UNSIGNED | FK a usuarios |
| juego_id | BIGINT UNSIGNED | FK a juegos |
| cantidad | INT | Cantidad de items (normalmente 1) |
| created_at | TIMESTAMP | Fecha de adición al carrito |
| updated_at | TIMESTAMP | Fecha de última actualización |

**Relaciones:**
- `usuario_id` → `usuarios.id`
- `juego_id` → `juegos.id`

---

### 6. **sessions**
Sesiones de usuario (gestión de sesiones).

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | VARCHAR(255) | ID de sesión (PK) |
| user_id | BIGINT UNSIGNED | FK a usuarios |
| ip_address | VARCHAR(45) | IP del cliente |
| user_agent | TEXT | User agent del navegador |
| payload | LONGTEXT | Datos de sesión encriptados |
| last_activity | INT | Timestamp de última actividad |

---

### 7. **cache**
Tabla de caché de la aplicación.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| key | VARCHAR(255) | Clave de caché (PK) |
| value | MEDIUMTEXT | Valor en caché |
| expiration | INT | Timestamp de expiración |

---

### 8. **migrations**
Registro de migraciones ejecutadas.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT UNSIGNED | ID único (PK, Auto-increment) |
| migration | VARCHAR(255) | Nombre de la migración |
| batch | INT | Número de lote |

---

## Diagrama de Relaciones

```
usuarios (1) ──────── (∞) bibliotecas ──────── (1) juegos
    │                                              │
    │                                              │
    └─────────────── (∞) reseñas ────────────────┘
    │
    └─────────────── (∞) carritos ────────────────┐
                                                   │
                                              (1) juegos
```

---

## Datos de Prueba

### Usuarios
- **usuario1** / usuario1 (rol: user, saldo: 100.00)
- **admin1** / admin1 (rol: admin, saldo: 100.00)

### Juegos (10 juegos predefinidos)
1. The Witcher 3: Wild Hunt - $39.99
2. Red Dead Redemption 2 - $59.99
3. Cyberpunk 2077 - $49.99
4. Grand Theft Auto V - $29.99
5. Elden Ring - $59.99
6. Hollow Knight - $14.99
7. Celeste - $19.99
8. Stardew Valley - $13.99
9. Undertale - $9.99
10. Cuphead - $19.99

---

## Instalación/Reinstalación

Para reinstalar completamente la base de datos:

```powershell
# Ejecutar el script de instalación
.\install.ps1
```

O manualmente:

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear

# Recrear base de datos
php artisan migrate:fresh --seed
```

---

## Notas Importantes

- Las contraseñas se almacenan **encriptadas con bcrypt**
- Las relaciones usan **ON DELETE CASCADE** para mantener integridad
- La tabla `bibliotecas` es la relación muchos-a-muchos entre usuarios y juegos
- El `saldo` de usuarios se usa para controlar compras
- Las `reseñas` son independientes de la `biblioteca` (puedes reseñar sin tener el juego)
