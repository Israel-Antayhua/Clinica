-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-07-2026 a las 08:27:50
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
  `estado` varchar(20) DEFAULT 'Pendiente',
  `monto` decimal(10,2) DEFAULT NULL,
  `estado_pago` varchar(20) DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `id_paciente`, `fecha`, `hora`, `id_medico`, `estado`, `monto`, `estado_pago`) VALUES
(12, 2, '2026-05-22', '22:00:00', 5, 'Confirmado', 90.00, 'Pagado'),
(13, 2, '2026-05-21', '08:00:00', 9, 'Confirmado', 190.00, 'Pagado'),
(14, 2, '2026-05-21', '21:00:00', 9, 'Confirmado', 200.00, 'Pagado'),
(15, 1, '2026-05-25', '15:00:00', 9, 'Confirmado', 90.00, 'Pagado'),
(16, 1, '2026-05-22', '17:00:00', 9, 'Confirmado', 90.00, 'Pagado'),
(17, 2, '2026-05-17', '12:00:00', 9, 'Pendiente', 90.00, 'Pendiente'),
(18, 1, '2026-05-21', '14:00:00', 9, 'Confirmado', 90.00, 'Pagado'),
(19, 1, '2026-05-28', '16:00:00', 9, 'Confirmado', 90.00, 'Pagado'),
(20, 1, '2026-05-20', '14:00:00', 9, 'Confirmado', 90.00, 'Pagado'),
(21, 3, '2026-05-21', '15:00:00', 9, 'Pendiente', 90.00, 'Pendiente'),
(22, 3, '2026-05-25', '17:00:00', 9, 'Confirmado', 90.00, 'Pagado'),
(23, 1, '2026-05-21', '22:00:00', 5, 'Confirmado', 90.00, 'Pagado'),
(24, 2, '2026-05-23', '17:00:00', 5, 'Pendiente', 200.00, 'Pendiente'),
(25, 3, '2026-05-22', '10:00:00', 9, 'Pendiente', 90.00, 'Pendiente'),
(26, 2, '2026-05-22', '22:00:00', 9, 'Confirmado', 90.00, 'Pagado'),
(27, 2, '2026-05-22', '18:00:00', 7, 'Pendiente', 90.00, 'Pendiente'),
(28, 2, '2026-05-22', '15:00:00', 5, 'Confirmado', 90.00, 'Pagado'),
(29, 2, '2026-05-31', '18:00:00', 8, 'Pendiente', 150.00, 'Pendiente'),
(30, 2, '2026-05-20', '17:00:00', 7, 'Pendiente', 220.00, 'Pendiente'),
(31, 2, '2026-05-25', '12:00:00', 9, 'Pendiente', NULL, 'Pendiente'),
(32, 2, '2026-05-23', '18:00:00', 9, 'Pendiente', 100.00, 'Pendiente'),
(33, 2, '2026-05-24', '18:00:00', 9, 'Pendiente', 100.00, 'Pendiente'),
(34, 2, '2026-05-18', '18:00:00', 7, 'Pendiente', 100.00, 'Pendiente'),
(35, 2, '2026-05-25', '18:00:00', 7, 'Pendiente', 100.00, 'Pendiente'),
(36, 2, '2026-05-21', '18:00:00', 9, 'Pendiente', 100.00, 'Pendiente'),
(37, 2, '2026-06-01', '16:00:00', 5, 'Pendiente', 60.00, 'Pendiente'),
(38, 2, '2026-05-23', '11:00:00', 9, 'Pendiente', 100.00, 'Pendiente'),
(39, 2, '2026-05-25', '18:00:00', 9, 'Pendiente', 100.00, 'Pendiente'),
(40, 2, '2026-05-25', '16:00:00', 9, 'Pendiente', 100.00, 'Pendiente'),
(41, 2, '2026-05-25', '10:00:00', 9, 'Pendiente', 100.00, 'Pendiente'),
(42, 2, '2026-05-25', '14:00:00', 9, 'Pendiente', 100.00, 'Pendiente'),
(43, 2, '2026-05-29', '11:00:00', 8, 'Pendiente', 150.00, 'Pendiente'),
(44, 2, '2026-06-04', '11:00:00', 9, 'Pendiente', 100.00, 'Pendiente'),
(45, 2, '2026-05-25', '08:00:00', 9, 'Pendiente', 100.00, 'Pendiente'),
(46, 1, '2026-06-23', '14:00:00', 10, 'Confirmado', 120.00, 'Pagado'),
(47, 1, '2026-06-25', '14:00:00', 9, 'Confirmado', 100.00, 'Pagado'),
(48, 1, '2026-06-23', '14:00:00', 11, 'Confirmado', 300.00, 'Pagado'),
(49, 1, '2026-06-22', '16:00:00', 8, 'Confirmado', 150.00, 'Pagado'),
(50, 1, '2026-06-30', '14:00:00', 11, 'Confirmado', 300.00, 'Pagado'),
(51, 1, '2026-07-23', '16:00:00', 12, 'Pendiente', 60.00, 'Pendiente'),
(52, 2, '2026-07-23', '18:00:00', 11, 'Confirmado', 300.00, 'Pagado'),
(53, 1, '2026-07-14', '08:00:00', 12, 'Cancelada', 60.00, 'Pagado'),
(82, 11, '2026-07-15', '09:00:00', 14, 'Pendiente', 45.00, 'Pendiente'),
(83, 12, '2026-07-15', '10:30:00', 15, 'Pendiente', 150.00, 'Pendiente'),
(84, 13, '2026-07-15', '14:00:00', 16, 'Pendiente', 140.00, 'Pendiente'),
(85, 14, '2026-07-16', '08:30:00', 17, 'Pendiente', 160.00, 'Pendiente'),
(86, 15, '2026-07-16', '11:00:00', 18, 'Pendiente', 150.00, 'Pendiente'),
(87, 16, '2026-07-16', '15:30:00', 19, 'Pendiente', 130.00, 'Pendiente'),
(88, 17, '2026-07-17', '09:30:00', 20, 'Pendiente', 100.00, 'Pendiente'),
(89, 18, '2026-07-17', '13:00:00', 21, 'Pendiente', 120.00, 'Pendiente'),
(90, 19, '2026-07-17', '16:00:00', 22, 'Pendiente', 130.00, 'Pendiente'),
(91, 20, '2026-07-18', '08:00:00', 23, 'Pendiente', 90.00, 'Pendiente'),
(92, 21, '2026-07-18', '10:00:00', 24, 'Pendiente', 180.00, 'Pendiente'),
(93, 1, '2026-07-18', '14:30:00', 25, 'Pendiente', 250.00, 'Pendiente'),
(94, 2, '2026-07-19', '09:00:00', 26, 'Pendiente', 170.00, 'Pendiente'),
(95, 3, '2026-07-19', '11:30:00', 27, 'Pendiente', 180.00, 'Pendiente'),
(96, 4, '2026-07-19', '15:00:00', 28, 'Pendiente', 160.00, 'Pendiente'),
(97, 5, '2026-07-20', '08:30:00', 29, 'Pendiente', 80.00, 'Pendiente'),
(98, 8, '2026-07-21', '09:00:00', 14, 'Pendiente', 45.00, 'Pendiente'),
(99, 10, '2026-07-21', '10:30:00', 15, 'Pendiente', 150.00, 'Pendiente'),
(100, 11, '2026-07-21', '14:00:00', 16, 'Pendiente', 140.00, 'Pendiente'),
(101, 12, '2026-07-22', '09:30:00', 17, 'Pendiente', 160.00, 'Pendiente'),
(102, 13, '2026-07-22', '11:00:00', 18, 'Pendiente', 150.00, 'Pendiente'),
(103, 14, '2026-07-23', '15:30:00', 19, 'Pendiente', 130.00, 'Pendiente'),
(104, 15, '2026-07-23', '17:00:00', 20, 'Pendiente', 100.00, 'Pendiente'),
(105, 16, '2026-07-24', '08:00:00', 21, 'Pendiente', 120.00, 'Pendiente'),
(106, 17, '2026-07-24', '12:30:00', 22, 'Pendiente', 130.00, 'Pendiente'),
(107, 18, '2026-07-25', '09:30:00', 23, 'Pendiente', 90.00, 'Pendiente'),
(108, 19, '2026-07-25', '14:30:00', 24, 'Pendiente', 180.00, 'Pendiente'),
(109, 20, '2026-07-25', '16:00:00', 25, 'Pendiente', 250.00, 'Pendiente'),
(110, 21, '2026-07-15', '08:00:00', 26, 'Pendiente', 170.00, 'Pendiente'),
(111, 1, '2026-07-15', '15:30:00', 27, 'Pendiente', 180.00, 'Pendiente'),
(112, 2, '2026-07-16', '09:00:00', 28, 'Pendiente', 160.00, 'Pendiente'),
(113, 3, '2026-07-16', '16:00:00', 29, 'Pendiente', 80.00, 'Pendiente'),
(114, 4, '2026-07-17', '08:30:00', 14, 'Pendiente', 45.00, 'Pendiente'),
(115, 5, '2026-07-17', '11:30:00', 15, 'Pendiente', 150.00, 'Pendiente'),
(116, 8, '2026-07-18', '09:00:00', 16, 'Pendiente', 140.00, 'Pendiente'),
(117, 10, '2026-07-18', '15:00:00', 17, 'Pendiente', 160.00, 'Pendiente'),
(118, 11, '2026-07-19', '10:00:00', 18, 'Pendiente', 150.00, 'Pendiente'),
(119, 12, '2026-07-19', '14:30:00', 19, 'Pendiente', 130.00, 'Pendiente'),
(120, 13, '2026-07-20', '09:30:00', 20, 'Pendiente', 100.00, 'Pendiente'),
(121, 14, '2026-07-20', '16:00:00', 21, 'Pendiente', 120.00, 'Pendiente'),
(122, 15, '2026-07-21', '08:00:00', 22, 'Pendiente', 130.00, 'Pendiente'),
(123, 16, '2026-07-21', '13:30:00', 23, 'Pendiente', 90.00, 'Pendiente'),
(124, 17, '2026-07-22', '10:00:00', 24, 'Pendiente', 180.00, 'Pendiente'),
(125, 18, '2026-07-22', '15:30:00', 25, 'Pendiente', 250.00, 'Pendiente'),
(126, 19, '2026-07-23', '09:00:00', 26, 'Pendiente', 170.00, 'Pendiente'),
(127, 20, '2026-07-24', '11:00:00', 27, 'Pendiente', 180.00, 'Pendiente'),
(128, 21, '2026-07-25', '14:00:00', 28, 'Pendiente', 160.00, 'Pendiente'),
(129, 1, '2026-07-25', '17:00:00', 29, 'Pendiente', 80.00, 'Pendiente'),
(130, 2, '2026-07-15', '11:00:00', 18, 'Pendiente', 150.00, 'Pendiente'),
(131, 3, '2026-07-15', '16:30:00', 19, 'Pendiente', 130.00, 'Pendiente'),
(132, 4, '2026-07-16', '10:00:00', 20, 'Pendiente', 100.00, 'Pendiente'),
(133, 5, '2026-07-16', '14:00:00', 21, 'Pendiente', 120.00, 'Pendiente'),
(134, 8, '2026-07-16', '17:00:00', 22, 'Pendiente', 130.00, 'Pendiente'),
(135, 10, '2026-07-17', '09:00:00', 23, 'Pendiente', 90.00, 'Pendiente'),
(136, 11, '2026-07-17', '12:30:00', 24, 'Pendiente', 180.00, 'Pendiente'),
(137, 12, '2026-07-17', '15:30:00', 25, 'Pendiente', 250.00, 'Pendiente'),
(138, 13, '2026-07-18', '08:30:00', 26, 'Pendiente', 170.00, 'Pendiente'),
(139, 14, '2026-07-18', '11:30:00', 27, 'Pendiente', 180.00, 'Pendiente'),
(140, 15, '2026-07-18', '16:00:00', 28, 'Pendiente', 160.00, 'Pendiente'),
(141, 16, '2026-07-19', '09:30:00', 29, 'Pendiente', 80.00, 'Pendiente'),
(142, 17, '2026-07-19', '13:00:00', 14, 'Pendiente', 45.00, 'Pendiente'),
(143, 18, '2026-07-19', '16:30:00', 15, 'Pendiente', 150.00, 'Pendiente'),
(144, 19, '2026-07-20', '08:00:00', 16, 'Pendiente', 140.00, 'Pendiente'),
(145, 20, '2026-07-20', '11:00:00', 17, 'Pendiente', 160.00, 'Pendiente'),
(146, 21, '2026-07-20', '15:00:00', 18, 'Pendiente', 150.00, 'Pendiente'),
(147, 1, '2026-07-21', '09:00:00', 19, 'Pendiente', 130.00, 'Pendiente'),
(148, 2, '2026-07-21', '12:00:00', 20, 'Pendiente', 100.00, 'Pendiente'),
(149, 3, '2026-07-21', '16:00:00', 21, 'Pendiente', 120.00, 'Pendiente'),
(150, 4, '2026-07-22', '08:30:00', 22, 'Pendiente', 130.00, 'Pendiente'),
(151, 5, '2026-07-22', '11:30:00', 23, 'Pendiente', 90.00, 'Pendiente'),
(152, 8, '2026-07-22', '15:30:00', 24, 'Pendiente', 180.00, 'Pendiente'),
(153, 10, '2026-07-23', '09:30:00', 25, 'Pendiente', 250.00, 'Pendiente'),
(154, 11, '2026-07-23', '13:00:00', 26, 'Pendiente', 170.00, 'Pendiente'),
(155, 12, '2026-07-23', '16:30:00', 27, 'Pendiente', 180.00, 'Pendiente'),
(156, 13, '2026-07-24', '08:00:00', 28, 'Pendiente', 160.00, 'Pendiente'),
(157, 14, '2026-07-24', '11:00:00', 29, 'Pendiente', 80.00, 'Pendiente'),
(158, 15, '2026-07-25', '09:00:00', 14, 'Pendiente', 45.00, 'Pendiente'),
(159, 16, '2026-07-25', '15:30:00', 15, 'Pendiente', 150.00, 'Pendiente'),
(160, 17, '2026-07-15', '08:30:00', 24, 'Pendiente', 180.00, 'Pendiente'),
(161, 18, '2026-07-15', '12:00:00', 25, 'Pendiente', 250.00, 'Pendiente'),
(162, 19, '2026-07-15', '16:00:00', 26, 'Pendiente', 170.00, 'Pendiente'),
(163, 20, '2026-07-16', '09:30:00', 27, 'Pendiente', 180.00, 'Pendiente'),
(164, 21, '2026-07-16', '13:30:00', 28, 'Pendiente', 160.00, 'Pendiente'),
(165, 1, '2026-07-16', '17:00:00', 29, 'Pendiente', 80.00, 'Pendiente'),
(166, 2, '2026-07-17', '08:00:00', 14, 'Pendiente', 45.00, 'Pendiente'),
(167, 3, '2026-07-17', '10:30:00', 15, 'Pendiente', 150.00, 'Pendiente'),
(168, 4, '2026-07-17', '14:30:00', 16, 'Pendiente', 140.00, 'Pendiente'),
(169, 5, '2026-07-18', '09:00:00', 17, 'Pendiente', 160.00, 'Pendiente'),
(170, 8, '2026-07-18', '12:30:00', 18, 'Pendiente', 150.00, 'Pendiente'),
(171, 10, '2026-07-18', '16:30:00', 19, 'Pendiente', 130.00, 'Pendiente'),
(172, 11, '2026-07-19', '08:30:00', 20, 'Pendiente', 100.00, 'Pendiente'),
(173, 12, '2026-07-19', '11:00:00', 21, 'Pendiente', 120.00, 'Pendiente'),
(174, 13, '2026-07-19', '15:30:00', 22, 'Pendiente', 130.00, 'Pendiente'),
(175, 14, '2026-07-20', '09:00:00', 23, 'Pendiente', 90.00, 'Pendiente'),
(176, 15, '2026-07-20', '13:00:00', 24, 'Pendiente', 180.00, 'Pendiente'),
(177, 16, '2026-07-20', '16:30:00', 25, 'Pendiente', 250.00, 'Pendiente'),
(178, 17, '2026-07-21', '08:00:00', 26, 'Pendiente', 170.00, 'Pendiente'),
(179, 18, '2026-07-21', '10:30:00', 27, 'Pendiente', 180.00, 'Pendiente'),
(180, 19, '2026-07-21', '14:30:00', 28, 'Pendiente', 160.00, 'Pendiente'),
(181, 20, '2026-07-22', '09:30:00', 29, 'Pendiente', 80.00, 'Pendiente'),
(182, 21, '2026-07-22', '13:30:00', 14, 'Pendiente', 45.00, 'Pendiente'),
(183, 1, '2026-07-22', '16:30:00', 15, 'Pendiente', 150.00, 'Pendiente'),
(184, 2, '2026-07-23', '08:30:00', 16, 'Pendiente', 140.00, 'Pendiente'),
(185, 3, '2026-07-23', '11:30:00', 17, 'Pendiente', 160.00, 'Pendiente'),
(186, 4, '2026-07-24', '14:00:00', 18, 'Pendiente', 150.00, 'Pendiente'),
(187, 5, '2026-07-24', '16:30:00', 19, 'Pendiente', 130.00, 'Pendiente'),
(188, 8, '2026-07-25', '10:00:00', 20, 'Pendiente', 100.00, 'Pendiente'),
(189, 10, '2026-07-25', '13:30:00', 21, 'Pendiente', 120.00, 'Pendiente'),
(190, 11, '2026-07-26', '08:00:00', 22, 'Pendiente', 130.00, 'Pendiente'),
(191, 12, '2026-07-26', '09:30:00', 23, 'Pendiente', 90.00, 'Pendiente'),
(192, 13, '2026-07-26', '11:00:00', 24, 'Pendiente', 180.00, 'Pendiente'),
(193, 14, '2026-07-26', '14:00:00', 25, 'Pendiente', 250.00, 'Pendiente'),
(194, 15, '2026-07-26', '16:30:00', 26, 'Pendiente', 170.00, 'Pendiente'),
(195, 16, '2026-07-27', '08:30:00', 27, 'Pendiente', 180.00, 'Pendiente'),
(196, 17, '2026-07-27', '10:00:00', 28, 'Pendiente', 160.00, 'Pendiente'),
(197, 18, '2026-07-27', '12:30:00', 29, 'Pendiente', 80.00, 'Pendiente'),
(198, 19, '2026-07-27', '15:00:00', 14, 'Pendiente', 45.00, 'Pendiente'),
(199, 20, '2026-07-27', '17:00:00', 15, 'Pendiente', 150.00, 'Pendiente'),
(200, 21, '2026-07-28', '08:00:00', 16, 'Pendiente', 140.00, 'Pendiente'),
(201, 1, '2026-07-28', '09:30:00', 17, 'Pendiente', 160.00, 'Pendiente'),
(202, 2, '2026-07-28', '11:30:00', 18, 'Pendiente', 150.00, 'Pendiente'),
(203, 3, '2026-07-28', '14:30:00', 19, 'Pendiente', 130.00, 'Pendiente'),
(204, 4, '2026-07-28', '16:30:00', 20, 'Pendiente', 100.00, 'Pendiente'),
(205, 5, '2026-07-29', '08:30:00', 21, 'Pendiente', 120.00, 'Pendiente'),
(206, 8, '2026-07-29', '10:00:00', 22, 'Pendiente', 130.00, 'Pendiente'),
(207, 10, '2026-07-29', '12:00:00', 23, 'Pendiente', 90.00, 'Pendiente'),
(208, 11, '2026-07-29', '15:00:00', 24, 'Pendiente', 180.00, 'Pendiente'),
(209, 12, '2026-07-29', '17:00:00', 25, 'Pendiente', 250.00, 'Pendiente'),
(210, 13, '2026-07-30', '08:00:00', 26, 'Pendiente', 170.00, 'Pendiente'),
(211, 14, '2026-07-30', '09:30:00', 27, 'Pendiente', 180.00, 'Pendiente'),
(212, 15, '2026-07-30', '11:30:00', 28, 'Pendiente', 160.00, 'Pendiente'),
(213, 16, '2026-07-30', '14:00:00', 29, 'Pendiente', 80.00, 'Pendiente'),
(214, 17, '2026-07-30', '16:30:00', 14, 'Pendiente', 45.00, 'Pendiente'),
(215, 18, '2026-07-31', '08:30:00', 15, 'Pendiente', 150.00, 'Pendiente'),
(216, 19, '2026-07-31', '10:30:00', 16, 'Pendiente', 140.00, 'Pendiente'),
(217, 20, '2026-07-31', '12:30:00', 17, 'Pendiente', 160.00, 'Pendiente'),
(218, 21, '2026-07-31', '15:30:00', 18, 'Pendiente', 150.00, 'Pendiente'),
(219, 1, '2026-07-31', '17:00:00', 19, 'Pendiente', 130.00, 'Pendiente'),
(220, 2, '2026-08-01', '08:00:00', 20, 'Pendiente', 100.00, 'Pendiente'),
(221, 3, '2026-08-01', '09:30:00', 21, 'Pendiente', 120.00, 'Pendiente'),
(222, 4, '2026-08-01', '11:00:00', 22, 'Pendiente', 130.00, 'Pendiente'),
(223, 5, '2026-08-01', '13:30:00', 23, 'Pendiente', 90.00, 'Pendiente'),
(224, 8, '2026-08-01', '15:00:00', 24, 'Pendiente', 180.00, 'Pendiente'),
(225, 10, '2026-08-01', '16:30:00', 25, 'Pendiente', 250.00, 'Pendiente'),
(226, 11, '2026-08-01', '17:30:00', 26, 'Pendiente', 170.00, 'Pendiente'),
(227, 12, '2026-08-01', '18:00:00', 27, 'Pendiente', 180.00, 'Pendiente'),
(228, 13, '2026-08-01', '18:30:00', 28, 'Pendiente', 160.00, 'Pendiente'),
(229, 14, '2026-08-01', '19:00:00', 29, 'Pendiente', 80.00, 'Pendiente'),
(230, 15, '2026-07-26', '18:00:00', 14, 'Pendiente', 45.00, 'Pendiente'),
(231, 16, '2026-07-27', '18:30:00', 15, 'Pendiente', 150.00, 'Pendiente'),
(232, 17, '2026-07-28', '18:00:00', 16, 'Pendiente', 140.00, 'Pendiente'),
(233, 18, '2026-07-29', '18:30:00', 17, 'Pendiente', 160.00, 'Pendiente'),
(234, 19, '2026-07-30', '18:00:00', 18, 'Pendiente', 150.00, 'Pendiente'),
(235, 20, '2026-07-31', '18:30:00', 19, 'Pendiente', 130.00, 'Pendiente'),
(236, 21, '2026-08-01', '19:30:00', 20, 'Pendiente', 100.00, 'Pendiente'),
(237, 1, '2026-08-01', '20:00:00', 21, 'Pendiente', 120.00, 'Pendiente'),
(238, 2, '2026-08-01', '20:30:00', 22, 'Pendiente', 130.00, 'Pendiente'),
(239, 3, '2026-08-01', '21:00:00', 23, 'Pendiente', 90.00, 'Pendiente');

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
(1, 'Cardiología', 'Activo', 130.00),
(2, 'Dermatología', 'Activo', 140.00),
(3, 'Medicina General', 'Activo', 60.00),
(4, 'Pediatría', 'Activo', 100.00),
(5, 'Ginecología', 'Activo', 120.00),
(6, 'Oncología', 'Activo', 220.00),
(7, 'Reumatología', 'Activo', 160.00),
(8, 'Medicina Interna', 'Activo', 120.00),
(9, 'Traumatología', 'Activo', 130.00),
(10, 'Gestion de lavadero', 'Activo', 300.00),
(11, 'Odontologia', 'Activo', 110.00),
(12, 'Neurocirugia', 'Activo', 230.00),
(13, 'Ortopedia', 'Activo', 60.00),
(14, 'Cardiologías', 'Activo', 45.00),
(15, 'Neurología', 'Activo', 150.00),
(16, 'Endocrinología', 'Activo', 140.00),
(17, 'Gastroenterología', 'Activo', 160.00),
(18, 'Neumología', 'Activo', 150.00),
(19, 'Urología', 'Activo', 130.00),
(20, 'Oftalmología', 'Activo', 100.00),
(21, 'Otorrinolaringología', 'Activo', 120.00),
(22, 'Psiquiatría', 'Activo', 130.00),
(23, 'Psicología Clínica', 'Activo', 90.00),
(24, 'Cirugía General', 'Activo', 180.00),
(25, 'Cirugía Plástica', 'Activo', 250.00),
(26, 'Infectología', 'Activo', 170.00),
(27, 'Hematología', 'Activo', 180.00),
(28, 'Nefrología', 'Activo', 160.00),
(29, 'Medicina Familiar', 'Activo', 80.00);

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
(5, 5, 'Jose', 3, '943402321', 'Activo'),
(6, 6, 'Miguel', 1, '923323999', 'Activo'),
(7, 9, 'Rusbelt', 4, '924321122', 'Activo'),
(8, 10, 'Joshep', 1, '999232922', 'Activo'),
(9, 11, 'Carla', 4, '924323021', 'Activo'),
(10, 12, 'Reile', 5, '924242923', 'Activo'),
(11, 13, 'Cristina', 10, '924859549', 'Activo'),
(12, 28, 'Karla', 13, '931139272', 'Activo'),
(13, 49, 'Jesus', 12, '929320302', 'Activo'),
(14, 50, 'Sofía Mendoza', 14, '974222333', 'Activo'),
(15, 51, 'Andrés Cabrera', 15, '973333444', 'Activo'),
(16, 52, 'Valeria Campos', 16, '972444555', 'Activo'),
(17, 53, 'Fernando Ruiz', 17, '971555666', 'Activo'),
(18, 54, 'Carolina Vega', 18, '970666777', 'Activo'),
(19, 55, 'Martín Herrera', 19, '969777888', 'Activo'),
(20, 56, 'Gabriela Ortiz', 20, '968888999', 'Activo'),
(21, 57, 'Eduardo Silva', 21, '967999111', 'Activo'),
(22, 58, 'Natalia Romero', 22, '966111222', 'Activo'),
(23, 59, 'César Aguilar', 23, '965222333', 'Activo'),
(24, 60, 'Francisco León', 24, '964333444', 'Activo'),
(25, 61, 'Andrea Morales', 25, '963444555', 'Activo'),
(26, 62, 'Héctor Vargas', 26, '962555666', 'Activo'),
(27, 63, 'Beatriz Salinas', 27, '961666777', 'Activo'),
(28, 64, 'Manuel Cárdenas', 28, '960777888', 'Activo'),
(29, 65, 'Gabriela Ríos', 29, '959888999', 'Activo');

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
  `direccion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id_paciente`, `id_usuario`, `nombres`, `apellidos`, `dni`, `fecha_nacimiento`, `celular`, `direccion`) VALUES
