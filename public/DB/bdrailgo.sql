-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-05-2025 a las 20:15:40
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
-- Base de datos: `bdrailgo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `averias`
--

CREATE TABLE `averias` (
  `id_averia` int(11) NOT NULL,
  `num_serie` varchar(12) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `fecha` datetime NOT NULL,
  `coste` double NOT NULL,
  `reparada` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `averias`
--

INSERT INTO `averias` (`id_averia`, `num_serie`, `descripcion`, `fecha`, `coste`, `reparada`) VALUES
(1, '22222222222T', 'Cambio de aceite', '2025-03-20 12:30:00', 300, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `telefono` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`dni`, `nombre`, `email`, `telefono`, `password`) VALUES
('', 'Administrador', 'admin@admin.com', '', 'admin'),
('55577788E', 'Paco', 'paco@gmail.com', '777888999', '123'),
('71348221J', 'Hugo', 'hugoferfer16@gmail.com', '607925732', '12345678'),
('88844455T', 'Maria', 'maria@gmail.com', '547548932', '123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_ticket` int(11) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `id_ruta` int(11) NOT NULL,
  `num_asiento` int(11) NOT NULL,
  `fecha_reserva` datetime NOT NULL,
  `opinion` varchar(200) NOT NULL,
  `fecha_opinion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_ticket`, `dni`, `id_ruta`, `num_asiento`, `fecha_reserva`, `opinion`, `fecha_opinion`) VALUES
