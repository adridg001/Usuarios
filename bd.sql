-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 14-01-2025 a las 11:42:24
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
-- Base de datos: `Digi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `partidas_ganadas` int(11) DEFAULT 0,
  `partidas_perdidas` int(11) DEFAULT 0,
  `permisos` tinyint(1) NOT NULL DEFAULT 0,
  `digievoluciones_disponibles` int(11) DEFAULT 0,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `digimones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `ataque` int(11) NOT NULL,
  `defensa` int(11) NOT NULL,
  `nivel` int(11) NOT NULL DEFAULT 1,
  `evo_id` int(11) NOT NULL,
  `tipo` int(11) NOT NULL
  `imagen` varchar(255) NOT NULL,
  `imagen_victoria` varchar(255) NOT NULL,
  `imagen_derrota` varchar(255) NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `digimones_inv` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `digimon_id` int(11) NOT NULL,
  `seleccionado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `equipo` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `digimon_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `nick` (`nick`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
