-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-10-2026 a las 19:38:12
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
-- Base de datos: `sae_egf`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id_alumno` int(11) NOT NULL,
  `matricula` varchar(15) NOT NULL,
  `nombre_alum` varchar(15) NOT NULL,
  `A_parterno_alum` varchar(15) NOT NULL,
  `A_materno_alum` varchar(15) NOT NULL,
  `domicilio` varchar(80) NOT NULL,
  `mail_alum` varchar(50) NOT NULL,
  `telefono` varchar(35) NOT NULL,
  `estatus` varchar(4) NOT NULL DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id_alumno`, `matricula`, `nombre_alum`, `A_parterno_alum`, `A_materno_alum`, `domicilio`, `mail_alum`, `telefono`, `estatus`) VALUES
(1, '0202020', 'Erick', 'Gonzalez', 'fuentes', 'c santiago Jarillo', 'pascualfuentes103@gmail.com', '5548435562', 'ALTA'),
(2, '0101010', 'Alan', 'Martinez', 'Linares', 'GAM', 'AlanML@gmail.com', '55 2200 6677', 'ALTA'),
(3, '0202020', 'Bris Eraldy', 'Hernandez ', 'Cruz', 'cdmx', 'Briserlady@gmail.com', '1234567890', 'ALTA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE `grupo` (
  `id_grupo` int(11) NOT NULL,
  `descripcion_grupo` varchar(15) NOT NULL DEFAULT 'ALTA',
  `estatus_grupo` varchar(4) NOT NULL DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id_grupo`, `descripcion_grupo`, `estatus_grupo`) VALUES
(1, '4T1', 'ALTA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id_materia` int(11) NOT NULL,
  `descripcion_materia` varchar(150) NOT NULL,
  `estatus_materia` varchar(4) NOT NULL DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id_materia`, `descripcion_materia`, `estatus_materia`) VALUES
(1, 'Programacion II', 'ALTA'),
(2, 'hggyfvgycgc', 'ALTA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor`
--

CREATE TABLE `profesor` (
  `id_profesor` int(11) NOT NULL,
  `N_control_profesor` varchar(15) NOT NULL,
  `nombre_profesor` varchar(15) NOT NULL,
  `A_paterno_profesor` text NOT NULL,
  `A_materno_profesor` varchar(15) NOT NULL,
  `Domicilio_profesor` varchar(80) NOT NULL,
  `mail_profesor` varchar(50) NOT NULL,
  `telefono_profesor` varchar(35) NOT NULL,
  `estatus_profesor` varchar(4) NOT NULL DEFAULT 'ALTA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesor`
--

INSERT INTO `profesor` (`id_profesor`, `N_control_profesor`, `nombre_profesor`, `A_paterno_profesor`, `A_materno_profesor`, `Domicilio_profesor`, `mail_profesor`, `telefono_profesor`, `estatus_profesor`) VALUES
(1, '0000001', 'Cesar', 'Hernandez', 'Gonzalez', 'CMDX', 'Cesarhernandezgonzalez@gmail.com', '1234567890', 'ALTA'),
(2, '0000001', 'Cesar', 'Hernandez', 'Gonzalez', 'CMDX', 'Cesarhernandezgonzalez@gmail.com', '1234567890', 'ALTA');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id_alumno`);

--
-- Indices de la tabla `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id_grupo`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id_materia`);

--
-- Indices de la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD PRIMARY KEY (`id_profesor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id_alumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `profesor`
--
ALTER TABLE `profesor`
  MODIFY `id_profesor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
