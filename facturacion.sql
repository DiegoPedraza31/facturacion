-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 11-03-2025 a las 16:11:25
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `facturacion_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturacion`
--

DROP TABLE IF EXISTS `facturacion`;
CREATE TABLE IF NOT EXISTS `facturacion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `cedula` int NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_entrega` timestamp NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `empresa` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `barrio` varchar(100) NOT NULL,
  `referencia` varchar(100) NOT NULL,
  `valor` int NOT NULL,
  `num_cuotas` varchar(100) NOT NULL,
  `observaciones` text,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `facturacion`
--


-- facturacion_db.usuarios definition

CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- facturacion_db.facturas_auditoria definition

CREATE TABLE `facturas_auditoria` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_factura` int DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `accion` enum('INSERT','UPDATE','DELETE') DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `datos_anteriores` text,
  `datos_nuevos` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Disparadores `facturacion`
--
DROP TRIGGER IF EXISTS `after_facturas_insert`;
DELIMITER $$
CREATE TRIGGER `after_facturas_insert` AFTER INSERT ON `facturacion` FOR EACH ROW BEGIN
    INSERT INTO facturas_auditoria (id_factura, usuario, accion, datos_nuevos)
    VALUES (NEW.id, USER(), 'INSERT', CONCAT('Monto: ', NEW.valor, ', Cliente: ', NEW.nombre));
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `after_facturas_update`;
DELIMITER $$
CREATE TRIGGER `after_facturas_update` AFTER UPDATE ON `facturacion` FOR EACH ROW BEGIN
    INSERT INTO facturas_auditoria (id_factura, usuario, accion, datos_anteriores, datos_nuevos)
    VALUES (
        OLD.id, 
        USER(), 
        'UPDATE',
        CONCAT('Monto: ', OLD.valor, ', Cliente: ', OLD.nombre),
        CONCAT('Monto: ', NEW.valor, ', Cliente: ', NEW.nombre)
    );
END
$$
DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

