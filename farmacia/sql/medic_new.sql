-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-04-2016 a las 22:05:57
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
-- Estructura de tabla para la tabla `artic_gral`
--

DROP TABLE IF EXISTS `artic_gral`;
CREATE TABLE `artic_gral` (
  `AG_CODIGO` varchar(4) NOT NULL COMMENT 'Codigo',
  `AG_NOMBRE` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Nombre',
  `AG_CODMED` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Medicamento',
  `AG_PRES` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Presentacion',
  `AG_STACT` decimal(12,2) NOT NULL COMMENT 'Stock Farmacia',
  `AG_STACDEP` decimal(12,2) NOT NULL COMMENT 'Stock Deposito',
  `AG_CODCLA` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Clase',
  `AG_FRACCQ` varchar(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Fraccionado',
  `AG_PSICOF` varchar(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Psicofármaco',
  `AG_PTOMIN` decimal(10,2) NOT NULL COMMENT 'Stock Minimo Deposito',
  `AG_FPTOMIN` decimal(10,2) NOT NULL COMMENT 'Stock Minimo Farmacia',
  `AG_PTOPED` decimal(10,2) NOT NULL COMMENT 'Stock Medio Deposito',
  `AG_FPTOPED` decimal(10,2) NOT NULL COMMENT 'Stock Medio Farmacia',
  `AG_PTOMAX` decimal(10,2) NOT NULL COMMENT 'Stock Maximo Deposito',
  `AG_FPTOMAX` decimal(10,2) NOT NULL COMMENT 'Stock Maximo Farmacia',
  `AG_CONSDIA` decimal(19,6) NOT NULL COMMENT 'Consumo Promedio Deposito',
  `AG_FCONSDI` decimal(19,6) NOT NULL COMMENT 'Consumo Promedio Farmacia',
  `AG_RENGLON` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Solicitud Compras',
  `AG_PRECIO` decimal(12,3) NOT NULL COMMENT 'Precio ultima compra',
  `AG_REDOND` decimal(10,2) NOT NULL COMMENT 'Cantidad Minima a pedir',
  `AG_PUNTUAL` decimal(19,6) NOT NULL COMMENT 'Consumo Medio Deposito',
  `AG_FPUNTUAL` decimal(19,6) NOT NULL COMMENT 'Consumo Medio Farmacia',
  `AG_REPAUT` enum('F','T') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Reposición Automatica',
  `AG_ULTENT` date NOT NULL COMMENT 'Ultima entrada',
  `AG_ULTSAL` date NOT NULL COMMENT 'Ultima Salida',
  `AG_UENTDEP` date NOT NULL COMMENT 'Ultima entrada Deposito',
  `AG_USALDEP` date NOT NULL COMMENT 'Ultima salida Deposito',
  `AG_PROVINT` varchar(3) NOT NULL COMMENT 'Proveedor Interno',
  `AG_ACTIVO` enum('F','T') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Activo',
  `AG_VADEM` varchar(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Vademecum',
  `AG_ORIGUSUA` varchar(6) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Usuario',
  `AG_FRACSAL` varchar(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Fracciona en Sala',
  `AG_DROGA` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Droga',
  `AG_VIA` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Vía de acceso',
  `AG_DOSIS` decimal(12,4) NOT NULL COMMENT 'Dosis',
  `AG_ACCION` varchar(3) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Acción terapéutica',
  `AG_VISIBLE` enum('F','T') NOT NULL COMMENT 'Visisble desde Descartes',
  `AG_DEPOSITO` varchar(2) NOT NULL COMMENT 'Deposito'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `artic_gral`
--

------------------------------------------------------

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
-- Volcado de datos para la tabla `medic`
--


-- Índices para tablas volcadas
--

--
-- Indices de la tabla `artic_gral`
--
ALTER TABLE `artic_gral`
  ADD PRIMARY KEY (`AG_CODIGO`),
  ADD KEY `FK_artic_gral_deposito` (`AG_DEPOSITO`),
  ADD KEY `FK_artic_gral_droga` (`AG_DROGA`),
  ADD KEY `FK_artic_gral_vias` (`AG_VIA`),
  ADD KEY `FK_artic_gral_clases` (`AG_CODCLA`),
  ADD KEY `FK_artic_gral_acciont` (`AG_ACCION`),
  ADD KEY `FK_artic_gral_servicio` (`AG_PROVINT`),
  ADD KEY `FK_artic_gral_medic` (`AG_CODMED`);

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
-- Filtros para la tabla `artic_gral`
--
ALTER TABLE `artic_gral`
  ADD CONSTRAINT `FK_artic_gral_acciont` FOREIGN KEY (`AG_ACCION`) REFERENCES `acciont` (`AC_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_artic_gral_clases` FOREIGN KEY (`AG_CODCLA`) REFERENCES `clases` (`CL_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_artic_gral_deposito` FOREIGN KEY (`AG_DEPOSITO`) REFERENCES `deposito` (`DE_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_artic_gral_droga` FOREIGN KEY (`AG_DROGA`) REFERENCES `droga` (`DR_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_artic_gral_medic` FOREIGN KEY (`AG_CODMED`) REFERENCES `medic` (`ME_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_artic_gral_servicio` FOREIGN KEY (`AG_PROVINT`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_artic_gral_vias` FOREIGN KEY (`AG_VIA`) REFERENCES `vias` (`vi_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
