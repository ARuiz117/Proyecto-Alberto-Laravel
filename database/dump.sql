-- ===============================================================
-- Steam HRG - Base de Datos Completa
-- Proyecto CFGS Desarrollo de Aplicaciones Web - I.E.S. Zaidín-Vergeles
-- Autor: Alberto Ruiz González
-- Curso: 2025-2026
-- Fecha: Noviembre 2025
-- ===============================================================
-- Base de datos: misteamdb_laravel
-- Servidor: localhost
-- Versión: MariaDB 10.4.32
-- ===============================================================

-- Configuración inicial de la base de datos
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- ===============================================================
-- TABLA: usuarios - Datos de los usuarios registrados
-- ===============================================================

-- Eliminar tabla si existe para recrearla
DROP TABLE IF EXISTS `usuarios`;

-- Crear estructura de la tabla usuarios
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID único del usuario',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre de usuario',
  `email` varchar(100) NOT NULL COMMENT 'Correo electrónico único',
  `password` varchar(255) NOT NULL COMMENT 'Contraseña encriptada con bcrypt',
  `rol` enum('user','admin') NOT NULL DEFAULT 'user' COMMENT 'Rol del usuario (normal/admin)',
  `saldo` decimal(10,2) NOT NULL DEFAULT '100.00' COMMENT 'Saldo disponible para compras',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de registro',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Última actualización',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de usuarios del sistema Steam HRG';
/*!40101 SET character_set_client = @saved_cs_client */;

-- Insertar usuarios de prueba y datos reales
LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES 
-- Usuarios de prueba para el sistema
(1,'usuario1','usuario1@steamhrg.com','$2y$12$6jLxCRf64SUuY8UfAZOZ8ew9M1Mz6j6dxceZ2LjqNP52x8nonTaT6','user',159.99,'2025-11-17 20:16:43','2025-11-21 11:50:20'),
(2,'admin1','admin1@steamhrg.com','$2y$12$CiykWMcCjkYOe4bWq19lleCM7ynK8FOE.Ge1HTj90/v3yWHAz93OC','admin',450.02,'2025-11-17 20:16:43','2025-11-24 21:25:12'),
-- Usuarios adicionales de prueba
(4,'alberto1','alberto1@gmail.com','$2y$12$TOmUrJi9EylHZOCyh0liPubCjRo1SU0kcdZDnS3a0tFXr0J/HsOxK','user',100.00,'2025-11-18 16:18:51','2025-11-18 16:18:51'),
(6,'usuario2','usuario2@gmail.com','$2y$12$kzVQNN.tadjxWXcBdXEL9.SZafkvz1/.XK5z61qg.HmSyp8lydny.','user',60.01,'2025-11-20 14:21:33','2025-11-20 14:26:49');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

-- ===============================================================
-- TABLA: juegos - Catálogo de videojuegos disponibles
-- ===============================================================

DROP TABLE IF EXISTS `juegos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `juegos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID único del juego',
  `titulo` varchar(200) NOT NULL COMMENT 'Título del videojuego',
  `descripcion` text COMMENT 'Descripción detallada del juego',
  `precio` decimal(10,2) NOT NULL COMMENT 'Precio en euros',
  `genero` enum('Acción','RPG','Terror','Estrategia','Aventura','Deportes','Puzzle','Simulación') NOT NULL COMMENT 'Género del juego',
  `imagen_url` varchar(255) DEFAULT NULL COMMENT 'Nombre del archivo de imagen',
  `steam_id` int DEFAULT NULL COMMENT 'ID de Steam para API externa',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de creación',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Última actualización',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catálogo de juegos Steam HRG';
/*!40101 SET character_set_client = @saved_cs_client */;

