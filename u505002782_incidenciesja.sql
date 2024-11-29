-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 29-11-2024 a las 09:38:22
-- Versión del servidor: 10.11.9-MariaDB
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u505002782_incidenciesja`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `id_usuari_creacio` int(11) DEFAULT NULL,
  `id_usuari_receptor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espais`
--

CREATE TABLE `espais` (
  `id` int(11) NOT NULL,
  `aula` int(11) DEFAULT NULL,
  `pis` int(11) DEFAULT NULL,
  `espai` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `espais`
--

INSERT INTO `espais` (`id`, `aula`, `pis`, `espai`) VALUES
(11, 1111, -1, 'DEVELOPERS'),
(12, 1112, 0, 'PROVA'),
(13, NULL, NULL, 'Biblioteca');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencia`
--

CREATE TABLE `incidencia` (
  `id_incidencia` int(11) NOT NULL,
  `titol` varchar(200) NOT NULL,
  `descripcio` text NOT NULL,
  `id_usuari` int(11) DEFAULT NULL,
  `id_tipus_incidencia` int(11) DEFAULT NULL,
  `prioritat` enum('baixa','mitjana','alta') NOT NULL,
  `estat` enum('pendent','enproces','resolta') DEFAULT 'pendent',
  `data_creacio` timestamp NOT NULL DEFAULT current_timestamp(),
  `darrera_modificacio` timestamp NULL DEFAULT NULL,
  `id_usuari_creacio` int(11) DEFAULT NULL,
  `upload` varchar(5000) DEFAULT NULL,
  `espai` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `incidencia`
--

INSERT INTO `incidencia` (`id_incidencia`, `titol`, `descripcio`, `id_usuari`, `id_tipus_incidencia`, `prioritat`, `estat`, `data_creacio`, `darrera_modificacio`, `id_usuari_creacio`, `upload`, `espai`) VALUES
(95, 'Prova Correu', 'EYY SI', 32, 1, 'alta', 'pendent', '2024-11-29 07:41:39', '2024-11-29 08:42:45', 13, '[]', 13),
(96, 'test', 'sadfas', 32, 1, 'mitjana', 'pendent', '2024-11-29 08:26:19', NULL, 32, '', 11),
(97, 'Aleix peix', 'hola', 32, 1, 'mitjana', 'pendent', '2024-11-29 08:39:07', NULL, 32, '[]', 11),
(98, 'Aleix peix', 'hola', 32, 1, 'mitjana', 'pendent', '2024-11-29 08:39:39', NULL, 32, '', 11),
(99, 'testgrafic', 'wewer', NULL, 3, 'mitjana', 'pendent', '2024-11-29 08:52:35', NULL, 15, '', 12),
(100, 'dasfadsf', 'dsafadsf', 32, 1, 'baixa', 'pendent', '2024-11-29 08:55:01', '2024-11-29 09:12:20', 13, '{\"0\":\"dasfadsf_0.png\",\"2\":\"dasfadsf_0.webp\"}', 11),
(101, 'dasfadsf', 'dsafadsf', 32, 1, 'baixa', 'pendent', '2024-11-29 08:57:39', NULL, 13, '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `missatges`
--

CREATE TABLE `missatges` (
  `id` int(11) NOT NULL,
  `emisor_id` int(11) NOT NULL,
  `receptor_id` int(11) NOT NULL,
  `missatge` varchar(5000) NOT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp(),
  `id_chat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacions`
--

CREATE TABLE `notificacions` (
  `id` int(11) NOT NULL,
  `id_incidencia` int(11) NOT NULL,
  `tipus` varchar(50) NOT NULL,
  `data` timestamp NULL DEFAULT current_timestamp(),
  `llegida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `notificacions`
--

INSERT INTO `notificacions` (`id`, `id_incidencia`, `tipus`, `data`, `llegida`) VALUES
(89, 95, 'creada', '2024-11-29 07:41:39', 0),
(90, 96, 'creada', '2024-11-29 08:26:19', 0),
(91, 97, 'creada', '2024-11-29 08:39:07', 0),
(92, 98, 'creada', '2024-11-29 08:39:39', 0),
(93, 95, 'modificada', '2024-11-29 08:42:45', 1),
(94, 99, 'creada', '2024-11-29 08:52:35', 0),
(95, 100, 'creada', '2024-11-29 08:55:01', 0),
(96, 101, 'creada', '2024-11-29 08:57:39', 0),
(97, 100, 'modificada', '2024-11-29 09:01:25', 0),
(98, 100, 'modificada', '2024-11-29 09:10:51', 0),
(99, 100, 'modificada', '2024-11-29 09:12:20', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipus_incidencia`
--

CREATE TABLE `tipus_incidencia` (
  `id_tipus_incidencia` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `descripcio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipus_incidencia`
--

INSERT INTO `tipus_incidencia` (`id_tipus_incidencia`, `nom`, `descripcio`) VALUES
(1, 'Informatica', 'Tasques de informatica'),
(2, 'Electricitat', 'Tasques de electicitat'),
(3, 'Calefaccio', 'Tasques de calefaccio'),
(4, 'Fusteria', 'Tasques de fusteria'),
(5, 'Ferres', 'Tasques de ferro'),
(6, 'Obres', 'Tasques de obres'),
(7, 'Audiovisual', 'Tasques de audiovisual'),
(8, 'Equips de seguretat', 'Tasques de equips de seguretat'),
(9, 'Neteja clavegueram', 'Tasques de neteja clavegueram');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuaris`
--

CREATE TABLE `usuaris` (
  `id_usuari` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `cognoms` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contrasenya` varchar(255) NOT NULL,
  `rol` enum('usuari','administrador','tecnic') DEFAULT 'usuari',
  `data_creacio` timestamp NOT NULL DEFAULT current_timestamp(),
  `imatge` varchar(1000) NOT NULL,
  `telefono` int(11) DEFAULT NULL,
  `id_sector` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuaris`
--

INSERT INTO `usuaris` (`id_usuari`, `nom`, `cognoms`, `email`, `contrasenya`, `rol`, `data_creacio`, `imatge`, `telefono`, `id_sector`, `token`, `token_expira`) VALUES
(13, 'Aleix', 'Ribalta', 'aleixribalta04@gmail.com', '$2y$10$dM5BAFqB5ci0SA9FgrU2buSCOiOKZPcE8RODCmIGyb407TySi2ZwC', 'administrador', '2024-10-25 13:29:26', 'aleix_ribalta.png', 640566506, 1, NULL, NULL),
(14, 'Biel', 'Nadal Gigachad', 'biel@gigachad.cat', '', 'tecnic', '2024-10-25 15:34:26', 'biel_nadal_gigachad.webp', 123456789, 1, NULL, NULL),
(15, 'Berlingo', 'Berlingo', 'berlingo@berlingo.cat', '$2y$10$K1cJYxSsAZOEjkXu.o2Wte8H1JVehcICtgd.aVXZ.DazWisZgdakG', 'usuari', '2024-10-25 15:35:58', 'berlingo_berlingo.webp', 2147483647, 1, NULL, NULL),
(32, 'Marc', 'Sanchez Tasies', 'sanxisquad@gmail.com', '$2y$10$rs.va4u7wHyPaC1VAKb7Te5m6rEC3EY1zGFqg/6XDOxJA72DH75X2', 'administrador', '2024-10-30 11:31:00', 'marc_sanchez.webp', 606952142, 1, NULL, '2024-11-28 12:36:16'),
(37, 'Ibrahim', 'Kadri', 'ibra@ibra.cat', '$2y$10$Bz8JY1P/cUsS6191h6FR9.B/RvpgCmd6DpRm3hAMRqtFb9EXr8yoS', 'administrador', '2024-11-06 09:18:28', 'ibrahim_kadri.webp', 1231233, 1, NULL, NULL),
(52, 'Ismael', 'Montoro', 'montoro@hotmail.com', '$2y$10$NbBkxfkxr4Ty.69Fsf7y3.U5zfZCWZngzJiuVn0W2W8iACGQ8YkUi', 'administrador', '2024-11-14 10:58:34', 'default.png', 123456, 3, NULL, NULL),
(54, 'Josep Maria', 'Llubes', 'llubes@llubes.com', '$2y$10$ujvWZc/slXEVFrNcEzx8Au4GQubkd.y7ePsI0URCEtX/HNwvT2dcy', 'administrador', '2024-11-28 07:25:14', 'josep_maria_llubes.webp', 123456789, 3, NULL, NULL),
(61, 'Ying', 'Yang', 'msancheztasies@gmail.com', '$2y$10$JNHMkOSxt7eyXkWO7XBSIOd3qySXbw2NwLnd7jurkFxD9QVP8i4qS', 'tecnic', '2024-11-29 08:49:30', 'ying_yang.webp', 12345678, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuaris_in_sector`
--

CREATE TABLE `usuaris_in_sector` (
  `id_usuari` int(11) DEFAULT NULL,
  `id_tipus` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuaris_in_sector`
--

INSERT INTO `usuaris_in_sector` (`id_usuari`, `id_tipus`) VALUES
(32, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuari_creacio` (`id_usuari_creacio`),
  ADD KEY `id_usuari_receptor` (`id_usuari_receptor`);

--
-- Indices de la tabla `espais`
--
ALTER TABLE `espais`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `incidencia`
--
ALTER TABLE `incidencia`
  ADD PRIMARY KEY (`id_incidencia`),
  ADD KEY `id_usuari` (`id_usuari`),
  ADD KEY `id_tipus_incidencia` (`id_tipus_incidencia`),
  ADD KEY `incidencia` (`id_usuari_creacio`),
  ADD KEY `fk_incidencia_espai` (`espai`);

--
-- Indices de la tabla `missatges`
--
ALTER TABLE `missatges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emisor_id` (`emisor_id`),
  ADD KEY `receptor_id` (`receptor_id`),
  ADD KEY `id_chat` (`id_chat`);

--
-- Indices de la tabla `notificacions`
--
ALTER TABLE `notificacions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_incidencia` (`id_incidencia`);

--
-- Indices de la tabla `tipus_incidencia`
--
ALTER TABLE `tipus_incidencia`
  ADD PRIMARY KEY (`id_tipus_incidencia`);

--
-- Indices de la tabla `usuaris`
--
ALTER TABLE `usuaris`
  ADD PRIMARY KEY (`id_usuari`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_sector` (`id_sector`);

--
-- Indices de la tabla `usuaris_in_sector`
--
ALTER TABLE `usuaris_in_sector`
  ADD KEY `usuaris_in_sector_ibfk_1` (`id_usuari`),
  ADD KEY `usuaris_in_sector_ibfk_2` (`id_tipus`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `espais`
--
ALTER TABLE `espais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `incidencia`
--
ALTER TABLE `incidencia`
  MODIFY `id_incidencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `missatges`
--
ALTER TABLE `missatges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `notificacions`
--
ALTER TABLE `notificacions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT de la tabla `tipus_incidencia`
--
ALTER TABLE `tipus_incidencia`
  MODIFY `id_tipus_incidencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuaris`
--
ALTER TABLE `usuaris`
  MODIFY `id_usuari` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`id_usuari_creacio`) REFERENCES `usuaris` (`id_usuari`),
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`id_usuari_receptor`) REFERENCES `usuaris` (`id_usuari`);

--
-- Filtros para la tabla `incidencia`
--
ALTER TABLE `incidencia`
  ADD CONSTRAINT `fk_incidencia_espai` FOREIGN KEY (`espai`) REFERENCES `espais` (`id`),
  ADD CONSTRAINT `incidencia` FOREIGN KEY (`id_usuari_creacio`) REFERENCES `usuaris` (`id_usuari`),
  ADD CONSTRAINT `incidencia_ibfk_1` FOREIGN KEY (`id_usuari`) REFERENCES `usuaris` (`id_usuari`),
  ADD CONSTRAINT `incidencia_ibfk_2` FOREIGN KEY (`id_tipus_incidencia`) REFERENCES `tipus_incidencia` (`id_tipus_incidencia`);

--
-- Filtros para la tabla `missatges`
--
ALTER TABLE `missatges`
  ADD CONSTRAINT `missatges_ibfk_1` FOREIGN KEY (`emisor_id`) REFERENCES `usuaris` (`id_usuari`),
  ADD CONSTRAINT `missatges_ibfk_2` FOREIGN KEY (`receptor_id`) REFERENCES `usuaris` (`id_usuari`),
  ADD CONSTRAINT `missatges_ibfk_3` FOREIGN KEY (`id_chat`) REFERENCES `chats` (`id`);

--
-- Filtros para la tabla `notificacions`
--
ALTER TABLE `notificacions`
  ADD CONSTRAINT `notificacions_ibfk_1` FOREIGN KEY (`id_incidencia`) REFERENCES `incidencia` (`id_incidencia`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuaris`
--
ALTER TABLE `usuaris`
  ADD CONSTRAINT `fk_sector` FOREIGN KEY (`id_sector`) REFERENCES `tipus_incidencia` (`id_tipus_incidencia`);

--
-- Filtros para la tabla `usuaris_in_sector`
--
ALTER TABLE `usuaris_in_sector`
  ADD CONSTRAINT `usuaris_in_sector_ibfk_1` FOREIGN KEY (`id_usuari`) REFERENCES `usuaris` (`id_usuari`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuaris_in_sector_ibfk_2` FOREIGN KEY (`id_tipus`) REFERENCES `tipus_incidencia` (`id_tipus_incidencia`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
