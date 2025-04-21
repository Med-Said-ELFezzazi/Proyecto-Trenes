-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-04-2025 a las 17:04:07
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
('55577788E', 'Paco', 'paco@gmail.com', '777888999', '123'),
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
(1, '55577788E', 1, 1, '2025-03-22 12:30:00', '', '2025-03-22 12:26:43');

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
(1, '11111111111D', 'Vitoria', 'Madrid', '2025-04-14 10:30:00', '2025-03-26 15:00:00', 15, '2025-04-14'),
(2, '11111111111D', 'Vitoria', 'Madrid', '2025-04-14 14:30:00', '2025-03-26 19:00:00', 20, '2025-04-14'),
(10, '11111111111D', 'Vitoria', 'Donostia', '2025-04-14 08:00:00', '2025-04-14 08:30:00', 13, '2025-04-14'),
(11, '11111111111D', 'Vitoria', 'Donostia', '2025-04-14 10:00:00', '2025-04-14 10:30:00', 13, '2025-04-14'),
(12, '11111111111D', 'Vitoria', 'Donostia', '2025-04-14 12:00:00', '2025-04-14 12:30:00', 13, '2025-04-14'),
(13, '11111111111D', 'Vitoria', 'Donostia', '2025-04-14 14:00:00', '2025-04-14 14:30:00', 13, '2025-04-14'),
(14, '11111111111D', 'Vitoria', 'Donostia', '2025-04-14 16:00:00', '2025-04-14 16:30:00', 13, '2025-04-14');

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
('11111111111D', 200, 'Mercedes', 4, 'mercedes.jpg'),
('22222222222T', 150, 'Ford', 5, 'ford.jpg');

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
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `rutas`
--
ALTER TABLE `rutas`
  MODIFY `id_ruta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