-- Insertar catálogo de juegos predefinidos
LOCK TABLES `juegos` WRITE;
/*!40000 ALTER TABLE `juegos` DISABLE KEYS */;
INSERT INTO `juegos` VALUES 
-- RPGs
(1,'The Witcher 3: Wild Hunt','Aventura épica de fantasía con un mundo abierto impresionante y decisiones que impactan la historia.',59.99,'RPG','THE_WITCHER_3_WILD_HUNT.jpg',292030,'2025-11-17 20:16:43','2025-11-17 20:16:43'),
(2,'Elden Ring','RPG de acción de mundo abierto creado por FromSoftware en colaboración con George R.R. Martin.',69.99,'RPG','ELDEN_RING.jpg',1245620,'2025-11-17 20:16:43','2025-11-17 20:16:43'),
-- Acción
(3,'Grand Theft Auto V','Juego de mundo abierto donde puedes explorar Los Santos y vivir múltiples vidas.',39.99,'Acción','GTA_V.jpg',271590,'2025-11-17 20:16:43','2025-11-17 20:16:43'),
(4,'Cyberpunk 2077','RPG de acción en un futuro distópico con gráficos impresionantes y decisiones morales.',49.99,'Acción','CYBERPUNK_2077.jpg',1091500,'2025-11-17 20:16:43','2025-11-17 20:16:43'),
-- Terror
(5,'Resident Evil Village','Terror de supervivencia con vampiros y monstruos en un pueblo aislado.',45.99,'Terror','RESIDENT_EVIL_VILLAGE.jpg',1196590,'2025-11-17 20:16:43','2025-11-17 20:16:43'),
(6,'Amnesia: Rebirth','Terror psicológico que te mantendrá en tensión constante.',29.99,'Terror','AMNESIA_REBIRTH.jpg',1129380,'2025-11-17 20:16:43','2025-11-17 20:16:43'),
-- Estrategia
(7,'Civilization VI','Juego de estrategia por turnos donde construyes un imperio desde la antigüedad.',34.99,'Estrategia','CIVILIZATION_VI.jpg',289070,'2025-11-17 20:16:43','2025-11-17 20:16:43'),
(8,'StarCraft II','Estrategia en tiempo real con tres facciones únicas y batallas épicas.',19.99,'Estrategia','STARCRAFT_II.jpg',36346,'2025-11-17 20:16:43','2025-11-17 20:16:43'),
-- Deportes
(9,'FIFA 24','Simulación de fútbol con los mejores jugadores y equipos del mundo.',59.99,'Deportes','FIFA_24.jpg',1811260,'2025-11-17 20:16:43','2025-11-17 20:16:43'),
-- Puzzle
(10,'Portal 2','Juego de puzles con portales que desafían tu mente y tu lógica.',19.99,'Puzzle','PORTAL_2.jpg',620,'2025-11-17 20:16:43','2025-11-17 20:16:43');
/*!40000 ALTER TABLE `juegos` ENABLE KEYS */;
UNLOCK TABLES;

-- ===============================================================
-- TABLA: bibliotecas - Juegos comprados por cada usuario
-- ===============================================================

DROP TABLE IF EXISTS `bibliotecas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bibliotecas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID único de la relación',
  `usuario_id` bigint(20) unsigned NOT NULL COMMENT 'ID del usuario que compró',
  `juego_id` bigint(20) unsigned NOT NULL COMMENT 'ID del juego comprado',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de compra',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Última actualización',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bibliotecas_usuario_id_juego_id_unique` (`usuario_id`,`juego_id`) COMMENT 'Evita compras duplicadas',
  KEY `bibliotecas_juego_id_foreign` (`juego_id`),
  CONSTRAINT `bibliotecas_juego_id_foreign` FOREIGN KEY (`juego_id`) REFERENCES `juegos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bibliotecas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bibliotecas personales de usuarios';
/*!40101 SET character_set_client = @saved_cs_client */;

-- Insertar compras realizadas por usuarios
LOCK TABLES `bibliotecas` WRITE;
/*!40000 ALTER TABLE `bibliotecas` DISABLE KEYS */;
INSERT INTO `bibliotecas` VALUES 
-- Compras de usuario2
(24,6,1,'2025-11-20 14:26:49','2025-11-20 14:26:49'),
-- Compras de usuario1
(29,1,2,'2025-11-21 11:50:20','2025-11-21 11:50:20'),
(30,1,3,'2025-11-21 11:52:40','2025-11-21 11:52:40'),
-- Compras de admin1
(31,2,2,'2025-11-21 15:14:54','2025-11-21 15:14:54'),
(32,2,3,'2025-11-21 15:53:36','2025-11-21 15:53:36');
/*!40000 ALTER TABLE `bibliotecas` ENABLE KEYS */;
UNLOCK TABLES;

-- ===============================================================
-- TABLA: carritos - Carritos de compras activos
-- ===============================================================

DROP TABLE IF EXISTS `carritos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carritos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID único del item en carrito',
  `usuario_id` bigint(20) unsigned NOT NULL COMMENT 'ID del usuario',
  `juego_id` bigint(20) unsigned NOT NULL COMMENT 'ID del juego en carrito',
  `cantidad` int NOT NULL DEFAULT '1' COMMENT 'Cantidad (siempre 1 para juegos digitales)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de agregado',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Última actualización',
  PRIMARY KEY (`id`),
  KEY `carritos_juego_id_foreign` (`juego_id`),
  CONSTRAINT `carritos_juego_id_foreign` FOREIGN KEY (`juego_id`) REFERENCES `juegos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carritos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Carritos de compras pendientes';
/*!40101 SET character_set_client = @saved_cs_client */;

-- Insertar items en carritos activos
LOCK TABLES `carritos` WRITE;
/*!40000 ALTER TABLE `carritos` DISABLE KEYS */;
INSERT INTO `carritos` VALUES 
(1,1,5,1,'2025-11-21 11:52:00','2025-11-21 11:52:00'),
(2,1,6,1,'2025-11-21 11:52:05','2025-11-21 11:52:05'),
(3,2,4,1,'2025-11-21 15:53:25','2025-11-21 15:53:25'),
(4,2,5,1,'2025-11-21 15:53:30','2025-11-21 15:53:30'),
(5,4,1,1,'2025-11-18 16:18:51','2025-11-18 16:18:51'),
(6,6,2,1,'2025-11-20 14:21:33','2025-11-20 14:21:33');
/*!40000 ALTER TABLE `carritos` ENABLE KEYS */;
UNLOCK TABLES;

-- ===============================================================
-- TABLA: reseñas - Valoraciones y comentarios de usuarios
-- ===============================================================

DROP TABLE IF EXISTS `resenas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resenas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID único de la reseña',
  `usuario_id` bigint(20) unsigned NOT NULL COMMENT 'ID del usuario que reseña',
  `juego_id` bigint(20) unsigned NOT NULL COMMENT 'ID del juego reseñado',
  `valoracion` int NOT NULL COMMENT 'Puntuación de 1 a 5 estrellas',
  `comentario` text NOT NULL COMMENT 'Comentario detallado del usuario',
  `recomendado` tinyint(1) NOT NULL COMMENT '¿Recomienda el juego? (1=Sí, 0=No)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de la reseña',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Última actualización',
  PRIMARY KEY (`id`),
  KEY `resenas_juego_id_foreign` (`juego_id`),
  CONSTRAINT `resenas_juego_id_foreign` FOREIGN KEY (`juego_id`) REFERENCES `juegos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `resenas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Reseñas y valoraciones de usuarios';