(1, 3, 'Anderson', 'Achulla Huaraca', 75918856, '2002-12-19', 931139271, 'A.H Santa rosa Mz.D lt.4'),
(2, 4, 'Israel', 'Huayta Peña', 42472472, '2004-07-14', 931120131, 'Hola'),
(3, 14, 'Valery', 'Capcha Peña', 73483492, '2000-06-21', 942583934, 'Prueba Direccion 3'),
(4, 25, 'Miguel', 'Espinoza Caman', 72482942, '2004-02-17', 934353271, 'Prueba Direccion 4'),
(5, 26, 'Leyton', 'Garcia Linares', 47224823, '2026-06-26', 929320302, 'Prueba Direccion 27'),
(8, 45, 'Andersona', 'Achulla Huaraca', 42472471, '2026-07-09', 931139271, 'A.H Santa rosa Mz.D lt.3'),
(10, 48, 'Andersona', 'Achulla Huaraca', 42472475, '2026-07-02', 931139271, 'A.H Santa rosa Mz.D lt.3'),
(11, 66, 'Carlos', 'Ramirez Soto', 74839201, '1998-03-15', 932456781, 'Av. Los Olivos Mz.B Lt.5'),
(12, 67, 'Mariana', 'Flores Rojas', 75648392, '2001-08-22', 934567821, 'Jr. Lima 245'),
(13, 68, 'Diego', 'Mendoza Vargas', 72345689, '1999-11-05', 935678912, 'A.H Villa Esperanza'),
(14, 69, 'Camila', 'Torres Medina', 74658923, '2003-04-18', 936789123, 'Calle San Martín 120'),
(15, 70, 'José', 'Castillo Ruiz', 73482915, '1997-09-30', 937891234, 'Av. Primavera 456'),
(16, 71, 'Luciana', 'Herrera Peña', 76543218, '2002-01-12', 938912345, 'Jr. Amazonas 321'),
(17, 72, 'Mateo', 'Salazar Castro', 75432198, '2005-06-25', 939123456, 'Mz.C Lt.8 Santa Rosa'),
(18, 73, 'Daniela', 'Vega Morales', 74561239, '2000-10-10', 930234567, 'Av. Central 890'),
(19, 74, 'Renzo', 'Navarro Silva', 76894521, '1996-12-03', 931345678, 'Calle Los Pinos 55'),
(20, 75, 'Andrea', 'Campos León', 71234569, '2004-05-27', 932567890, 'Jr. Tacna 640'),
(21, 76, 'Sebastián', 'Aguilar Ramos', 78945612, '1998-07-19', 933678901, 'A.H Nueva Esperanza');

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
(8, 28, 90.00, 'Pagado', 'Visa', 'chr_test_LDV0uD6EZmKURaAK', '2026-05-22 15:00:29'),
(9, 20, 90.00, 'Pagado', 'Visa', 'chr_test_TmPqnzh4AUpkhh2D', '2026-06-19 12:01:25'),
(10, 46, 120.00, 'Pagado', 'Visa', 'chr_test_EKVk71mmtsDTn7Fo', '2026-06-19 21:25:23'),
(11, 49, 150.00, 'Pagado', 'Visa', 'chr_test_UU9qHkFG5mczOCH2', '2026-06-19 21:27:42'),
(12, 52, 300.00, 'Pagado', 'Visa', 'chr_test_LRmQSzxheqneTlta', '2026-07-13 23:32:36'),
(13, 53, 60.00, 'Pagado', 'Visa', 'chr_test_McSMkyZq7J89WpOs', '2026-07-13 23:50:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `intentos_fallidos` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `bloqueado_hasta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `correo`, `password`, `rol`, `intentos_fallidos`, `bloqueado_hasta`) VALUES
(3, 'anderachu7@gmail.com', '$2y$10$qM.vpb1Vy3Xum.QMilL7OeUs1DDOjSwtiakfSilrd8wdAb/5CQ3yq', 'paciente', 0, NULL),
(4, 'israel@gmail.com', '$2y$10$Nl0EcRwe.HmGwcIY2YCg3.LgYR4ADtUKWtZnpR7pb8H7ufN1QgtLe', 'paciente', 0, NULL),
(5, 'Jose1@gmail.com', '$2y$10$pEVijnTXO4r3lfxUqquUCejFs6H.VDZ/F2MgTwsSAmsMoMd4Qzyqq', 'administrador', 0, NULL),
(6, 'Miguel@gmail.com', '$2y$10$nxrzLGummzcRLr73b28eheJLHGogqe0pIfXuD.mdL9Paml/0xH1Qa', 'medico', 0, '2026-07-13 22:55:12'),
(9, 'Rusbelt@gmail.com', '$2y$10$PwqGait8r5j4Lo7P2aBMIuHFdP7.4d22SzDanzb2lK8QPH..0nlbu', 'medico', 0, NULL),
(10, 'Joshep@gmail.com', '$2y$10$RXhczx.ixXhq0ZKf8u6fyeVne2TL6qLt8Fk9DVxE5lyDzM9BYIozu', 'medico', 0, NULL),
(11, 'Carla@gmail.com', '$2y$10$l6tnmxdrNEzzTZsfrRw65u3eraFd0eQbRgWc53cuaIwi5MIMpBN3m', 'medico', 0, NULL),
(12, 'Reile@gmail.com', '$2y$10$lmNR7ng0yb6ekdX9iufCFu9hseMezjkjAA/vf5C1mLPJZsrHaMXyW', 'medico', 0, NULL),
(13, 'Cristina@gmail.com', '$2y$10$RcrQcbKqm9a447xxvDptl.J1QGVs5c7EzM75AAR.3rKoMJYa.nJuy', 'medico', 0, NULL),
(14, 'Valery@gmail.com', '$2y$10$aOOrRpdLYfghWwWqF5yyZ.4LdNUX3bPJhhbpumE.3j9tdSoMwNF9S', 'paciente', 0, NULL),
(25, 'miguel45@gmail.com', '$2y$10$ad5y./V0davPllu1RjJuHu9JL4flqkAyEiFwjklz5a/OdY1XjaPAq', 'paciente', 0, NULL),
(26, 'sofia.mendoza@gmail.com', '$2y$10$8ExTTGM3V8.GGp1OCX66yugkOy9CcfMpM87QqwYh7BlUOU4QH.H7C', 'paciente', 0, NULL),
(28, 'valeria.campos@gmail.com', '$2y$10$NWqKnSKSQLUDOW8r7eL3K.q7CO9hhVYf4RhOZY946a/TmdSPKjb.q', 'medico', 0, NULL),
(36, 'francisco.leon@gmail.com', '$2y$10$zgMylSY2Ulyd4M95QRvY5eXQe277gAntESCgjh8QLa44KqtCqhOqK', 'paciente', 0, NULL),
(39, 'beatriz.salinas@gmail.com', '$2y$10$OPm1qc464Xlo8ZKyy/luXu17fkMqhHsX3DRTzxk96NXnCZppHEBoC', 'paciente', 0, NULL),
(41, 'gabriela.rios@gmail.com', '$2y$10$qlgXaVX.e7qzREvLJ9h7EOft5DGxOsiTX.ODiOLUDRvr2cKu1Z0.q', 'paciente', 0, NULL),
(45, 'Carla11@gmail.com', '$2y$10$elR/L1/noLb9lc.JVJuJze8pSNEmmMlhibIzDj5OdAbzqIgG9igmG', 'paciente', 0, NULL),
(48, 'anderachu76@gmail.com', '$2y$10$g0qUytc902v4JIZ9jQe8FeIw/ibnzATcuLXj/OYorG5PK4X.tsa2i', 'paciente', 0, NULL),
(49, 'anderachu79@gmail.com', '$2y$10$kF2J7qWCQNk6okjXAIFLTus5En4PMHX9tNDEspdqI.cWCJM.iK1JG', 'medico', 0, NULL),
(50, 'medico11@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(51, 'medico12@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(52, 'medico13@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(53, 'medico14@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(54, 'medico15@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(55, 'medico16@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(56, 'medico17@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(57, 'medico18@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(58, 'medico19@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(59, 'medico20@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(60, 'medico21@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(61, 'medico22@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(62, 'medico23@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(63, 'medico24@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(64, 'medico25@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(65, 'medico26@clinica.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Medico', 0, NULL),
(66, 'carlos.ramirez@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Paciente', 0, NULL),
(67, 'mariana.flores@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Paciente', 0, NULL),
(68, 'diego.mendoza@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Paciente', 0, NULL),
(69, 'camila.torres@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Paciente', 0, NULL),
(70, 'jose.castillo@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Paciente', 0, NULL),
(71, 'luciana.herrera@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Paciente', 0, '2026-07-14 01:19:12'),
(72, 'mateo.salazar@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Paciente', 0, NULL),
(73, 'daniela.vega@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQFQxjH1t5zQ1eG', 'Paciente', 0, NULL),
(74, 'renzo.navarro@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Paciente', 0, NULL),
(75, 'andrea.campos@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Paciente', 0, NULL),
(76, 'sebastian.aguilar@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9lln4JfQqFQxjH1t5zQ1eG', 'Paciente', 0, NULL);

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
  ADD UNIQUE KEY `dni` (`dni`),
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
  ADD UNIQUE KEY `usuario` (`correo`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `correo_2` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

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
