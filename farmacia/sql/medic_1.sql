-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-04-2016 a las 17:35:21
-- Versión del servidor: 10.1.9-MariaDB
-- Versión de PHP: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `farmacia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medic`
--

DROP TABLE IF EXISTS `medic`;
CREATE TABLE `medic` (
  `ME_CODIGO` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Código del medicamento',
  `ME_NOMCOM` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Nombre comercial del medicamento',
  `ME_CODKAI` varchar(8) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Código según Kairos',
  `ME_CODRAF` varchar(9) NOT NULL COMMENT 'Código según Rafam',
  `ME_KAIBAR` varchar(13) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Código de barras según Kairos',
  `ME_KAITRO` varchar(8) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Código de troquel según Kairos',
  `ME_CODMON` varchar(4) NOT NULL COMMENT 'Código de la monodroga',
  `ME_CODLAB` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Código del proveedor',
  `ME_PRES` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Texto que indica la presentación',
  `ME_FRACCQ` varchar(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Indica si se fracciona al enviar a Quirófano',
  `ME_VALVEN` double NOT NULL COMMENT 'Valor de venta',
  `ME_ULTCOM` date NOT NULL COMMENT 'Fecha de última compra',
  `ME_VALCOM` decimal(12,2) NOT NULL COMMENT 'Valor de la última compra',
  `ME_ULTSAL` date NOT NULL COMMENT 'Fecha de última salida',
  `ME_STMIN` double NOT NULL COMMENT 'Stock mínimo',
  `ME_STMAX` double NOT NULL COMMENT 'Stock máximo',
  `ME_RUBRO` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Rubro de facturación',
  `ME_UNIENV` decimal(12,2) NOT NULL COMMENT 'Unidades por envase',
  `ME_DEPOSITO` varchar(2) NOT NULL COMMENT 'Código del subdepósito de farmacia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `medic`
--
ALTER TABLE `medic`
  ADD PRIMARY KEY (`ME_CODIGO`),
  ADD KEY `FK_medic_artic_gral` (`ME_CODMON`),
  ADD KEY `FK_medic_labo` (`ME_CODLAB`),
  ADD KEY `FK_medic_deposito` (`ME_DEPOSITO`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `medic`
--
ALTER TABLE `medic`
  ADD CONSTRAINT `FK_medic_artic_gral` FOREIGN KEY (`ME_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_medic_deposito` FOREIGN KEY (`ME_DEPOSITO`) REFERENCES `deposito` (`DE_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_medic_labo` FOREIGN KEY (`ME_CODLAB`) REFERENCES `labo` (`LA_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
