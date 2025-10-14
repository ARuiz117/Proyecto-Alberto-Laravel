-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-10-2025 a las 23:17:52
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `misteamdb_laravel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bibliotecas`
--

CREATE TABLE `bibliotecas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `juego_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `bibliotecas`
--

INSERT INTO `bibliotecas` (`id`, `usuario_id`, `juego_id`, `created_at`, `updated_at`) VALUES
(23, 1, 1, '2025-10-14 18:02:22', '2025-10-14 18:02:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegos`
--

CREATE TABLE `juegos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(6,2) NOT NULL DEFAULT 19.99,
  `imagen_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `juegos`
--

INSERT INTO `juegos` (`id`, `titulo`, `descripcion`, `precio`, `imagen_url`, `created_at`, `updated_at`) VALUES
(1, 'The Witcher 3: Wild Hunt', 'Un épico RPG de mundo abierto donde juegas como Geralt de Rivia, un cazador de monstruos en busca de su hija adoptiva.', 39.99, 'https://imgs.search.brave.com/U7HXX0NII5pcwVNRfh_erT2DvGkVYC3o2StcsaPY224/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9waWNz/LmZpbG1hZmZpbml0/eS5jb20vd2llZHpt/aW5fM19kemlraV9n/b24tMzU4NDk0NTQ3/LW1tZWQuanBn', '2025-10-14 15:48:57', '2025-10-14 15:48:57'),
(2, 'Red Dead Redemption 2', 'Una aventura de acción en el salvaje oeste con una historia profunda y un mundo abierto impresionante.', 59.99, 'https://imgs.search.brave.com/Vtg5ceu8xxVhdClvzhXBjwJvfM2YJMgFAmmIdFWx6SQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLmJs/b2dzLmVzL2NlZWRl/NC9ibG9iL29yaWdp/bmFsLmpwZWc', '2025-10-14 15:48:57', '2025-10-14 15:48:57'),
(3, 'Cyberpunk 2077', 'Un RPG futurista en Night City, donde tus decisiones afectan la historia y el mundo.', 49.99, 'https://imgs.search.brave.com/lMIOieYZW2t0YIMNyPDlxj5dib84-vURwzdDS01rGAE/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9zdGF0/aWMwLmdhbWVyYW50/aW1hZ2VzLmNvbS93/b3JkcHJlc3Mvd3At/Y29udGVudC91cGxv/YWRzLzIwMjQvMTIv/bWl4Y29sbGFnZS0w/OC1kZWMtMjAyNC0w/Mi0zOC1wbS0zMTE2/LmpwZw', '2025-10-14 15:48:57', '2025-10-14 15:48:57'),
(4, 'Grand Theft Auto V', 'Explora Los Santos en este juego de acción y mundo abierto, con modo historia y multijugador.', 29.99, 'https://imgs.search.brave.com/QhNppADLP_9weCtPB0jBuZXMxBKRNchoj7ISnb-y7fY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWFn/ZS5hcGkucGxheXN0/YXRpb24uY29tL2Nk/bi9VUDEwMDQvQ1VT/QTAwNDE5XzAwL2JU/TlNlN29rOGVGVkdl/UUJ5QTVxU3pCUW9L/QUFZMzJSLnBuZw', '2025-10-14 15:48:57', '2025-10-14 15:48:57'),
(5, 'Elden Ring', 'Un RPG de acción en un mundo abierto creado por FromSoftware y George R. R. Martin.', 59.99, 'https://assets.xboxservices.com/assets/7b/54/7b54f5e4-0857-4ce3-8a18-2b8c431e8a9e.jpg?n=Elden-Ring_GLP-Page-Hero-0_1083x1222_01.jpg', '2025-10-14 15:48:57', '2025-10-14 15:48:57'),
(6, 'Hollow Knight', 'Un metroidvania de acción y aventura en un mundo subterráneo lleno de insectos y misterios.', 14.99, 'https://gaming-cdn.com/images/products/2198/616x353/hollow-knight-pc-mac-juego-steam-cover.jpg?v=1705490619', '2025-10-14 15:48:57', '2025-10-14 15:48:57'),
(7, 'Celeste', 'Un desafiante juego de plataformas sobre escalar una montaña y superar tus propios límites.', 19.99, 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bd/Celeste_video_game_logo.png/250px-Celeste_video_game_logo.png', '2025-10-14 15:48:57', '2025-10-14 15:48:57'),
(8, 'Stardew Valley', 'Simulador de granja donde puedes cultivar, criar animales, pescar y explorar cuevas.', 13.99, 'https://imgs.search.brave.com/DJqAJFbpHocH7w1Qk6dwIyyO0TDPCPQe_L1dWTNoMdU/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWFn/ZXMubmV4dXNtb2Rz/LmNvbS9pbWFnZXMv/Z2FtZXMvdjIvMTMw/My90aWxlLmpwZw', '2025-10-14 15:48:57', '2025-10-14 15:48:57'),
(9, 'Undertale', 'Un RPG único donde puedes elegir no matar a ningún enemigo y tus decisiones importan.', 9.99, 'https://imgs.search.brave.com/_1Cbq-sG1XFc0BPH2NXfqIRdZGOY_bO2CMgDCVMykUI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL00v/TVY1Qk1XSTNaVGt4/WmprdFlXVTNOQzAw/T0dRMUxXRmxOemd0/WXpJd01XSTRORGcy/WVRVMFhrRXlYa0Zx/Y0djQC5qcGc', '2025-10-14 15:48:57', '2025-10-14 15:48:57'),
(10, 'Cuphead', 'Un juego de plataformas y disparos con estética de dibujos animados de los años 30 y dificultad elevada.', 19.99, 'https://imgs.search.brave.com/Tavux6EMJShe1ZTkhrlhNtcVV-z62b3tv9PwxgOpTsQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLjNk/anVlZ29zLmNvbS9q/dWVnb3MvMTA1ODMv/Y3VwaGVhZC9mb3Rv/cy9maWNoYS9jdXBo/ZWFkLTM4NDE5MzIu/d2VicA', '2025-10-14 15:48:57', '2025-10-14 15:48:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_14_174409_create_juegos_table', 1),
(5, '2025_10_14_174417_create_bibliotecas_table', 1),
(6, '2025_10_14_174423_create_resenas_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `juego_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('PsGbh51mbteng3VljoISacGdiC3sazLJIYleHC0q', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid1I4Wm1OSWt3aU5aR3lrVVVBTTZzQTYwek03dDNBVW5IenlGWUZDdiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1760473524),
('u4xJIm92eyQbHZz8cSLyN1CwqnJ6alE5aobDUb1V', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoienhMWExmR2FYcmV4UXZtNGx2ZW9EaTd1RldIT05qOTFxUFFoNGN1ciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iaWJsaW90ZWNhIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1760467083),
('vwrrGBQtIgqzBMnvlLWrzyVOEUQTF2vJQRJg3XWO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQTJOZ29wT2I2VnZZVWhySktOMXJhS1JEWFk1WllZRDAzUDlFWDNOZiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1760475319);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` enum('user','admin') NOT NULL DEFAULT 'user',
  `saldo` decimal(10,2) NOT NULL DEFAULT 100.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `clave`, `rol`, `saldo`, `created_at`, `updated_at`) VALUES
(1, 'usuario1', 'usuario1@steamhrg.com', '$2y$12$8jdIQm7xbZlASZ10PKOggeqjRFINvGBkhcaF4D3foCcY6dviHfGuO', 'user', 60.01, '2025-10-14 16:10:17', '2025-10-14 18:02:22'),
(2, 'admin1', 'admin1@steamhrg.com', '$2y$12$HESiFl8lsSwtrOBX.EsLheGRUM3279m4J.4ISPjl4p2uUHSFer6zS', 'admin', 100.00, '2025-10-14 16:35:24', '2025-10-14 18:03:25'),
(3, 'usuario3', 'usuario3@gmail.com', '$2y$12$F3q9Cwed6Ij4/ACQoeeeY.AjHAG/syj8lRqGjtbfK4BIMuVzUlqki', 'user', 100.00, '2025-10-14 16:35:24', '2025-10-14 16:55:41');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bibliotecas`
--
ALTER TABLE `bibliotecas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bibliotecas_usuario_id_foreign` (`usuario_id`),
  ADD KEY `bibliotecas_juego_id_foreign` (`juego_id`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `juegos`
--
ALTER TABLE `juegos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resenas_usuario_id_foreign` (`usuario_id`),
  ADD KEY `resenas_juego_id_foreign` (`juego_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuarios_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bibliotecas`
--
ALTER TABLE `bibliotecas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `juegos`
--
ALTER TABLE `juegos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bibliotecas`
--
ALTER TABLE `bibliotecas`
  ADD CONSTRAINT `bibliotecas_juego_id_foreign` FOREIGN KEY (`juego_id`) REFERENCES `juegos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bibliotecas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `resenas_juego_id_foreign` FOREIGN KEY (`juego_id`) REFERENCES `juegos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resenas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
