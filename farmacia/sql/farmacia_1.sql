-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-04-2016 a las 14:17:00
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
-- Estructura de tabla para la tabla `acciont`
--

DROP TABLE IF EXISTS `acciont`;
CREATE TABLE `acciont` (
  `AC_COD` varchar(3) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Código',
  `AC_DESCRI` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Descripción'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `acciont`
--

INSERT INTO `acciont` (`AC_COD`, `AC_DESCRI`) VALUES
('001', 'ABRASIVO DE LIMPIEZA'),
('002', 'ACARICIDA AMBIENTAL'),
('003', 'ACCESORIO RADIOLOGICO'),
('004', 'ACCESORIOS'),
('005', 'ACEITE PARA MASAJES MUSCULARES'),
('006', 'ACELERADOR DE BRONCEADO'),
('007', 'ACIDIFICANTE URINARIO'),
('008', 'ACTIVADOR CEREBRAL'),
('009', 'ACTIVADOR METABOLICO CELULAR'),
('010', 'ACTIVADOR METABOLICO CEREBRAL'),
('011', 'ACTIVADOR METABOLICO MUSCULAR'),
('012', 'ACTIVADOR MUSCULAR'),
('013', 'ADELGAZANTE'),
('014', 'ADHESIVO HEMOSTATICO'),
('015', 'ADHESIVO P/PROTESIS DENTALES'),
('016', 'AFIRMANTE ANTIFLACCIDEZ'),
('017', 'AGENTE DESPERTADOR NO ANFETAM.'),
('018', 'AGENTE ESTIMULANTE OSTEOBLASTI'),
('019', 'AGENTE INOTROPICO POSITIVO'),
('020', 'AGONISTA DOPAMINERGICO'),
('021', 'AGONISTA LHRH'),
('022', 'AGUA DESTILADA'),
('023', 'AGUJA P/APLICADOR DE INSULINA'),
('024', 'ALFABLOQUEANTE VASODILATADOR'),
('025', 'ALIMENTO DIETETICO'),
('026', 'ALIMENTO FUNCIONAL'),
('027', 'ALIMENTO GENERAL'),
('028', 'ALIMENTO INFANTIL'),
('029', 'ANABOLICO'),
('030', 'ANALG.ANTIB.ANTISEP.BUCOFARING'),
('031', 'ANALGESICO'),
('032', 'ANALGESICO ANTIACIDO'),
('033', 'ANALGESICO ANTIBIOTICO OTICO'),
('034', 'ANALGESICO ANTIESPASMODICO'),
('035', 'ANALGESICO ANTIFEBRIL'),
('036', 'ANALGESICO ANTIGRIPAL'),
('037', 'ANALGESICO ANTIHISTAMINICO'),
('038', 'ANALGESICO ANTIINFLAM.'),
('039', 'ANALGESICO ANTIJAQUECOSO'),
('040', 'ANALGESICO ANTINEURITICO'),
('041', 'ANALGESICO ANTIRREUMATICO'),
('042', 'ANALGESICO ANTISEP.BUCOFAR.'),
('043', 'ANALGESICO DESCONGESTIVO'),
('044', 'ANALGESICO MIORRELAJ.'),
('045', 'ANALGESICO OTICO'),
('046', 'ANALGESICO REFRIGERANTE'),
('047', 'ANALGESICO RUBEFACIENTE'),
('048', 'ANALGESICO TOPICO'),
('049', 'ANALGESICO TOPICO ANTIINFL.'),
('050', 'ANALGESICO URINARIO'),
('051', 'ANDROGENOTERAPIA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alarmas`
--

DROP TABLE IF EXISTS `alarmas`;
CREATE TABLE `alarmas` (
  `AL_CODIGO` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código',
  `AL_MIN` int(11) DEFAULT NULL COMMENT 'Punto mínimo de consumo normal semanal ',
  `AL_MAX` int(11) DEFAULT NULL COMMENT 'Consumo máximo normal semanal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alarmas`
--

INSERT INTO `alarmas` (`AL_CODIGO`, `AL_MIN`, `AL_MAX`) VALUES
('1', 12, 33);

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

INSERT INTO `artic_gral` (`AG_CODIGO`, `AG_NOMBRE`, `AG_CODMED`, `AG_PRES`, `AG_STACT`, `AG_STACDEP`, `AG_CODCLA`, `AG_FRACCQ`, `AG_PSICOF`, `AG_PTOMIN`, `AG_FPTOMIN`, `AG_PTOPED`, `AG_FPTOPED`, `AG_PTOMAX`, `AG_FPTOMAX`, `AG_CONSDIA`, `AG_FCONSDI`, `AG_RENGLON`, `AG_PRECIO`, `AG_REDOND`, `AG_PUNTUAL`, `AG_FPUNTUAL`, `AG_REPAUT`, `AG_ULTENT`, `AG_ULTSAL`, `AG_UENTDEP`, `AG_USALDEP`, `AG_PROVINT`, `AG_ACTIVO`, `AG_VADEM`, `AG_ORIGUSUA`, `AG_FRACSAL`, `AG_DROGA`, `AG_VIA`, `AG_DOSIS`, `AG_ACCION`, `AG_VISIBLE`, `AG_DEPOSITO`) VALUES
('0001', 'ACIDO ACETIL SALICILICO 500 MGS. COMP.', '0001', '****', '46.00', '320.00', '02', 'N', 'N', '34.00', '0.00', '0.00', '0.00', '410.75', '259.00', '2.054795', '0.000000', 'Comprimidos de Acido Acetil Salicilico 500 mg.', '0.290', '100.00', '1.857143', '8.633333', 'F', '2016-03-02', '2016-03-08', '2015-09-04', '2016-03-02', '022', 'T', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0002', 'DIPIRONA 1 GR./2MLS. INY.', '0002', '****', '327.00', '1244.00', '02', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '4239.73', '520.00', '19.416438', '0.000000', 'Ampollas de Dipirona 1 gr. x 2 cc. cada una..-', '4.500', '100.00', '20.961905', '17.333333', '', '2016-03-02', '2016-03-08', '2016-01-13', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0007', 'DEXTROPROPOXIFENO 50+DIPIRONA 1,5 INY.', '0007', '****', '1816.00', '0.00', '02', 'N', 'S', '0.00', '0.00', '0.00', '0.00', '1553.42', '1116.00', '14.794521', '0.000000', 'Ampollas de dextropropoxifeno 50mg + dipirona 1,5mg  x 5 ml', '5.800', '100.00', '0.000000', '37.200000', '', '2015-10-28', '2016-03-08', '2015-10-27', '2015-10-27', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0013', 'ADRENALINA CLORHIDRATO 1MG./ML. INY.', '0013', '****', '81.00', '129.00', '02', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '535.18', '34.00', '2.449315', '0.000000', 'Ampollas de Adrenalina Clorhidrato 1 o/oo en 1cc.', '5.830', '100.00', '2.647619', '1.133333', '', '2016-03-02', '2016-03-08', '2016-01-06', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0017', 'PARACETAMOL 500 MGS. COMP.', '0017', '****', '563.00', '1550.00', '02', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '9596.92', '1090.00', '44.684932', '0.000000', 'Comprimidos de Paracetamol 500 mg.', '0.330', '100.00', '46.714286', '36.333333', '', '2016-03-02', '2016-03-08', '2016-01-11', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0033', 'PROPINOXATO+CLONIXINATO LISINA 100MG/2ML', '0033', '****', '21.00', '27.00', '02', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '703.03', '447.00', '3.147945', '0.000000', 'Ampollas de Propinoxato Clorhidrato + Clonixinato de lisina', '9.600', '3.00', '3.547619', '14.900000', '', '2016-03-02', '2016-03-08', '2016-02-22', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0040', 'METOCLOPRAMIDA 10 MGS. INY. (x AMP 1 CC)', '0040', '****', '926.00', '4491.00', '02', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '12280.49', '1408.00', '60.290411', '0.000000', 'Ampollas de Metoclopramida 10 mg/1cc', '4.500', '100.00', '56.666667', '46.933333', '', '2016-03-02', '2016-03-08', '2016-01-13', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0042', 'LORAZEPAN 1 MG. COMP.', '0042', '****', '176.00', '750.00', '02', 'N', 'S', '0.00', '0.00', '0.00', '0.00', '1408.77', '91.00', '6.273973', '0.000000', 'Comprimidos de Lorazepan 1 mg.', '0.330', '10.00', '7.142857', '3.033333', '', '2016-03-02', '2016-03-08', '2016-01-11', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0084', 'AMIODARONA 150 MGS/3 MLS. INY. ****', '0084', '****', '112.00', '470.00', '02', 'S', 'N', '0.00', '0.00', '0.00', '0.00', '784.84', '56.00', '3.569863', '0.000000', 'Ampollas de Amiodarona 150 mg.', '4.500', '100.00', '3.904762', '1.866667', '', '2016-03-03', '2016-03-07', '2016-01-13', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0092', 'DIGOXINA 0,25 MGS. COMP.', '0092', '****', '59.00', '180.00', '02', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '262.19', '42.00', '1.068493', '0.000000', 'Comprimidos de Digoxina 0,25 mgs.', '0.650', '10.00', '1.428571', '1.400000', '', '2016-02-17', '2016-03-04', '2016-01-05', '2016-02-16', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0093', 'DIGOXINA 0,25 MG./ML. INY.', '0093', '****', '73.00', '431.00', '02', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '415.75', '30.00', '2.054795', '0.000000', 'Ampollas de Digoxina 0,25 mg/1ml.', '8.340', '100.00', '1.904762', '1.000000', '', '2016-03-02', '2016-03-08', '2016-01-07', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0094', 'ISOSORBIDE DINITRATO 5 MGS. S/L. COMP.', '0094', '****', '104.00', '27.00', '02', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '345.74', '43.00', '1.435616', '0.000000', 'Comprimidos de Dinitrato de Isosorbide Sublingual 5 mg.', '2.400', '10.00', '1.857143', '1.433333', '', '2016-02-24', '2016-03-07', '2015-12-04', '2016-02-24', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0098', 'DILTIAZEN 60 MGS. COMP.', '0098', '****', '35.00', '10.00', '02', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '136.92', '37.00', '0.684932', '0.000000', 'Comprimidos Diltiazem Clorhidrato 60 mg. ranurados.', '0.550', '10.00', '0.619048', '1.233333', '', '2016-02-24', '2016-03-08', '2015-05-04', '2016-02-24', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0102', 'ENALAPRIL 5 MGS. COMP.', '0102', '****', '0.00', '0.00', '02', 'N', 'N', '0.00', '1.00', '2.00', '0.00', '0.00', '0.00', '0.000000', '0.000000', 'Comprimidos de Enalapril de 5 Mgs., al abrigo de la luz', '0.060', '10.00', '0.000000', '0.000000', '', '1899-12-30', '2004-05-15', '1899-12-30', '2004-06-24', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0103', 'ENALAPRIL 10 MGS. COMP.', '0103', '****', '168.00', '1830.00', '02', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '4070.41', '784.00', '19.375342', '0.000000', 'Comprimidos de Enalapril de 10 mgs. ranurados, al abrigo de', '0.200', '10.00', '19.390476', '26.133333', '', '2016-03-02', '2016-03-08', '2016-01-06', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0109', 'DOPAMINA CLORHIDRATO 200 MG (x AMP.5 CC)', '0109', '****', '77.00', '138.00', '05', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '142.73', '106.00', '0.616438', '0.000000', 'Ampollas de Dopamina Clorhidrato 200mg/5cc.', '5.500', '100.00', '0.742857', '3.533333', '', '2016-02-17', '2016-03-03', '2016-01-05', '2016-02-16', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0116', 'NIFEDIPINA 10 MGS. CAPSULAS', '0116', '****', '0.00', '0.00', '02', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '68.29', '71.00', '0.602740', '0.000000', 'C psulas blandas de Nifedipina 10 mgs.', '0.950', '100.00', '0.047619', '10.142857', '', '2015-04-19', '2015-04-28', '2015-04-10', '2015-04-10', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0214', 'TRIMETOPRIMA-SULFOMETOXAZOL 160/800 COMP', '0214', '****', '160.00', '450.00', '03', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '849.25', '0.00', '3.945205', '0.000000', 'Comprimidos ranurados de Sulfometoxazol 800 mg/Trimetoprima', '1.360', '50.00', '4.142857', '2.600000', '', '2016-03-02', '2016-03-08', '2016-01-11', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0215', 'TRIMETOPRIMA-SULFOMETOXAZOL 40/200 JBE.', '0215', '****', '0.00', '0.00', '03', 'N', 'N', '0.00', '1.00', '0.00', '0.00', '0.58', '0.00', '0.005479', '0.000000', 'Frasco de 60 cc de Sulfometoxazol 400 mg/Trimetoprina 80 mg.', '125.000', '1.00', '0.000000', '0.000000', '', '2015-12-22', '2016-02-19', '2015-12-22', '2015-12-22', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0228', 'PENICILINA G SODICA 5.000.000 INY.', '0228', '', '0.00', '0.00', '99', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.000000', '0.000000', 'Frasco Ampolla Penicilina G S¢dica 5.000.000 UI .-', '1.000', '50.00', '0.000000', '0.000000', '', '1899-12-30', '2000-11-12', '1899-12-30', '1899-12-30', '022', '', 'N', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0229', 'PENICILINA G SODICA 3.000.000 INY.', '0229', '', '-1.00', '0.00', '99', 'N', 'N', '0.00', '1.00', '2.00', '0.00', '3.00', '0.00', '0.000000', '0.000000', 'Frasco Ampolla Penicilina G S¢dica 3.000.000 UI', '0.750', '50.00', '0.000000', '0.000000', '', '2015-08-10', '2015-11-11', '2003-06-02', '2003-06-05', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0230', 'PENICILINA G SODICA 1000000 UI INY.', '0230', '****', '139.00', '270.00', '03', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '390.88', '18.00', '1.136986', '0.000000', 'Frasco Ampolla Penicilina G S¢dica 1.000.000 UI .-', '14.480', '100.00', '2.585714', '0.600000', '', '2015-12-29', '2016-01-13', '2015-12-22', '2015-12-28', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0244', 'AMOXICILINA 1 GR. COMP.', '0244', '', '0.00', '0.00', '99', 'N', 'N', '0.00', '1.00', '2.00', '0.00', '3.00', '0.00', '0.000000', '0.000000', 'Comprimidos de Amoxicilina  Trihidrato 1 gramo ranurado.', '0.850', '8.00', '0.000000', '0.000000', '', '2003-05-28', '2003-07-22', '1899-12-30', '2003-05-28', '022', '', 'N', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0245', 'AMOXICILINA 250MG./5ML. JBE. (x 60 MLS.)', '0245', '****', '1.00', '24.00', '03', 'N', 'N', '0.00', '1.00', '2.00', '0.00', '28.87', '0.00', '0.117808', '0.000000', 'Envases de Amoxicilina 250 mg. c/5 ml. de 60 cc.', '9.090', '1.00', '0.157143', '0.000000', '', '2016-03-02', '2016-02-26', '2016-01-13', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0246', 'AMOXICILINA 500 MGS. JBE. x 60 C.C.', '0246', '', '0.00', '0.00', '99', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.000000', '0.000000', 'Envases de Amoxicilina de 500 Mgs. jarabe, de 60 c.c.', '2.000', '1.00', '0.000000', '0.000000', '', '2008-09-17', '2014-06-03', '2008-04-08', '2008-10-15', '022', '', 'N', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0249', 'AMPICILINA 1 GR. INY.', '0249', '****', '318.00', '1685.00', '03', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '2996.54', '197.00', '14.057534', '0.000000', 'Frasco-ampolla de Ampicilina l gr. .-', '12.500', '100.00', '14.480952', '6.566667', '', '2016-03-02', '2016-03-08', '2016-01-14', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0251', 'AMOXICILINA 500 MGS. COMP.', '0251', '****', '93.00', '408.00', '03', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '739.51', '46.00', '3.309589', '0.000000', 'Comprimidos de Amoxicilina de 500 Mgs.', '0.850', '8.00', '3.733333', '6.571429', '', '2016-02-17', '2016-03-08', '2016-01-05', '2016-02-16', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0268', 'FENOXIMETILPENICILINA 300000 UI JBE.x100', '0268', '****', '1.00', '0.00', '03', 'N', 'N', '0.00', '1.00', '0.00', '0.00', '1.58', '0.00', '0.005479', '0.000000', 'Frasco Fenoximetilpenicilina de 300.000 UI/5cc.de 60 g.', '12.000', '1.00', '0.009524', '0.000000', '', '2015-09-30', '2015-06-30', '2015-08-27', '2015-09-29', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0269', 'FENOXIMETILPENICILINA 1500000 UI COMP.', '0269', '****', '0.00', '24.00', '03', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '6.90', '7.00', '0.065753', '0.000000', 'Comprimidos Fenoximetilpenicilina 1.500.000 UI ranurados.', '1.800', '6.00', '0.000000', '0.233333', '', '2015-01-16', '2015-09-30', '2015-10-13', '2015-01-16', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0305', 'CEFAZOLINA 1 GR. INY.', '0305', '****', '276.00', '1632.00', '03', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '5730.90', '1127.00', '28.265753', '0.000000', 'Frasco Ampolla Cefazolina 1 g de Droga  activa liofilizada.', '11.450', '100.00', '26.314286', '37.566667', '', '2016-03-02', '2016-03-08', '2016-01-14', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('0306', 'CEFAZOLINA 500 MGS. INY.', '0306', '', '0.00', '0.00', '99', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.000000', '0.000000', '', '0.000', '1.00', '0.000000', '0.000000', '', '2007-04-06', '2014-02-10', '1899-12-30', '1899-12-30', '022', '', 'N', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1121', 'CLORPROMAZINA I.V. 50MG./2ML.', '1121', '****', '182.00', '0.00', '02', 'N', 'S', '0.00', '0.00', '0.00', '0.00', '205.33', '28.00', '1.260274', '0.000000', 'Ampollas de Cloropromazina al 2,5 % I.V. con una concentraci', '5.260', '100.00', '0.695238', '0.933333', '', '2015-12-21', '2016-03-08', '2015-12-21', '2015-12-21', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1130', 'DIAZEPAN 10 MGS. INY.', '1130', '****', '-18.00', '346.00', '02', 'N', 'S', '0.00', '0.00', '0.00', '0.00', '1022.03', '245.00', '5.147945', '0.000000', 'Ampollas de Diazepan 10 mg.', '5.500', '100.00', '4.585714', '8.166667', '', '2016-03-02', '2016-03-08', '2016-01-22', '2016-03-08', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1134', 'SODIO DIFENILHIDANTOINATO 100MG/2ML INY.', '1134', '****', '357.00', '990.00', '02', 'N', 'S', '0.00', '0.00', '0.00', '0.00', '1547.87', '202.00', '7.717808', '0.000000', 'Ampollas Difenilhidantoinato de Na. (Fenito¡na) 50 mg/ml.', '5.570', '100.00', '7.023810', '6.733333', '', '2016-03-02', '2016-03-08', '2016-02-05', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1136', 'FENOBARBITAL 100 MGS. COMP.', '1136', '****', '15.00', '0.00', '02', 'N', 'S', '0.00', '1.00', '2.00', '0.00', '8.63', '0.00', '0.082192', '0.000000', 'Comprimidos de Fenobarbital  0,1 gr. ranurados.', '0.420', '10.00', '0.000000', '0.000000', '', '2015-09-04', '2016-01-29', '2014-01-27', '2014-01-27', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1138', 'FENOBARBITAL 200 MGS. INY.', '1138', '', '0.00', '0.00', '99', 'N', 'S', '0.00', '1.00', '1.00', '0.00', '1.00', '0.00', '0.000000', '0.000000', 'Ampollas de Fenobarbital S¢dico 200 mg/2 ml.', '3.200', '1.00', '0.000000', '0.000000', '', '2009-03-02', '2009-06-17', '1899-12-30', '1899-12-30', '022', '', 'N', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1139', 'HALOPERIDOL GOTAS 2MG/ML. (xENV. 20MLS.)', '1139', '****', '9.00', '55.00', '02', 'N', 'S', '0.00', '1.00', '2.00', '0.00', '74.53', '16.00', '0.347945', '0.000000', 'Frasco Gotero Haloperidol por 20 cc por c/10 gotas=1 mg de', '16.210', '1.00', '0.361905', '0.533333', '', '2016-03-02', '2016-02-21', '2016-01-07', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1184', 'ETILEFRINA CLORHIDRATO 10MG./ML. INY.', '1184', '****', '64.00', '254.00', '05', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '704.86', '0.00', '3.208219', '0.000000', 'Ampollas de Etilefrina Clorhidrato de 1cc (Tipo Effortil)', '7.680', '100.00', '3.504762', '0.000000', '', '2016-03-02', '2016-02-29', '2016-01-13', '2016-03-02', '022', '', 'S', 'DEPOSI', '', '123', '1', '0.0000', '008', 'F', '1'),
('1185', 'BUPIVACAINA HIPERBARICA INY.(x AMP 4 ML)', '1185', '', '0.00', '0.00', '99', 'N', 'N', '0.00', '120.00', '240.00', '0.00', '332.86', '60.00', '0.082192', '0.000000', 'Ampolla de Bupivaca¡na Clorhidrato 0,5 % Hiperb rica en 4 ml', '21.000', '100.00', '0.000000', '2.000000', '', '2008-12-18', '2015-01-22', '2008-03-25', '2008-12-17', '022', '', 'N', 'DEPOSI', '', '123', '1', '0.0000', '008', 'F', '1'),
('1187', 'BUPIVACAINA 5 MG/ML S/EPIN.(xVIAL 20cc)*', '1187', '****', '40.00', '150.00', '05', 'N', 'N', '0.00', '1.00', '2.00', '0.00', '349.83', '0.00', '1.460274', '0.000000', 'Frasco Pak-Est‚ril de Bupivaca¡na Clorhidrato 0,5% sin vaso', '25.900', '1.00', '1.871429', '0.000000', '', '2016-03-02', '2016-02-29', '2016-01-08', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1189', 'BUPIVACAINA 5 MG/ML C/EPI.(xVIAL 20cc)**', '1189', '****', '25.00', '20.00', '05', 'S', 'N', '0.00', '0.00', '0.00', '0.00', '47.33', '0.00', '0.260274', '0.000000', 'Frasco Pak-Est‚ril de Bupivaca¡na Clorhidrato 0,5% con vaso', '53.940', '5.00', '0.190476', '0.000000', '', '2016-02-24', '2016-02-15', '2016-01-13', '2016-02-24', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1191', 'MIDAZOLAM 15 MGS./3 MLS. INY.', '1191', '****', '4847.00', '100.00', '05', 'N', 'S', '0.00', '0.00', '0.00', '0.00', '48948.90', '5156.00', '226.465753', '0.000000', 'Ampollas de Midazolam 15 mg/3cc.', '6.050', '100.00', '239.714286', '171.866667', '', '2016-03-03', '2016-03-08', '2016-03-02', '2016-03-03', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1200', 'PANCURONIO BROMURO 4MG./2ML. INY.', '1200', '****', '124.00', '887.00', '05', 'N', 'S', '0.00', '0.00', '0.00', '0.00', '1507.91', '195.00', '6.575342', '0.000000', 'Ampollas de Bromuro de Pancuronio 4 mg/2cc.', '7.000', '100.00', '7.785714', '6.500000', '', '2016-03-02', '2016-03-05', '2016-01-12', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1219', 'LIDOCAINA 2% JALEA (x POMO 25 C.C.)', '1219', '****', '65.00', '553.00', '05', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '1360.59', '104.00', '6.424658', '0.000000', 'Pomo de Lidoca¡na Clorh¡drica al 2% en un excipiente de Car-', '20.700', '1.00', '6.533333', '3.466667', '', '2016-03-02', '2016-03-08', '2016-01-13', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1224', 'LIDOCAINA 2% VISCOSA (x SOL. 50 ML.)', '1224', '****', '66.00', '163.00', '05', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '277.08', '51.00', '1.315068', '0.000000', 'Envase Pl stico de Lidoca¡na Clorh¡drica al 2% en un exci-', '17.460', '1.00', '1.323810', '1.700000', '', '2016-03-02', '2016-03-06', '2016-01-14', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1231', 'GAS FREON AEROSOL (x ENV. 250GR./204ML.)', '1231', '****', '3.00', '0.00', '05', 'N', 'N', '0.00', '1.00', '2.00', '0.00', '13.83', '4.00', '0.060274', '0.000000', 'Envase x 180 cc de Gas Refrigerante a base de Freon Tipo', '124.000', '1.00', '0.071429', '0.133333', '', '2016-03-02', '2016-02-26', '2015-09-11', '2016-03-02', '022', '', 'S', 'DEPOSI', '', '123', '1', '0.0000', '008', 'F', '1'),
('1232', 'HALOTANO (x ENV. 250 MLS.)', '1232', '', '0.00', '0.00', '99', 'N', 'N', '0.00', '1.00', '2.00', '0.00', '3.00', '0.00', '0.000000', '0.000000', 'Frascos de Halotano de 250 ml.', '98.000', '1.00', '0.000000', '0.000000', '', '2006-11-23', '2007-02-02', '2014-09-29', '2006-11-22', '022', '', 'N', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1233', 'LIDOCAINA 2% S/EPINEFRINA INY. (x 20 CC)', '1233', '', '0.00', '0.00', '99', 'N', 'N', '0.00', '1.00', '2.00', '0.00', '3.00', '0.00', '0.000000', '0.000000', 'Ampollas (Polyamp) est‚ril de Lidoca¡na Clorhidrato al 2%', '4.150', '5.00', '0.000000', '0.000000', '', '2002-10-25', '2014-06-13', '2003-08-08', '2005-01-25', '022', '', 'N', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1234', 'LIDOCAINA 2% C/EPINEFRINA INY. (x 20 CC)', '1234', '****', '43.00', '118.00', '05', 'N', 'N', '0.00', '0.00', '0.00', '0.00', '271.14', '55.00', '1.391781', '0.000000', 'Frasco-ampollas de Lidocaina 2% x 20 cc. c/epinefrina.', '28.340', '5.00', '1.190476', '1.833333', '', '2016-03-02', '2016-03-02', '2016-01-13', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1'),
('1238', 'LIDOCAINA 4% TOPICA (x SOL. 25 ML.)', '1238', '****', '5.00', '6.00', '05', 'N', 'N', '0.00', '1.00', '2.00', '0.00', '20.43', '0.00', '0.104110', '0.000000', 'Envases de Lidocaina al 4% x 25 cc. uso t¢pico.-', '20.480', '1.00', '0.090476', '0.000000', '', '2016-02-03', '2016-01-28', '2016-01-14', '2016-02-03', '022', '', 'S', 'DEPOSI', '', '123', '1', '0.0000', '008', 'F', '1'),
('1240', 'LIDOCAINA 10% SPRAY (x 50 GRS.)', '1240', '****', '13.00', '13.00', '05', 'N', 'N', '0.00', '1.00', '2.00', '0.00', '32.03', '4.00', '0.147945', '0.000000', 'Frasco Spray de Lidoca¡na base al 10% con Bacteriost tico', '64.120', '1.00', '0.157143', '0.133333', '', '2016-02-24', '2016-02-29', '2016-01-13', '2016-03-02', '022', '', 'S', 'DEPOSI', 'N', '123', '1', '0.0000', '008', 'F', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

DROP TABLE IF EXISTS `clases`;
CREATE TABLE `clases` (
  `CL_COD` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Codigo',
  `CL_NOM` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Descripción'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `clases`
--

INSERT INTO `clases` (`CL_COD`, `CL_NOM`) VALUES
('01', 'DESCARTABLES'),
('02', 'MONODROGAS'),
('03', 'ANTIBIOTICOS'),
('04', 'SOLUCIONES'),
('05', 'ANESTESICOS'),
('06', 'DROGAS PURAS'),
('07', 'ANTISEPTICOS'),
('08', 'SUTURAS P/S.M.U.'),
('09', 'TRATAMIENTOS ESPECIALES'),
('0?', 'PSICOFARMACOS'),
('10', 'ENVASES'),
('21', 'FORMULAS MAGISTRALES FARMACIA'),
('23', 'DROGAS PURAS P/LA PLATA'),
('29', 'BIBLIOGRAFIA'),
('30', 'SET DE BOMBAS'),
('31', 'PA¥ALES'),
('89', 'DISPOSITIVOS MEDICOS'),
('99', 'FUERA DE VADEMECUM');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deposito`
--

DROP TABLE IF EXISTS `deposito`;
CREATE TABLE `deposito` (
  `DE_CODIGO` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT 'Codigo',
  `DE_DESCR` varchar(45) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Nombre'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `deposito`
--

INSERT INTO `deposito` (`DE_CODIGO`, `DE_DESCR`) VALUES
('1', 'DEPOSITO DE FARMACIA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `droga`
--

DROP TABLE IF EXISTS `droga`;
CREATE TABLE `droga` (
  `DR_CODIGO` varchar(4) COLLATE utf8_bin NOT NULL COMMENT 'Código',
  `DR_DESCRI` text COLLATE utf8_bin COMMENT 'Descripción',
  `DR_CLASE` varchar(2) COLLATE utf8_bin DEFAULT NULL COMMENT 'Clase'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `droga`
--

INSERT INTO `droga` (`DR_CODIGO`, `DR_DESCRI`, `DR_CLASE`) VALUES
('123', 'aaa', '30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `labo`
--

DROP TABLE IF EXISTS `labo`;
CREATE TABLE `labo` (
  `LA_CODIGO` varchar(5) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `LA_NOMBRE` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `LA_TIPO` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `labo`
--

INSERT INTO `labo` (`LA_CODIGO`, `LA_NOMBRE`, `LA_TIPO`) VALUES
('*001', 'FARMACIA COLON?', 'e'),
('*002', 'DROGUERIA IBSA', 'i'),
('0001', 'PROMEDIC DIST. S.R.L.', 'i'),
('0002', 'UNANUT S.A.', 'e'),
('0003', 'SCHERING ARGENTINA SAIC.', NULL),
('0004', 'RADIO LLAMADA B.BCA. S.A.', NULL),
('0005', 'ROBERTO A. ROBILOTTA', NULL),
('0006', 'MEDICAL SUPPLIES S.A.', NULL),
('0007', 'JOTAFI COMP.INT. S.A.', NULL),
('0008', 'CHAVEZ HECTOR HORACIO', 'i'),
('0009', 'MULTITEX', NULL),
('0010', 'ALGANY S.R.L.', NULL),
('0011', 'DENTAL-MEDIC', NULL),
('0012', 'SERVICSA', NULL),
('0013', 'CASA SETTIMI', NULL),
('0014', 'SAIPP S.R.L.', NULL),
('0015', 'RUMBO SUR S.R.L.', NULL),
('0016', 'IMPRENTA LENZU', NULL),
('0017', 'PAPELERA MUSSINI S.R.L.', NULL),
('0018', 'PAPELERA SAN LUIS S.A.C.I', NULL),
('0019', 'IMPRENTA MARCHETTI S.R.L.', NULL),
('0020', 'COOPERATIVA OBRERA LTDA.', NULL),
('0021', 'IN.QUI.BA.', NULL),
('0022', 'DISTRIBUIDORA SAN MARTIN', NULL),
('0023', 'ARGENTIA S.A.C.I.F.I', NULL),
('0024', 'DOW QUIMICA S.A.- LEPETIT', NULL),
('0025', 'ROUX OCEFA S.A.', NULL),
('0026', 'NESTOR L.SERRON Y CIA.SRL', NULL),
('0027', 'CROMOION S.R.L.', NULL),
('0028', 'KEY PHARMA', NULL),
('0029', 'RADIOGRAFICA DEL SUD', NULL),
('0030', 'LEW BERNARDO', NULL),
('0031', 'PROINFA S.R.L.', NULL),
('0032', 'PROD.FARM.DR. GRAY SACI.', NULL),
('0033', 'DROMAN-MANSUR', NULL),
('0034', 'QUIMICA SUR S.A.', NULL),
('0035', 'BIOMEDICA PUNTA ALTA', NULL),
('0036', 'TRIMED S.A.', NULL),
('0037', 'EFI-CONT BAHIA', NULL),
('0038', 'AUTOSERV.CORRIENTES SRL.', NULL),
('0039', 'MIDOLO HUGO e HIJOS S.R.L', NULL),
('0040', 'DUPOMAR S.A.C.I.F.', NULL),
('0041', 'JOHN WYETH LAB. S.A.', NULL),
('0042', 'ROFINA S.A.C.I.F.', NULL),
('0043', 'ABBOTT LAB. ARG. S.A.', NULL),
('0044', 'PRODUVENTA S.A.C.I.', NULL),
('0045', 'OLGA B. EVANGELISTA', NULL),
('0046', 'ENILSUR S.R.L.', NULL),
('0047', 'LISER S.H.', NULL),
('0048', 'MAZDEN S.R.L.', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medic`
--

DROP TABLE IF EXISTS `medic`;
CREATE TABLE `medic` (
  `ME_CODIGO` char(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ME_NOMCOM` char(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ME_CODKAI` char(8) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ME_KAIBAR` char(13) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ME_KAITRO` char(8) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ME_KAIROS` char(13) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ME_CODMON` char(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ME_CODLAB` char(4) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ME_PRES` char(25) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ME_GRUPO` char(3) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ME_FRACCQ` char(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ME_VALVEN` decimal(12,2) NOT NULL,
  `ME_ULTCOM` date NOT NULL,
  `ME_VALCOM` decimal(12,2) NOT NULL,
  `ME_ULTSAL` date NOT NULL,
  `ME_STMIN` decimal(12,2) NOT NULL,
  `ME_STMAX` decimal(12,2) NOT NULL,
  `ME_RUBRO` char(2) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ME_FACTCON` decimal(5,2) NOT NULL,
  `ME_UNIENV` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `medic`
--

INSERT INTO `medic` (`ME_CODIGO`, `ME_NOMCOM`, `ME_CODKAI`, `ME_KAIBAR`, `ME_KAITRO`, `ME_KAIROS`, `ME_CODMON`, `ME_CODLAB`, `ME_PRES`, `ME_GRUPO`, `ME_FRACCQ`, `ME_VALVEN`, `ME_ULTCOM`, `ME_VALCOM`, `ME_ULTSAL`, `ME_STMIN`, `ME_STMAX`, `ME_RUBRO`, `ME_FACTCON`, `ME_UNIENV`) VALUES
('0003', 'NOVALGINA 500 MG COMP', '00313401', '7795312020756', ' 3777210', '', '0003', '9936', 'comp.', '  A', 'N', '4.53', '1998-04-08', '1.30', '2001-11-06', '525.00', '1225.00', '31', '0.00', '10.00'),
('6333', 'UNI-KT (Set de Via Venosa Central)', '', '', '', '', '6333', '0043', '', ' MD', 'N', '85.00', '2001-12-15', '157.50', '2001-12-04', '0.00', '0.00', '32', '0.00', '0.00'),
('0005', 'LECHE DE MAGNESIO PHILLIPS', '00248105', '7794640130113', '       0', '', '0005', '0077', 'SUSP. ORAL', '', 'N', '17.29', '1899-12-30', '15.50', '2001-12-01', '0.00', '0.00', '31', '0.00', '1.00'),
('1081', 'ATENOLOL COMP. x 100 MGS.', '00356501', '7794220000225', '27511610', '', '1081', '0345', 'COMP.', '  E', 'N', '6.72', '2001-12-15', '0.16', '2001-12-08', '150.00', '350.00', '31', '0.00', '14.00'),
('0016', 'SINALGICO Iny.', '01636505', '7798084684201', '33758610', '', '0016', '0347', 'Iny.', '  A', 'N', '5.35', '1999-04-16', '5.07', '2001-01-14', '0.00', '0.00', '31', '0.00', '5.00'),
('0026', 'ATROPINA SULFATO 1/oo', '01100501', '            0', '       0', '', '0026', '0077', 'AMP X 1ML', '  B', 'S', '3.82', '2001-11-27', '1.18', '2001-12-05', '300.00', '700.00', '31', '0.00', '100.00'),
('0033', 'SERTAL COMPUESTO ampollas.-', '00394503', '7795345001500', '25410220', '', '0033', '0042', 'Iny.', '  B', 'N', '38.14', '2001-12-27', '5.90', '2001-12-26', '0.00', '0.00', '31', '0.00', '1.00'),
('0031', 'NOVA -PARATROPINA', '01180702', '7795347939948', '43606520', '', '0031', '0104', 'GOTAS', '  B', 'N', '29.30', '1998-05-18', '10.00', '2001-11-04', '600.00', '140.00', '31', '0.00', '1.00'),
('0032', 'SERTAL INY.', '00394603', '7795345002859', '28333310', '', '0032', '0042', 'Iny.', '  B', 'N', '1.87', '2001-04-30', '0.95', '2001-12-07', '45.00', '105.00', '31', '0.00', '6.00'),
('0047', 'RELIVERAN gotas.-', '00374101', '7795306390551', ' 2969620', '', '0047', '0355', 'GOTAS', '  C', 'N', '65.27', '1997-08-07', '1.72', '1999-09-09', '0.00', '0.00', '31', '0.00', '1.00'),
('0013', 'ADRENALINA Iny.', '00789502', '            0', '       0', '', '0013', '0077', 'AMP. 1-0/00', '  E', 'S', '7.45', '2001-11-19', '1.18', '2001-12-04', '120.00', '280.00', '31', '0.00', '100.00'),
('0518', 'SOLUMEDROL 500 mg. fco.amp.', '00878702', '7791824117564', '26070310', '', '0518', '0293', 'fco.amp.', '', 'N', '206.79', '2001-10-19', '27.60', '2001-11-22', '0.00', '0.00', '31', '0.00', '1.00'),
('0084', 'ATLANSIL Iny.', '00041003', '7795345004983', '28484420', '', '0084', '0042', 'Iny.', '  E', 'S', '9.75', '2001-07-27', '1.50', '2001-12-02', '120.00', '280.00', '31', '0.00', '6.00'),
('0083', 'ATLANSIL Comp.', '00041001', '7795345004952', '28483610', '', '0083', '0042', 'Comp.', '  E', 'N', '3.68', '2001-11-30', '0.28', '2001-12-04', '75.00', '175.00', '31', '0.00', '20.00'),
('0091', 'TERRAMICINA 500 Comp.', '00430108', '7795381410199', '48565810', '', '0091', '0077', 'Comp.', '  E', 'N', '2.61', '1997-03-06', '1.00', '2001-12-04', '0.00', '0.00', '31', '0.00', '20.00'),
('0092', 'LANICOR COMP.', '00244703', '7791864000444', ' 1552020', '', '0092', '0591', 'Comp.', '  E', 'N', '1.53', '2001-12-11', '0.14', '2001-12-06', '150.00', '350.00', '31', '0.00', '60.00'),
('0093', 'LANICOR Iny.', '01100601', '            0', '       0', '', '0093', '0077', 'Iny.', '  E', 'N', '2.97', '2001-07-27', '1.10', '2001-12-01', '120.00', '280.00', '31', '0.00', '100.00'),
('0098', 'ACALIX 60 mg.', '00001103', '7795345002811', '27662720', '', '0098', '0042', 'Comp.', '  E', 'N', '1.66', '2001-12-15', '0.13', '2001-11-19', '0.00', '0.00', '31', '0.00', '50.00'),
('0109', 'MEGADOSE INY', '00270903', '7798017281576', '31760310', '', '0109', '0509', 'AMP 200 MG X 5 ML', '  E', 'S', '74.03', '2001-12-15', '2.03', '2001-12-08', '300.00', '700.00', '31', '0.00', '100.00'),
('0094', 'ISORDIL 5 mg.', '00233102', '7792499022009', '  786620', '', '0094', '0041', 'Comp.', '  E', 'N', '2.05', '2001-09-25', '0.85', '2001-12-20', '75.00', '175.00', '31', '0.00', '50.00'),
('1121', 'AMPLIACTIL E.V. al 2,5%', '00026902', '7798012550028', ' 9139820', '', '1121', '0082', 'Iny.', '  F', 'N', '21.24', '2001-09-18', '2.10', '2001-12-08', '90.00', '210.00', '31', '0.00', '5.00'),
('1128', 'TEGRETOL 200 mg. Comp.', '00426002', '7795306207026', '30347020', '', '1128', '0082', 'Comp.', '  F', 'N', '4.67', '2001-08-16', '0.28', '2001-11-16', '0.00', '0.00', '31', '0.00', '60.00'),
('1191', 'DORMICUM INY', '00751901', '7795348001361', '40918720', '', '1191', '0042', 'AMP. 15 MG X 3 ML', '  F', 'S', '13.49', '2001-12-11', '2.80', '2001-12-26', '120.00', '280.00', '31', '0.00', '100.00'),
('1134', 'EPAMIN INY. 50MG/ML', '00155404', '7792690021382', '17143910', '', '1134', '0585', 'AMPOLLAS', '  F', 'N', '33.49', '2001-12-11', '1.20', '2001-12-08', '225.00', '525.00', '31', '0.00', '1.00'),
('1136', 'LUMINAL 0,1 gr. COMP', '00761402', '7793640237402', ' 1450620', '', '1136', '0508', 'Comp.', '  F', 'N', '3.61', '2000-01-20', '0.28', '2001-10-04', '2400.00', '5600.00', '31', '0.00', '60.00'),
('1138', 'FENOBARBITAL 200 mg.', '01268508', '7798066760299', '36227810', '', '1138', '0077', 'Iny.', '  F', 'N', '11.16', '1899-12-30', '3.20', '2001-06-18', '0.00', '0.00', '31', '0.00', '5.00'),
('1139', 'HALOPIDOL 2 MG GTS.', '00931007', '            0', '       0', '', '1139', '0293', 'GOTAS X 20 ML', '  F', 'N', '12.30', '2001-08-13', '5.95', '2001-11-30', '24.00', '56.00', '31', '0.00', '1.00'),
('1133', 'LOTOQUIS SIMPLE COMP', '00262501', '7795328057159', '10405030', '', '1133', '0075', 'Comp.', '  F', 'N', '7.65', '2001-10-11', '0.31', '2001-12-07', '750.00', '1750.00', '31', '0.00', '100.00'),
('1132', 'LOTOQUIS  COMP', '00262401', '7795328057104', ' 6075760', '', '1132', '0075', 'Comp.', '  F', 'N', '7.92', '1899-12-30', '0.85', '2001-11-05', '0.00', '0.00', '31', '0.00', '100.00'),
('0043', 'TRAPAX 2,5 mg.', '00441915', '7792499222003', '20702850', '', '0043', '0041', 'Comp.', '  F', 'N', '1.94', '2001-10-26', '0.16', '2001-12-07', '750.00', '1750.00', '31', '0.00', '60.00'),
('1661', 'AGUA BIDEST. APIROG. ESTERIL x 10cc', '00009305', '            0', '       0', '', '1661', '0509', 'AMP. x 10 cc', '  U', 'N', '3.76', '2001-12-11', '0.38', '2001-12-20', '150.00', '350.00', '31', '0.00', '1.00'),
('0045', 'TRAPAX 2 mg. S/L', '00261611', '7795375000696', '20514760', '', '0045', '0041', 'Comp.', '  F', 'N', '3.05', '1899-12-30', '0.57', '2000-06-16', '0.00', '0.00', '31', '0.00', '5.00'),
('1130', 'VALIUM 10 mg. Iny.', '00457210', '7660113955807', ' 8904620', '', '1130', '0042', 'AMP 10 MG X 2 ML', '  F', 'N', '20.88', '2001-12-15', '1.10', '2001-12-26', '675.00', '1575.00', '31', '0.00', '5.00'),
('1131', 'VALIUM 5 mg.', '00457208', '7792371087355', ' 8908830', '', '1131', '9160', 'Comp.X 5 MG', '  F', 'N', '2.01', '2001-06-05', '0.12', '2001-12-26', '0.00', '0.00', '31', '0.00', '60.00'),
('1103', 'PILOTIM Gts.', '00344401', '7896548120446', '31152210', '', '1103', '0428', 'GOTAS', '  D', 'N', '69.38', '1997-05-21', '27.52', '1997-05-21', '0.00', '0.00', '31', '0.00', '1.00'),
('1102', 'OFTALMOLETS Gts.', '00319202', '7792086058053', '29857210', '', '1102', '0428', 'GOTAS', '  D', 'N', '139.81', '2001-11-27', '17.41', '2001-11-15', '0.00', '0.00', '31', '0.00', '1.00'),
('1101', 'OFTALMOLETS POMADA.-', '01015203', '7798009272698', '41182720', '', '1101', '0428', 'CREMA', '  D', 'N', '8.98', '2001-11-27', '2.40', '2001-11-15', '0.00', '0.00', '31', '0.00', '20.00'),
('0912', 'DELAK 5MG Comp.', '00484704', '7795362018604', '33715650', '', '0912', '0155', 'Comp.', '  B', 'N', '3.93', '1997-07-10', '0.19', '2000-09-22', '0.00', '0.00', '31', '0.00', '60.00'),
('1083', 'CICLOPENTOLATO Gts.', '00089301', '7795368000467', ' 8248830', '', '1083', '0700', 'GOTAS', '  D', 'N', '80.44', '2001-11-27', '27.00', '2001-11-15', '0.00', '0.00', '31', '0.00', '1.00'),
('1097', 'FORTCINOLONA 40 MG. FCO. AMP.', '02295401', '7794428012143', '61179710', '', '1097', '', 'Inyectable', '  D', 'N', '301.10', '2001-06-15', '286.00', '2001-09-06', '0.00', '0.00', '31', '0.00', '1.00'),
('0925', 'MADOPAR 250 mgs. Comp.', '00265102', '7792371032508', '27855810', '', '0925', '0355', 'comprimidos', '', 'N', '7.30', '2001-08-16', '2.20', '2001-12-03', '0.00', '0.00', '31', '0.00', '30.00'),
('1183', 'CAFEINA AL 25% AMP', '00552601', '            0', '       0', '', '1183', '0251', 'AMPOLLAS', '  G', 'N', '4.90', '1998-04-01', '0.21', '1998-04-21', '30.00', '70.00', '31', '0.00', '100.00'),
('1185', 'DURACAINE HIPERBARICA amp.', '01117501', '            0', '       0', '', '1185', '0032', 'AMP.', '  G', 'N', '1425.00', '2001-12-11', '21.00', '2001-12-20', '75.00', '175.00', '31', '0.00', '1.00'),
('1186', 'DURACAINE HIPERBARICA C/AGUJA PHOENIX', '01504501', '7795376001760', '       0', '', '1186', '0344', 'JERINGA PRELLENADA', '  G', 'S', '68.69', '1899-12-30', '23.80', '1998-04-21', '0.00', '0.00', '31', '0.00', '1.00'),
('1187', 'DURACAINE 0,5%', '00653402', '            0', '       0', '', '1187', '0344', 'Inyectable', '  G', 'S', '14.00', '2001-10-19', '13.00', '2001-11-28', '45.00', '105.00', '31', '0.00', '1.00'),
('1184', 'EFFORTIL Iny.', '00148305', '7795304866171', ' 1621340', '', '1184', '9075', 'AMP.10 MG X ML', '  G', 'S', '6.01', '2001-11-05', '1.27', '2001-12-03', '0.00', '0.00', '31', '0.00', '3.00'),
('1232', 'FLUOTHANE 250 cc. x ml.', '01048201', '7795373014824', '       0', '', '1232', '0077', 'FRASCO x 250 cc', '  G', 'N', '898.90', '2001-06-15', '98.00', '2001-12-20', '0.00', '0.00', '31', '0.00', '1.00'),
('6074', 'DETERGENTE ENZIMATICO LIQUIDO.-', '', '', '', '', '6074', '', '', ' MD', 'N', '54.20', '2001-12-27', '24.80', '2001-12-03', '0.00', '0.00', '32', '0.00', '0.00'),
('1197', 'PENTHOTAL SODICO 1 gr. (tiopental)', '00339102', '7790375265557', '       0', '', '1197', '0032', 'AMP LIOF. 1 GR', '  G', 'N', '37.18', '2001-04-30', '16.00', '2001-12-26', '0.00', '0.00', '31', '0.00', '100.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mot_perd`
--

DROP TABLE IF EXISTS `mot_perd`;
CREATE TABLE `mot_perd` (
  `mp_cod` varchar(4) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Codigo',
  `mp_nom` varchar(30) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Descripción'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `mot_perd`
--

INSERT INTO `mot_perd` (`mp_cod`, `mp_nom`) VALUES
('1', 'motivooo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `topemedi`
--

DROP TABLE IF EXISTS `topemedi`;
CREATE TABLE `topemedi` (
  `id_techo` int(11) NOT NULL,
  `TM_CODSERV` varchar(3) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Servicio',
  `TM_DEPOSITO` varchar(2) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Deposito',
  `TM_CODMON` varchar(4) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Monodroga',
  `TM_CANTID` decimal(12,2) DEFAULT NULL COMMENT 'Cantidad'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `topemedi`
--

INSERT INTO `topemedi` (`id_techo`, `TM_CODSERV`, `TM_DEPOSITO`, `TM_CODMON`, `TM_CANTID`) VALUES
(1, '022', '1', '0001', '30.00'),
(2, '022', '1', '0017', '78.00'),
(3, '030', '1', '0001', '24.00'),
(4, '030', '1', '1231', '72.00'),
(5, '030', '1', '1240', '372.00'),
(8, '030', '1', '0116', '225.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vias`
--

DROP TABLE IF EXISTS `vias`;
CREATE TABLE `vias` (
  `VI_CODIGO` varchar(2) COLLATE utf8_bin NOT NULL COMMENT 'Código',
  `VI_DESCRI` varchar(30) COLLATE utf8_bin DEFAULT NULL COMMENT 'Descripción'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `vias`
--

INSERT INTO `vias` (`VI_CODIGO`, `VI_DESCRI`) VALUES
('1', 'via 1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acciont`
--
ALTER TABLE `acciont`
  ADD PRIMARY KEY (`AC_COD`);

--
-- Indices de la tabla `alarmas`
--
ALTER TABLE `alarmas`
  ADD PRIMARY KEY (`AL_CODIGO`);

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
  ADD KEY `FK_artic_gral_servicio` (`AG_PROVINT`);

--
-- Indices de la tabla `clases`
--
ALTER TABLE `clases`
  ADD PRIMARY KEY (`CL_COD`);

--
-- Indices de la tabla `deposito`
--
ALTER TABLE `deposito`
  ADD PRIMARY KEY (`DE_CODIGO`);

--
-- Indices de la tabla `droga`
--
ALTER TABLE `droga`
  ADD PRIMARY KEY (`DR_CODIGO`),
  ADD KEY `FK_droga_clases` (`DR_CLASE`);

--
-- Indices de la tabla `labo`
--
ALTER TABLE `labo`
  ADD PRIMARY KEY (`LA_CODIGO`),
  ADD KEY `la_cod` (`LA_CODIGO`),
  ADD KEY `la_nom` (`LA_NOMBRE`);

--
-- Indices de la tabla `medic`
--
ALTER TABLE `medic`
  ADD KEY `me_cod` (`ME_CODIGO`),
  ADD KEY `me_mon` (`ME_CODMON`),
  ADD KEY `me_nom` (`ME_NOMCOM`);

--
-- Indices de la tabla `mot_perd`
--
ALTER TABLE `mot_perd`
  ADD PRIMARY KEY (`mp_cod`);

--
-- Indices de la tabla `topemedi`
--
ALTER TABLE `topemedi`
  ADD PRIMARY KEY (`id_techo`),
  ADD KEY `TM_DEPOSITO` (`TM_DEPOSITO`),
  ADD KEY `FK_topemedi_servicio` (`TM_CODSERV`);

--
-- Indices de la tabla `vias`
--
ALTER TABLE `vias`
  ADD PRIMARY KEY (`VI_CODIGO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `topemedi`
--
ALTER TABLE `topemedi`
  MODIFY `id_techo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `artic_gral`
--
ALTER TABLE `artic_gral`
  ADD CONSTRAINT `FK_artic_gral_acciont` FOREIGN KEY (`AG_ACCION`) REFERENCES `acciont` (`AC_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_artic_gral_clases` FOREIGN KEY (`AG_CODCLA`) REFERENCES `clases` (`CL_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_artic_gral_deposito` FOREIGN KEY (`AG_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_artic_gral_droga` FOREIGN KEY (`AG_DROGA`) REFERENCES `droga` (`DR_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_artic_gral_servicio` FOREIGN KEY (`AG_PROVINT`) REFERENCES `servicio` (`SE_CODIGO`),
  ADD CONSTRAINT `FK_artic_gral_vias` FOREIGN KEY (`AG_VIA`) REFERENCES `vias` (`vi_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `droga`
--
ALTER TABLE `droga`
  ADD CONSTRAINT `FK_droga_clases` FOREIGN KEY (`DR_CLASE`) REFERENCES `clases` (`CL_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `topemedi`
--
ALTER TABLE `topemedi`
  ADD CONSTRAINT `FK_topemedi_deposito` FOREIGN KEY (`TM_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_topemedi_servicio` FOREIGN KEY (`TM_CODSERV`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
