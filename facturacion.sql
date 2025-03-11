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

INSERT INTO `facturacion` (`id`, `id_usuario`, `nombre`, `apellido`, `cedula`, `fecha`, `fecha_entrega`, `telefono`, `empresa`, `direccion`, `barrio`, `referencia`, `valor`, `num_cuotas`, `observaciones`) VALUES
(2, 1, 'geremy', 'peñalosa', 1123, '2025-03-05 05:00:00', '0000-00-00 00:00:00', '3196683319', 'ed', 'calle 62a sur #73c-21', 'bosa', 'avon', 12345, '2', ''),
(3, 2, 'prueba', 'dos', 123456, '2025-03-06 05:00:00', '0000-00-00 00:00:00', '2121212', 's', 'calle 62a sur #73c-21', 'bosa', 'avon', 12356432, '2', 'xd'),
(4, 1, 'diego', 'pedraza', 1213342, '2025-03-04 05:00:00', '0000-00-00 00:00:00', '3195412234', 'el mejor', 'calle 62a sur #73c-21', 'bosa', 'avon', 123435, '1', 'lo se es el mejor'),
(5, 1, 'diego alejandro', 'pedraza rojas', 1016834930, '2025-03-01 05:00:00', '0000-00-00 00:00:00', '3195412234', 'el mejor', 'calle 62a sur #73c-21', 'perdomo', '0', 999999, '1', 'soy el mejor lo se'),
(6, 1, 'prueba', 'prueba 2', 2314, '2025-03-05 05:00:00', '0000-00-00 00:00:00', '123445', 'ed', 'calle 62a sur #73c-21', 'bosa', 'avon', 123, '2', 'eds'),
(7, 1, 'prueba', 'prueba 2', 2314, '2025-03-05 05:00:00', '0000-00-00 00:00:00', '123445', 'ed', 'calle 62a sur #73c-21', 'bosa', 'avon', 123, '2', 'eds'),
(8, 3, 'diego', 'pedraza rojas', 1016834930, '2025-03-10 05:00:00', '2025-03-14 05:00:00', '3195412234', 'ed', 'calle 62a sur #73c-21', 'bosa', '0', 1235123, '1', 'sin cajas'),
(9, 4, 'alejandro', 'pedraza rojas', 123123, '2025-03-01 05:00:00', '2025-03-10 05:00:00', '3196683319', 'ed', 'calle 62a sur #73c-21', 'bosa', '0', 2131134, '2', 'con cajas y cinta');

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