(583, '55577788E', 7, 18, '2025-05-23 12:41:34', '', '0000-00-00 00:00:00'),
(584, '55577788E', 7, 60, '2025-05-21 12:41:34', '', '0000-00-00 00:00:00'),
(585, '71348221J', 6, 14, '2025-05-04 12:41:34', '', '0000-00-00 00:00:00'),
(586, '71348221J', 6, 5, '2025-05-04 12:41:34', '', '0000-00-00 00:00:00'),
(587, '71348221J', 7, 1, '2025-05-04 12:41:55', '', '0000-00-00 00:00:00'),
(588, '71348221J', 7, 2, '2025-05-04 12:41:55', '', '0000-00-00 00:00:00'),
(589, '71348221J', 6, 1, '2025-05-04 12:41:55', '', '0000-00-00 00:00:00'),
(590, '71348221J', 6, 2, '2025-05-04 12:41:55', '', '0000-00-00 00:00:00'),
(591, '55577788E', 3, 11, '2025-05-05 14:46:15', '', '0000-00-00 00:00:00'),
(592, '71348221J', 3, 1, '2025-05-05 15:28:42', '', '0000-00-00 00:00:00'),
(593, '71348221J', 3, 2, '2025-05-05 15:28:42', '', '0000-00-00 00:00:00'),
(594, '71348221J', 3, 3, '2025-05-05 15:28:42', '', '0000-00-00 00:00:00'),
(595, '71348221J', 3, 131, '2025-05-07 15:15:25', '', '0000-00-00 00:00:00'),
(596, '71348221J', 3, 138, '2025-05-07 15:15:57', '', '0000-00-00 00:00:00'),
(597, '71348221J', 3, 121, '2025-05-07 15:16:13', '', '0000-00-00 00:00:00'),
(598, '55577788E', 3, 87, '2025-05-07 15:31:14', '', '0000-00-00 00:00:00'),
(599, '71348221J', 3, 74, '2025-05-07 15:36:09', '', '0000-00-00 00:00:00'),
(600, '71348221J', 3, 25, '2025-05-07 15:36:09', '', '0000-00-00 00:00:00'),
(601, '71348221J', 3, 105, '2025-05-07 15:36:09', '', '0000-00-00 00:00:00'),
(602, '71348221J', 3, 130, '2025-05-07 15:37:53', '', '0000-00-00 00:00:00'),
(603, '55577788E', 8, 89, '2025-05-07 15:37:53', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutas`
--

CREATE TABLE `rutas` (
  `id_ruta` int(11) NOT NULL,
  `num_serie` varchar(12) NOT NULL,
  `origen` varchar(200) NOT NULL,
  `destino` varchar(200) NOT NULL,
  `hora_salida` datetime NOT NULL,
  `hora_llegada` datetime NOT NULL,
  `tarifa` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rutas`
--

INSERT INTO `rutas` (`id_ruta`, `num_serie`, `origen`, `destino`, `hora_salida`, `hora_llegada`, `tarifa`, `fecha`) VALUES
(1, '11111111111D', 'Vitoria', 'Madrid', '2025-04-26 10:30:00', '2025-03-26 15:00:00', 15, '2025-04-26'),
(2, '11111111111D', 'Vitoria', 'Madrid', '2025-03-26 14:30:00', '2025-03-26 19:00:00', 20, '2025-03-26'),
(3, '22222222222T', 'Miranda de Ebro', 'Alicante', '2025-05-07 22:59:14', '2025-05-07 20:59:14', 119, '2025-05-05'),
(4, '11111111111D', 'Oviedo', 'Barcelona', '2025-04-17 15:30:14', '2025-04-17 18:59:14', 55, '2025-04-17'),
(5, '22222222222T', 'Miranda de Ebro', 'San Sebastian', '2025-04-23 18:21:55', '2025-04-24 18:21:55', 25, '2025-04-23'),
(6, '111111111111', 'Alicante', 'Miranda de Ebro', '2025-05-06 16:32:26', '2025-04-28 23:32:26', 121, '2025-05-06'),
(7, '22222222222T', 'Miranda de Ebro', 'Alicante', '2025-05-23 10:10:00', '2025-05-23 18:32:26', 99, '2025-05-23'),
(8, '11111111111D', 'Alicante', 'Miranda de Ebro', '2025-05-07 23:07:54', '2025-05-05 17:07:54', 35, '2025-05-05'),
(9, '33333333333A', 'Madrid', 'Barcelona', '2025-06-01 08:00:00', '2025-06-01 11:00:00', 60, '2025-06-01'),
(10, '44444444444B', 'Barcelona', 'Valencia', '2025-06-01 09:30:00', '2025-06-01 12:00:00', 45, '2025-06-01'),
(11, '55555555555C', 'Valencia', 'Sevilla', '2025-06-01 13:00:00', '2025-06-01 17:30:00', 70, '2025-06-01'),
(12, '66666666666D', 'Sevilla', 'Zaragoza', '2025-06-01 15:00:00', '2025-06-01 20:00:00', 80, '2025-06-01'),
(13, '77777777777E', 'Zaragoza', 'Bilbao', '2025-06-01 10:00:00', '2025-06-01 13:00:00', 50, '2025-06-01'),
(14, '88888888888F', 'Bilbao', 'Málaga', '2025-06-02 07:00:00', '2025-06-02 13:00:00', 85, '2025-06-02'),
(15, '99999999999G', 'Málaga', 'Alicante', '2025-06-02 14:00:00', '2025-06-02 18:00:00', 55, '2025-06-02'),
(16, '33333333333A', 'Alicante', 'Valladolid', '2025-06-03 08:30:00', '2025-06-03 12:30:00', 60, '2025-06-03'),
(17, '44444444444B', 'Valladolid', 'Gijón', '2025-06-03 13:00:00', '2025-06-03 16:30:00', 50, '2025-06-03'),
(18, '55555555555C', 'Gijón', 'Vigo', '2025-06-03 17:00:00', '2025-06-03 20:30:00', 55, '2025-06-03'),
(19, '66666666666D', 'Vigo', 'Salamanca', '2025-06-04 09:00:00', '2025-06-04 12:00:00', 40, '2025-06-04'),
(20, '77777777777E', 'Salamanca', 'Murcia', '2025-06-04 13:00:00', '2025-06-04 18:00:00', 75, '2025-06-04'),
(21, '88888888888F', 'Murcia', 'Granada', '2025-06-04 19:00:00', '2025-06-04 21:30:00', 35, '2025-06-04'),
(22, '99999999999G', 'Granada', 'San Sebastián', '2025-06-05 08:00:00', '2025-06-05 13:00:00', 90, '2025-06-05'),
(23, '33333333333A', 'San Sebastián', 'Pamplona', '2025-06-05 14:00:00', '2025-06-05 15:30:00', 25, '2025-06-05'),
(24, '44444444444B', 'Pamplona', 'Santander', '2025-06-05 16:00:00', '2025-06-05 18:00:00', 30, '2025-06-05'),
(25, '55555555555C', 'Santander', 'León', '2025-06-05 19:00:00', '2025-06-05 21:00:00', 28, '2025-06-05'),
(26, '66666666666D', 'León', 'Cádiz', '2025-06-06 08:00:00', '2025-06-06 13:00:00', 80, '2025-06-06'),
(27, '77777777777E', 'Cádiz', 'Almería', '2025-06-06 14:00:00', '2025-06-06 17:00:00', 35, '2025-06-06'),
(28, '33333333333A', 'Madrid', 'Barcelona', '2025-06-01 08:00:00', '2025-06-01 11:00:00', 60, '2025-06-01'),
(29, '44444444444B', 'Barcelona', 'Valencia', '2025-06-01 09:30:00', '2025-06-01 12:30:00', 45, '2025-06-01'),
(30, '55555555555C', 'Valencia', 'Sevilla', '2025-06-01 13:00:00', '2025-06-01 17:00:00', 70, '2025-06-01'),
(31, '66666666666D', 'Sevilla', 'Zaragoza', '2025-06-01 14:00:00', '2025-06-01 18:00:00', 80, '2025-06-01'),
(32, '77777777777E', 'Zaragoza', 'Bilbao', '2025-06-01 15:00:00', '2025-06-01 18:00:00', 50, '2025-06-01'),
(33, '88888888888F', 'Bilbao', 'Málaga', '2025-06-01 16:00:00', '2025-06-01 20:00:00', 85, '2025-06-01'),
(34, '99999999999G', 'Málaga', 'Alicante', '2025-06-01 17:00:00', '2025-06-01 20:00:00', 55, '2025-06-01'),
(35, '33333333333A', 'Alicante', 'Valladolid', '2025-06-02 08:00:00', '2025-06-02 12:00:00', 60, '2025-06-02'),
(36, '44444444444B', 'Valladolid', 'Gijón', '2025-06-02 09:30:00', '2025-06-02 12:30:00', 50, '2025-06-02'),
(37, '55555555555C', 'Gijón', 'Vigo', '2025-06-02 13:00:00', '2025-06-02 16:00:00', 55, '2025-06-02'),
(38, '66666666666D', 'Vigo', 'Salamanca', '2025-06-02 14:00:00', '2025-06-02 18:00:00', 40, '2025-06-02'),
(39, '77777777777E', 'Salamanca', 'Murcia', '2025-06-02 15:00:00', '2025-06-02 19:00:00', 75, '2025-06-02'),
(40, '88888888888F', 'Murcia', 'Granada', '2025-06-02 16:00:00', '2025-06-02 19:00:00', 35, '2025-06-02'),
(41, '99999999999G', 'Granada', 'San Sebastián', '2025-06-02 17:00:00', '2025-06-02 21:00:00', 90, '2025-06-02'),
(42, '33333333333A', 'San Sebastián', 'Pamplona', '2025-06-03 08:00:00', '2025-06-03 10:00:00', 25, '2025-06-03'),
(43, '44444444444B', 'Pamplona', 'Santander', '2025-06-03 09:30:00', '2025-06-03 12:00:00', 30, '2025-06-03'),
(44, '55555555555C', 'Santander', 'León', '2025-06-03 13:00:00', '2025-06-03 15:30:00', 28, '2025-06-03'),
(45, '66666666666D', 'León', 'Cádiz', '2025-06-03 14:00:00', '2025-06-03 19:00:00', 80, '2025-06-03'),
(46, '77777777777E', 'Cádiz', 'Almería', '2025-06-03 15:00:00', '2025-06-03 18:00:00', 35, '2025-06-03'),
(47, '88888888888F', 'Almería', 'Madrid', '2025-06-03 16:00:00', '2025-06-03 20:00:00', 60, '2025-06-03'),
(48, '99999999999G', 'Madrid', 'Granada', '2025-06-03 17:00:00', '2025-06-03 21:00:00', 77, '2025-06-03'),
(49, '33333333333A', 'Barcelona', 'Sevilla', '2025-06-04 08:00:00', '2025-06-04 13:00:00', 90, '2025-06-04'),
(50, '44444444444B', 'Valencia', 'Bilbao', '2025-06-04 09:30:00', '2025-06-04 13:00:00', 65, '2025-06-04'),
(51, '55555555555C', 'Sevilla', 'Valladolid', '2025-06-04 13:00:00', '2025-06-04 17:00:00', 60, '2025-06-04'),
(52, '66666666666D', 'Zaragoza', 'Gijón', '2025-06-04 14:00:00', '2025-06-04 18:00:00', 70, '2025-06-04'),
(53, '77777777777E', 'Bilbao', 'Salamanca', '2025-06-04 15:00:00', '2025-06-04 19:00:00', 48, '2025-06-04'),
(54, '88888888888F', 'Málaga', 'Murcia', '2025-06-04 16:00:00', '2025-06-04 19:00:00', 38, '2025-06-04'),
(55, '99999999999G', 'Alicante', 'Pamplona', '2025-06-04 17:00:00', '2025-06-04 21:00:00', 72, '2025-06-04'),
(56, '33333333333A', 'Valladolid', 'Valencia', '2025-06-05 08:00:00', '2025-06-05 12:00:00', 55, '2025-06-05'),
(57, '44444444444B', 'Gijón', 'Madrid', '2025-06-05 09:30:00', '2025-06-05 13:00:00', 60, '2025-06-05'),
(58, '55555555555C', 'Vigo', 'Barcelona', '2025-06-05 13:00:00', '2025-06-05 17:00:00', 75, '2025-06-05'),
(59, '66666666666D', 'Salamanca', 'Sevilla', '2025-06-05 14:00:00', '2025-06-05 18:00:00', 67, '2025-06-05'),
(60, '77777777777E', 'Murcia', 'Bilbao', '2025-06-05 15:00:00', '2025-06-05 19:00:00', 82, '2025-06-05'),
(61, '88888888888F', 'Granada', 'Zaragoza', '2025-06-05 16:00:00', '2025-06-05 20:00:00', 79, '2025-06-05'),
(62, '99999999999G', 'San Sebastián', 'León', '2025-06-05 17:00:00', '2025-06-05 21:00:00', 70, '2025-06-05'),
(63, '33333333333A', 'Madrid', 'Barcelona', '2025-05-23 08:00:00', '2025-05-23 11:00:00', 62, '2025-05-23'),
(64, '44444444444B', 'Madrid', 'Barcelona', '2025-05-23 12:00:00', '2025-05-23 15:00:00', 65, '2025-05-23'),
(65, '55555555555C', 'Madrid', 'Valencia', '2025-05-23 09:00:00', '2025-05-23 11:00:00', 50, '2025-05-23'),
(66, '66666666666D', 'Madrid', 'Valencia', '2025-05-23 14:00:00', '2025-05-23 16:00:00', 54, '2025-05-23'),
(67, '77777777777E', 'Barcelona', 'Sevilla', '2025-05-23 10:00:00', '2025-05-23 15:00:00', 80, '2025-05-23'),
(68, '88888888888F', 'Barcelona', 'Sevilla', '2025-05-23 16:00:00', '2025-05-23 21:00:00', 82, '2025-05-23'),
(69, '99999999999G', 'Sevilla', 'Bilbao', '2025-05-23 08:30:00', '2025-05-23 13:30:00', 70, '2025-05-23'),
(70, '33333333333A', 'Valencia', 'Málaga', '2025-05-24 07:30:00', '2025-05-24 11:30:00', 65, '2025-05-24'),
(71, '44444444444B', 'Valencia', 'Málaga', '2025-05-24 13:30:00', '2025-05-24 17:30:00', 68, '2025-05-24'),
(72, '55555555555C', 'Bilbao', 'Granada', '2025-05-24 09:00:00', '2025-05-24 13:00:00', 72, '2025-05-24'),
(73, '66666666666D', 'Bilbao', 'Granada', '2025-05-24 15:00:00', '2025-05-24 19:00:00', 75, '2025-05-24'),
(74, '77777777777E', 'Madrid', 'Sevilla', '2025-05-24 10:00:00', '2025-05-24 13:30:00', 64, '2025-05-24'),
(75, '88888888888F', 'Madrid', 'Sevilla', '2025-05-24 17:00:00', '2025-05-24 20:30:00', 67, '2025-05-24'),
(76, '99999999999G', 'Barcelona', 'Valencia', '2025-05-24 08:00:00', '2025-05-24 10:00:00', 48, '2025-05-24'),
(77, '33333333333A', 'Sevilla', 'Madrid', '2025-05-25 09:00:00', '2025-05-25 12:30:00', 64, '2025-05-25'),
(78, '44444444444B', 'Sevilla', 'Madrid', '2025-05-25 17:00:00', '2025-05-25 20:30:00', 67, '2025-05-25'),
(79, '55555555555C', 'Bilbao', 'Barcelona', '2025-05-25 08:00:00', '2025-05-25 12:00:00', 70, '2025-05-25'),
(80, '66666666666D', 'Bilbao', 'Barcelona', '2025-05-25 14:00:00', '2025-05-25 18:00:00', 73, '2025-05-25'),
(81, '77777777777E', 'Granada', 'Valencia', '2025-05-25 09:30:00', '2025-05-25 13:30:00', 62, '2025-05-25'),
(82, '88888888888F', 'Granada', 'Valencia', '2025-05-25 15:30:00', '2025-05-25 19:30:00', 65, '2025-05-25'),
(83, '99999999999G', 'Málaga', 'Madrid', '2025-05-25 07:30:00', '2025-05-25 10:30:00', 55, '2025-05-25'),
(84, '33333333333A', 'Madrid', 'Murcia', '2025-05-26 09:00:00', '2025-05-26 12:00:00', 58, '2025-05-26'),
(85, '44444444444B', 'Madrid', 'Murcia', '2025-05-26 15:00:00', '2025-05-26 18:00:00', 60, '2025-05-26'),
(86, '55555555555C', 'Sevilla', 'Valencia', '2025-05-26 08:00:00', '2025-05-26 12:00:00', 68, '2025-05-26'),
(87, '66666666666D', 'Sevilla', 'Valencia', '2025-05-26 14:00:00', '2025-05-26 18:00:00', 71, '2025-05-26'),
(88, '77777777777E', 'Barcelona', 'Bilbao', '2025-05-26 09:00:00', '2025-05-26 13:00:00', 66, '2025-05-26'),
(89, '88888888888F', 'Barcelona', 'Bilbao', '2025-05-26 16:00:00', '2025-05-26 20:00:00', 69, '2025-05-26'),
(90, '99999999999G', 'Valencia', 'Granada', '2025-05-26 10:00:00', '2025-05-26 14:00:00', 60, '2025-05-26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trenes`
--

CREATE TABLE `trenes` (
  `num_serie` varchar(12) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `modelo` varchar(200) NOT NULL,
  `vagones` int(11) NOT NULL,
  `imagen` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trenes`
--

INSERT INTO `trenes` (`num_serie`, `capacidad`, `modelo`, `vagones`, `imagen`) VALUES
('111111111111', 60, 'Ave', 3, 'sinImg.png'),
('11111111111D', 200, 'Mercedes', 4, 'tren_amarillo.jpeg'),
('22222222222T', 150, 'Ford', 5, 'tren_blanco.jpg'),
('33333333333A', 120, 'Talgo', 4, 'tren_mercedes.jpg'),
('44444444444B', 220, 'Alvia', 6, 'tren_naranja.jpg'),
('55555555555C', 100, 'Avant', 3, 'tren_renfe.jpg'),
('66666666666D', 180, 'Intercity', 5, 'tren_rojo.png'),
('77777777777E', 250, 'Euromed', 8, 'tren_verde.png'),
('88888888888F', 80, 'Cercanías', 2, 'trene-amarillo.jpg'),
('99999999999G', 160, 'Regional', 4, 'tren-renfe2.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `averias`
--
ALTER TABLE `averias`
  ADD PRIMARY KEY (`id_averia`),
  ADD KEY `num_serie` (`num_serie`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`dni`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_ticket`),
  ADD KEY `dni` (`dni`,`id_ruta`),
  ADD KEY `id_ruta` (`id_ruta`);

--
-- Indices de la tabla `rutas`
--
ALTER TABLE `rutas`
  ADD PRIMARY KEY (`id_ruta`),
  ADD KEY `num_serie` (`num_serie`);

--
-- Indices de la tabla `trenes`
--
ALTER TABLE `trenes`
  ADD PRIMARY KEY (`num_serie`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `averias`
--
ALTER TABLE `averias`
  MODIFY `id_averia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=604;

--
-- AUTO_INCREMENT de la tabla `rutas`
--
ALTER TABLE `rutas`
  MODIFY `id_ruta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `averias`
--
ALTER TABLE `averias`
  ADD CONSTRAINT `averias_ibfk_1` FOREIGN KEY (`num_serie`) REFERENCES `trenes` (`num_serie`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_ruta`) REFERENCES `rutas` (`id_ruta`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`dni`) REFERENCES `clientes` (`dni`);

--
-- Filtros para la tabla `rutas`
--
ALTER TABLE `rutas`
  ADD CONSTRAINT `rutas_ibfk_1` FOREIGN KEY (`num_serie`) REFERENCES `trenes` (`num_serie`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
