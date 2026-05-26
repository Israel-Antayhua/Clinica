-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-05-2026 a las 03:34:40
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
-- Base de datos: `clinica_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_medico` int(11) DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'Confirmada',
  `monto` decimal(10,2) DEFAULT 90.00,
  `estado_pago` varchar(20) DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `id_paciente`, `fecha`, `hora`, `id_medico`, `estado`, `monto`, `estado_pago`) VALUES
(12, 2, '2026-05-22', '22:00:00', 5, 'Confirmada', 90.00, 'Pagado'),
(13, 2, '2026-05-21', '08:00:00', 9, 'Confirmada', 190.00, 'Pagado'),
(14, 2, '2026-05-21', '21:00:00', 9, 'Confirmada', 200.00, 'Pagado'),
(15, 1, '2026-05-25', '15:00:00', 9, 'Confirmada', 90.00, 'Pagado'),
(16, 1, '2026-05-22', '17:00:00', 9, 'Confirmada', 90.00, 'Pendiente'),
(17, 2, '2026-05-17', '12:00:00', 9, 'Confirmada', 90.00, 'Pendiente'),
(18, 1, '2026-05-21', '14:00:00', 9, 'Confirmada', 90.00, 'Pendiente'),
(19, 1, '2026-05-28', '16:00:00', 9, 'Pendiente', 90.00, 'Pendiente'),
(20, 1, '2026-05-20', '14:00:00', 9, 'Confirmada', 90.00, 'Pendiente'),
(21, 3, '2026-05-23', '15:00:00', 9, 'Confirmada', 90.00, 'Pendiente'),
(22, 3, '2026-05-25', '17:00:00', 9, 'Confirmada', 90.00, 'Pagado'),
(23, 1, '2026-05-21', '22:00:00', 5, 'Confirmada', 90.00, 'Pagado'),
(24, 2, '2026-05-23', '17:00:00', 5, 'Confirmada', 200.00, 'Pendiente'),
(25, 3, '2026-05-22', '10:00:00', 9, 'Confirmada', 90.00, 'Pendiente'),
(26, 2, '2026-05-22', '22:00:00', 9, 'Confirmada', 90.00, 'Pagado'),
(27, 2, '2026-05-22', '18:00:00', 7, 'Confirmada', 90.00, 'Pendiente'),
(28, 2, '2026-05-22', '15:00:00', 5, 'Confirmada', 90.00, 'Pagado'),
(29, 2, '2026-05-31', '18:00:00', 8, 'Confirmada', 150.00, 'Pendiente'),
(30, 2, '2026-05-20', '17:00:00', 7, 'Confirmada', 220.00, 'Pendiente'),
(31, 2, '2026-05-25', '12:00:00', 9, 'Confirmada', NULL, 'Pendiente'),
(32, 2, '2026-05-23', '18:00:00', 9, 'Confirmada', 100.00, 'Pendiente'),
(33, 2, '2026-05-24', '18:00:00', 9, 'Confirmada', 100.00, 'Pendiente'),
(34, 2, '2026-05-18', '18:00:00', 7, 'Confirmada', 100.00, 'Pendiente'),
(35, 2, '2026-05-25', '18:00:00', 7, 'Confirmada', 100.00, 'Pendiente'),
(36, 2, '2026-05-29', '18:00:00', 9, 'Confirmada', 100.00, 'Pendiente'),
(37, 2, '2026-06-01', '16:00:00', 5, 'Confirmada', 60.00, 'Pendiente'),
(38, 2, '2026-05-23', '11:00:00', 9, 'Confirmada', 100.00, 'Pendiente'),
(39, 2, '2026-05-25', '18:00:00', 9, 'Confirmada', 100.00, 'Pendiente'),
(40, 2, '2026-05-25', '16:00:00', 9, 'Confirmada', 100.00, 'Pendiente'),
(41, 2, '2026-05-25', '10:00:00', 9, 'Confirmada', 100.00, 'Pendiente'),
(42, 2, '2026-05-25', '14:00:00', 9, 'Confirmada', 100.00, 'Pendiente'),
(43, 2, '2026-05-29', '11:00:00', 8, 'Confirmada', 150.00, 'Pendiente'),
(44, 2, '2026-06-04', '11:00:00', 9, 'Confirmada', 100.00, 'Pendiente'),
(45, 2, '2026-05-25', '08:00:00', 9, 'Confirmada', 100.00, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `estado` varchar(20) DEFAULT 'Activo',
  `precio_consulta` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id`, `nombre`, `estado`, `precio_consulta`) VALUES
(1, 'Cardiología', 'Activo', 150.00),
(2, 'Dermatología', 'Activo', 160.00),
(3, 'Medicina General', 'Activo', 60.00),
(4, 'Pediatría', 'Activo', 100.00),
(5, 'Ginecología', 'Activo', 120.00),
(6, 'Oncología', 'Activo', 220.00),
(7, 'Reumatología', 'Activo', 160.00),
(8, 'Medicina Interna', 'Activo', 120.00),
(9, 'Traumatología', 'Activo', 130.00),
(10, 'Gestion de lavadero', 'Activo', 300.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE `medicos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_especialidad` int(11) DEFAULT NULL,
  `telefono` varchar(20) NOT NULL,
  `estado` varchar(20) DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`id`, `id_usuario`, `nombre`, `id_especialidad`, `telefono`, `estado`) VALUES
(5, 5, 'Jose', 3, '943402322', 'Activo'),
(6, 6, 'Miguel', 1, '923323999', 'Activo'),
(7, 9, 'Rusbelt', 4, '924321122', 'Activo'),
(8, 10, 'Joshep', 1, '999232922', 'Activo'),
(9, 11, 'Carla', 4, '924323021', 'Activo'),
(10, 12, 'Reile', 5, '924242923', 'Activo'),
(11, 13, 'Cristina', 10, '924859549', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id_paciente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombres` varchar(30) NOT NULL,
  `apellidos` varchar(30) NOT NULL,
  `dni` int(8) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `celular` int(9) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `direccion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id_paciente`, `id_usuario`, `nombres`, `apellidos`, `dni`, `fecha_nacimiento`, `celular`, `correo`, `direccion`) VALUES
