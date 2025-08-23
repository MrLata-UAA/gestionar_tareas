-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para gestionar_tareas
CREATE DATABASE IF NOT EXISTS `gestionar_tareas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `gestionar_tareas`;

-- Volcando estructura para tabla gestionar_tareas.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasenha` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla gestionar_tareas.usuarios: ~2 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contrasenha`, `created_at`, `deleted_at`, `updated_at`) VALUES
	(1, 'Luis Demo', 'trabucoagueroluisalberto@gmail.com', '$2y$10$BLv9vtdEW3i6O5q6R9E0eeegXEsKl5zby7Y2oENSMQbT7BGrqFDhe', '2025-08-22 03:02:20', NULL, '2025-08-22 03:02:20'), -- contraseña:123789
	(2, 'Testing Demo', 'test@demo.com', '$2y$10$cf/kFGJ1i5b4oQiB5XDYzeJu7CuYqh9bYYdbZ7gcfvfiyiEYzMYEW', '2025-08-23 01:08:58', NULL, '2025-08-23 01:08:58'); -- contraseña:123456

-- Volcando estructura para tabla gestionar_tareas.tareas
CREATE TABLE IF NOT EXISTS `tareas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text,
  `estado` enum('pendiente','en progreso','completada') DEFAULT 'pendiente',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_limite` date DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla gestionar_tareas.tareas: ~8 rows (aproximadamente)
INSERT INTO `tareas` (`id`, `usuario_id`, `titulo`, `descripcion`, `estado`, `fecha_inicio`, `fecha_limite`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(7, 2, 'Estudiar PHP', 'Repasar CRUD y sesiones', 'completada', '2025-08-19', '2025-08-20', NULL, '2025-08-20 10:08:21', '2025-08-20 10:17:38'),
	(9, 2, 'Compartir Repositorio', 'Proceso para compartir el repositorio en mi cuenta de Github', 'completada', '2025-08-20', '2025-08-24', NULL, '2025-08-21 12:43:34', '2025-08-21 12:43:36'),
	(10, 1, 'Comprar víveres', 'Leche, pan, frutas y verduras', 'completada', '2025-08-19', '2025-08-25', NULL, '2025-08-22 22:15:47', '2025-08-22 22:15:53'),
	(11, 1, 'Estudiar PHP', 'Repasar CRUD y sesiones', 'completada', '2025-08-20', '2025-08-25', NULL, '2025-08-22 22:15:49', '2025-08-23 01:53:40'),
	(12, 1, 'Enviar proyecto', 'Subir repositorio a GitHub', 'completada', '2025-08-21', '2025-08-25', NULL, '2025-08-22 22:15:50', '2025-08-23 02:13:04'),
	(13, 1, 'Enviar proyecto', 'Compartir enlace de la tarea', 'completada', '2025-08-23', '2025-08-25', NULL, '2025-08-23 02:14:27', '2025-08-23 02:18:45');


/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