/*!40101 SET character_set_client = @saved_cs_client */;

-- Insertar reseñas de usuarios
LOCK TABLES `resenas` WRITE;
/*!40000 ALTER TABLE `resenas` DISABLE KEYS */;
INSERT INTO `resenas` VALUES 
(1,1,2,5,'Juego absolutamente increíble, los gráficos son espectaculares y la jugabilidad es adictiva.',1,'2025-11-21 11:52:50','2025-11-21 11:52:50'),
(2,1,3,4,'Muy bueno, aunque un poco repetitivo después de muchas horas. Recomendado.',1,'2025-11-21 11:53:00','2025-11-21 11:53:00'),
(3,2,2,5,'El mejor RPG que he jugado en años. La historia es increíble.',1,'2025-11-21 15:14:00','2025-11-21 15:14:00'),
(4,2,3,3,'Está bien, pero esperaba más. Los gráficos podrían ser mejores.',0,'2025-11-21 15:53:45','2025-11-21 15:53:45'),
(5,4,1,4,'Muy divertido, aunque un poco complicado al principio.',1,'2025-11-18 16:19:00','2025-11-18 16:19:00'),
(6,6,2,5,'Perfecto. Jugaré durante cientos de horas.',1,'2025-11-20 14:22:00','2025-11-20 14:22:00'),
(7,1,5,3,'Terror psicológico bien hecho, pero muy corto.',1,'2025-11-21 11:54:00','2025-11-21 11:54:00');
/*!40000 ALTER TABLE `resenas` ENABLE KEYS */;
UNLOCK TABLES;

-- ===============================================================
-- TABLAS DEL SISTEMA (Laravel)
-- ===============================================================

-- Tabla de caché del sistema
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL COMMENT 'Clave de caché',
  `value` mediumtext COMMENT 'Valor almacenado',
  `expiration` int NOT NULL COMMENT 'Tiempo de expiración',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- Tabla de sesiones de usuarios
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL COMMENT 'ID único de sesión',
  `user_id` bigint(20) unsigned DEFAULT NULL COMMENT 'ID del usuario (si está logueado)',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'Dirección IP del usuario',
  `user_agent` text COMMENT 'Navegador del usuario',
  `payload` longtext NOT NULL COMMENT 'Datos de sesión serializados',
  `last_activity` int NOT NULL COMMENT 'Última actividad timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- Tabla de registro de migraciones
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID único',
  `migration` varchar(255) NOT NULL COMMENT 'Nombre del archivo de migración',
  `batch` int NOT NULL COMMENT 'Número de batch ejecutado',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- ===============================================================
-- RESTAURAR CONFIGURACIÓN ORIGINAL
-- ===============================================================

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- ===============================================================
-- DUMP COMPLETADO - Steam HRG
-- Total de tablas: 8
-- Total de usuarios: 4
-- Total de juegos: 10
-- Total de compras: 5
-- Total de reseñas: 7
-- Generado por: Alberto Ruiz González
-- Fecha: Noviembre 2025
-- ===============================================================