(1, 3, 'Anderson', 'Achulla Huaraca', 75918856, '2002-12-19', 931139271, 'anderachu7@gmail.com', 'A.H Santa rosa Mz.D lt.3'),
(2, 4, 'Israel', 'Huayta Peña', 42472472, '2004-07-14', 931120132, 'correo_prueba@gmail.com', 'Prueba Direccion 2'),
(3, 14, 'Valery', 'Capcha Peña', 73483492, '2000-06-21', 942583934, 'correo_prueba2@gmail.com', 'Prueba Direccion 3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `id_cita` int(11) NOT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `estado` varchar(30) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `culqi_charge_id` varchar(100) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `id_cita`, `monto`, `estado`, `metodo_pago`, `culqi_charge_id`, `fecha_pago`) VALUES
(1, 13, 190.00, 'Pagado', 'Visa', 'chr_test_1nIe5Cvd5kfLmBzp', '2026-05-20 17:58:09'),
(2, 12, 90.00, 'Pagado', 'Visa', 'chr_test_jzNsdta0ariE2Pfs', '2026-05-20 18:00:26'),
(3, 14, 200.00, 'Pagado', 'Visa', 'chr_test_a70RRoxyPAaqibyW', '2026-05-20 23:27:38'),
(4, 15, 90.00, 'Pagado', 'Visa', 'chr_test_IQHB2rRPZ1dLdkwh', '2026-05-21 02:17:49'),
(5, 22, 90.00, 'Pagado', 'Visa', 'chr_test_h0n4enn6iSiyY3Jb', '2026-05-21 20:38:43'),
(6, 23, 90.00, 'Pagado', 'Visa', 'chr_test_2ic50N8xPEWBakBw', '2026-05-21 20:49:23'),
(7, 26, 90.00, 'Pagado', 'Visa', 'chr_test_b8Xh0Ns1epVj2GQ2', '2026-05-22 14:29:26'),
(8, 28, 90.00, 'Pagado', 'Visa', 'chr_test_LDV0uD6EZmKURaAK', '2026-05-22 15:00:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `rol`) VALUES
(1, 'doctor1', '12345', 'admin'),
(2, 'paciente1', '12345', 'paciente'),
(3, 'Anderson', '$2y$10$qM.vpb1Vy3Xum.QMilL7OeUs1DDOjSwtiakfSilrd8wdAb/5CQ3yq', 'paciente'),
(4, 'Israel', '$2y$10$Nl0EcRwe.HmGwcIY2YCg3.LgYR4ADtUKWtZnpR7pb8H7ufN1QgtLe', 'paciente'),
(5, 'Jose', '$2y$10$pEVijnTXO4r3lfxUqquUCejFs6H.VDZ/F2MgTwsSAmsMoMd4Qzyqq', 'medico'),
(6, 'Miguel', '$2y$10$nxrzLGummzcRLr73b28eheJLHGogqe0pIfXuD.mdL9Paml/0xH1Qa', 'medico'),
(9, 'Rusbelt', '$2y$10$PwqGait8r5j4Lo7P2aBMIuHFdP7.4d22SzDanzb2lK8QPH..0nlbu', 'medico'),
(10, 'Joshep', '$2y$10$RXhczx.ixXhq0ZKf8u6fyeVne2TL6qLt8Fk9DVxE5lyDzM9BYIozu', 'medico'),
(11, 'Carla', '$2y$10$l6tnmxdrNEzzTZsfrRw65u3eraFd0eQbRgWc53cuaIwi5MIMpBN3m', 'medico'),
(12, 'Reile', '$2y$10$lmNR7ng0yb6ekdX9iufCFu9hseMezjkjAA/vf5C1mLPJZsrHaMXyW', 'medico'),
(13, 'Cristina', '$2y$10$RcrQcbKqm9a447xxvDptl.J1QGVs5c7EzM75AAR.3rKoMJYa.nJuy', 'medico'),
(14, 'Valery', '$2y$10$aOOrRpdLYfghWwWqF5yyZ.4LdNUX3bPJhhbpumE.3j9tdSoMwNF9S', 'paciente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_medico` (`id_medico`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_especialidad` (`id_especialidad`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id_paciente`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cita` (`id_cita`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_medico`) REFERENCES `medicos` (`id`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`);

--
-- Filtros para la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `medicos_ibfk_1` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id`),
  ADD CONSTRAINT `medicos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `pacientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
