# Arquitectura del Proyecto - Proyecto Alberto Steam

## 📊 Modelo de Datos Optimizado

### Entidades Principales

```
┌─────────────────────────────────────────────────────────────────┐
│                         USUARIOS                                │
├─────────────────────────────────────────────────────────────────┤
│ • id (PK)                                                       │
│ • nombre (único)                                                │
│ • email (único)                                                 │
│ • clave (encriptada)                                            │
│ • rol (user | admin)                                            │
│ • saldo (decimal)                                               │
│ • timestamps                                                    │
└─────────────────────────────────────────────────────────────────┘
         │                    │                    │
         │                    │                    │
    (1:∞)│              (1:∞)│              (1:∞)│
         │                    │                    │
         ▼                    ▼                    ▼
   ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
   │ BIBLIOTECAS  │  │   RESEÑAS    │  │  CARRITOS    │
   │  (Pivote)    │  │              │  │              │
   └──────────────┘  └──────────────┘  └──────────────┘
         │                    │                    │
    (∞:1)│              (∞:1)│              (∞:1)│
         │                    │                    │
         └────────────────────┴────────────────────┘
                              │
                         (1:∞)│
                              ▼
                    ┌──────────────────┐
                    │     JUEGOS       │
                    ├──────────────────┤
                    │ • id (PK)        │
                    │ • titulo         │
                    │ • descripcion    │
                    │ • precio         │
                    │ • imagen_url     │
                    │ • timestamps     │
                    └──────────────────┘
```

---

## 📋 Tablas Detalladas

### 1. **usuarios** - Tabla Principal
Almacena información de los usuarios del sistema.

```sql
CREATE TABLE usuarios (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,              -- Encriptada con bcrypt
    rol ENUM('user','admin') DEFAULT 'user',
    saldo DECIMAL(10,2) DEFAULT 100.00,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Relaciones:**
- `1 usuario` → `∞ bibliotecas` (juegos que posee)
- `1 usuario` → `∞ reseñas` (comentarios que escribió)
- `1 usuario` → `∞ carritos` (items en carrito)

---

### 2. **juegos** - Tabla Principal
Catálogo de videojuegos disponibles.

```sql
CREATE TABLE juegos (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(6,2) DEFAULT 19.99,
    imagen_url VARCHAR(255),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Relaciones:**
- `1 juego` → `∞ bibliotecas` (usuarios que lo poseen)
- `1 juego` → `∞ reseñas` (comentarios que recibe)
- `1 juego` → `∞ carritos` (en carritos de usuarios)

---

### 3. **bibliotecas** - Tabla Pivote (Relación M:M)
Relaciona usuarios con juegos que poseen.

```sql
CREATE TABLE bibliotecas (
    usuario_id BIGINT UNSIGNED NOT NULL,
    juego_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    PRIMARY KEY (usuario_id, juego_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (juego_id) REFERENCES juegos(id) ON DELETE CASCADE
);
```

**Características:**
- **Clave Primaria Compuesta:** `(usuario_id, juego_id)` evita duplicados
- **Sin ID propio:** Es una tabla pivote pura
- **Timestamps:** Registra cuándo se agregó el juego a la biblioteca

**Propósito:** Responde a "¿Qué juegos tiene este usuario?"

---

### 4. **reseñas** - Tabla de Comentarios
Reseñas y comentarios de usuarios sobre juegos.

```sql
CREATE TABLE resenas (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    usuario_id BIGINT UNSIGNED NOT NULL,
    juego_id BIGINT UNSIGNED NOT NULL,
    contenido TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (juego_id) REFERENCES juegos(id) ON DELETE CASCADE
);
```

**Características:**
- **ID propio:** Cada reseña es una entidad independiente
- **Relación 1:1 lógica:** Un usuario puede escribir múltiples reseñas

**Propósito:** Almacena comentarios de usuarios sobre juegos

---

### 5. **carritos** - Carrito de Compras
Items en el carrito de compras de usuarios.

```sql
CREATE TABLE carritos (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    usuario_id BIGINT UNSIGNED NOT NULL,
    juego_id BIGINT UNSIGNED NOT NULL,
    cantidad INT DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (juego_id) REFERENCES juegos(id) ON DELETE CASCADE
);
```

**Características:**
- **ID propio:** Cada item del carrito es identificable
- **Cantidad:** Permite múltiples unidades (aunque normalmente es 1 para juegos)

**Propósito:** Almacena items pendientes de compra

---

## 🔄 Flujos de Datos

### Flujo 1: Usuario compra un juego
```
1. Usuario agrega juego al CARRITO
   carrito: (usuario_id=1, juego_id=5, cantidad=1)

2. Usuario procesa la compra
   - Se resta del saldo: usuarios.saldo -= juego.precio
   - Se agrega a BIBLIOTECA: bibliotecas (usuario_id=1, juego_id=5)
   - Se elimina del CARRITO

3. Resultado: El juego aparece en la biblioteca del usuario
```

### Flujo 2: Usuario escribe una reseña
```
1. Usuario accede a un juego que posee
   - Verifica: bibliotecas (usuario_id=1, juego_id=5) existe

2. Usuario escribe comentario
   - Se crea: resenas (usuario_id=1, juego_id=5, contenido="...")

3. Resultado: La reseña aparece en el juego
```

### Flujo 3: Eliminar usuario
```
1. DELETE FROM usuarios WHERE id=1

2. Cascada automática:
   - Elimina: bibliotecas (usuario_id=1, ...)
   - Elimina: reseñas (usuario_id=1, ...)
   - Elimina: carritos (usuario_id=1, ...)

3. Resultado: Integridad referencial mantenida
```

---

## 🎯 Decisiones de Diseño

### ✅ Tabla Pivote sin ID
**Razón:** `bibliotecas` es una relación pura M:M
- No necesita ID propio
- Clave primaria compuesta evita duplicados
- Más eficiente en queries

### ✅ Timestamps en Todas las Tablas
**Razón:** Auditoría y tracking
- `created_at`: Cuándo se creó el registro
- `updated_at`: Cuándo se modificó por última vez

### ✅ Encriptación de Contraseñas
**Razón:** Seguridad
- Se usa bcrypt (algoritmo de hash seguro)
- Nunca se almacena en texto plano
- No es reversible

### ✅ Rol de Usuario (user | admin)
**Razón:** Control de acceso
- `user`: Usuario normal
- `admin`: Administrador del sistema

### ✅ Saldo de Usuario
**Razón:** Control de compras
- Decimal(10,2) para precisión monetaria
- Se resta al comprar juegos
- Se suma al recibir reembolsos

---

## 📊 Estadísticas de Integridad

### Restricciones Implementadas
- ✅ Claves primarias en todas las tablas
- ✅ Claves foráneas con ON DELETE CASCADE
- ✅ Campos UNIQUE donde corresponde
- ✅ Valores NOT NULL en campos críticos
- ✅ Tipos de datos apropiados

### Índices Automáticos
- ✅ PK en todas las tablas
- ✅ FK en todas las relaciones
- ✅ UNIQUE en nombre y email de usuarios

### Tablas Implementadas
- ✅ usuarios
- ✅ juegos
- ✅ bibliotecas (tabla pivote)
- ✅ reseñas
- ✅ carritos
- ✅ sessions (Laravel)
- ✅ cache (Laravel)
- ✅ migrations (Laravel)

---

## 🔐 Seguridad

### Protecciones Implementadas
1. **Contraseñas:** Encriptadas con bcrypt
2. **Integridad Referencial:** ON DELETE CASCADE
3. **Validación:** En modelos Eloquent
4. **Autenticación:** Sistema de sesiones
5. **Autorización:** Roles (user/admin)

---

## 📈 Escalabilidad

### Preparado para Crecer
- Índices en claves foráneas
- Tipos de datos apropiados (BIGINT UNSIGNED)
- Estructura normalizada
- Relaciones bien definidas

### Posibles Extensiones
- Tabla de `transacciones` para auditoría de compras
- Tabla de `categorias` para agrupar juegos
- Tabla de `reviews_likes` para votos en reseñas
- Tabla de `wishlist` para deseos

---

## 🛠️ Mantenimiento

### Backups Recomendados
```bash
# Backup completo
mysqldump -u root misteamdb_laravel > backup.sql

# Backup con compresión
mysqldump -u root misteamdb_laravel | gzip > backup.sql.gz
```

### Verificación de Integridad
```bash
# Verificar tablas
mysqlcheck -u root misteamdb_laravel

# Reparar si es necesario
mysqlcheck -u root --repair misteamdb_laravel
```

---

## 📚 Referencias

- **Modelo Relacional:** Normalización 3NF
- **Patrón:** Tabla Pivote para relaciones M:M
- **Framework:** Laravel Eloquent ORM
- **Base de Datos:** MySQL/MariaDB
