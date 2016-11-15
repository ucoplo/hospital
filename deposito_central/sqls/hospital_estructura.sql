-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.1.9-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win32
-- HeidiSQL Versión:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando estructura para tabla hospital.acciont
CREATE TABLE IF NOT EXISTS `acciont` (
  `AC_COD` varchar(3) NOT NULL COMMENT 'Código',
  `AC_DESCRI` varchar(30) NOT NULL COMMENT 'Descripción',
  PRIMARY KEY (`AC_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.adq_reng
CREATE TABLE IF NOT EXISTS `adq_reng` (
  `AR_RENUM` int(11) NOT NULL COMMENT 'Número de remito',
  `AR_DEPOSITO` varchar(2) NOT NULL COMMENT 'Código del subdepósito',
  `AR_NROREN` smallint(6) NOT NULL COMMENT 'Número de renglón',
  `AR_CODART` varchar(4) NOT NULL COMMENT 'Código de articulo',
  `AR_PRECIO` decimal(12,3) NOT NULL COMMENT 'Precio de la compra',
  `AR_CANTID` decimal(10,2) NOT NULL COMMENT 'Cantidad entregada',
  `AR_FECVTO` date NOT NULL COMMENT 'Fecha de vencimiento',
  PRIMARY KEY (`AR_RENUM`,`AR_DEPOSITO`,`AR_CODART`),
  KEY `FK_adq_reng_deposito` (`AR_DEPOSITO`),
  KEY `FK_adq_reng_artic_gral` (`AR_CODART`,`AR_DEPOSITO`),
  CONSTRAINT `FK_adq_reng_artic_gral` FOREIGN KEY (`AR_CODART`, `AR_DEPOSITO`) REFERENCES `artic_gral` (`AG_CODIGO`, `AG_DEPOSITO`),
  CONSTRAINT `FK_adq_reng_deposito` FOREIGN KEY (`AR_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`),
  CONSTRAINT `FK_adq_reng_remito_adq` FOREIGN KEY (`AR_RENUM`) REFERENCES `remito_adq` (`RA_NUM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Renglones de Remitos de adquisición de depósito remito_adq';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.alarmas
CREATE TABLE IF NOT EXISTS `alarmas` (
  `AL_CODMON` varchar(4) CHARACTER SET utf8 NOT NULL COMMENT 'Código Monodroga',
  `AL_DEPOSITO` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT 'Depósito',
  `AL_MIN` int(11) DEFAULT NULL COMMENT 'Punto mínimo de consumo normal semanal ',
  `AL_MAX` int(11) DEFAULT NULL COMMENT 'Consumo máximo normal semanal',
  PRIMARY KEY (`AL_CODMON`,`AL_DEPOSITO`),
  KEY `FK_alarmas_deposito` (`AL_DEPOSITO`),
  CONSTRAINT `FK_alarmas_artic_gral_2` FOREIGN KEY (`AL_CODMON`, `AL_DEPOSITO`) REFERENCES `artic_gral` (`AG_CODIGO`, `AG_DEPOSITO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_alarmas_deposito` FOREIGN KEY (`AL_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.ambu_enc
CREATE TABLE IF NOT EXISTS `ambu_enc` (
  `AM_HISCLI` int(11) DEFAULT NULL COMMENT ' historia clínica del paciente',
  `AM_NUMVALE` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Número de vale',
  `AM_FECHA` date DEFAULT NULL COMMENT 'Indica la fecha de la entrega de un medicamento en ventanilla de farmacia a un paciente ambulatorio',
  `AM_HORA` time DEFAULT NULL COMMENT 'Indica la hora de la entrega de un medicamento en ventanilla de farmacia a un paciente ambulatorio',
  `AM_PROG` varchar(2) DEFAULT NULL COMMENT 'Indica el programa al cual está asociado',
  `AM_ENTIDER` varchar(3) DEFAULT NULL COMMENT 'Indica la entidad que lo deriva',
  `AM_MEDICO` varchar(6) DEFAULT NULL COMMENT 'Matricula del médico que indicó la medicación',
  `AM_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Código del subdepósito de farmacia',
  `AM_FARMACEUTICO` varchar(6) DEFAULT NULL COMMENT 'Matricula del farmaceutico',
  PRIMARY KEY (`AM_NUMVALE`),
  KEY `FK_ambu_enc_deposito` (`AM_DEPOSITO`),
  KEY `FK_ambu_enc_programa` (`AM_PROG`),
  KEY `FK_ambu_enc_enti_der` (`AM_ENTIDER`),
  KEY `FK_ambu_enc_paciente` (`AM_HISCLI`),
  KEY `FK_ambu_enc_legajos` (`AM_MEDICO`),
  KEY `FK_ambu_enc_legajos_2` (`AM_FARMACEUTICO`),
  CONSTRAINT `FK_ambu_enc_deposito` FOREIGN KEY (`AM_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_ambu_enc_enti_der` FOREIGN KEY (`AM_ENTIDER`) REFERENCES `enti_der` (`ED_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_ambu_enc_legajos` FOREIGN KEY (`AM_MEDICO`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_ambu_enc_legajos_2` FOREIGN KEY (`AM_FARMACEUTICO`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_ambu_enc_paciente` FOREIGN KEY (`AM_HISCLI`) REFERENCES `paciente` (`PA_HISCLI`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_ambu_enc_programa` FOREIGN KEY (`AM_PROG`) REFERENCES `programa` (`PR_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Encabezado de los retiros de medicamentos por ventanilla, el destinatario es un paciente ambulatorio';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.ambu_ren
CREATE TABLE IF NOT EXISTS `ambu_ren` (
  `AM_NUMVALE` int(11) NOT NULL COMMENT 'Número de vale',
  `AM_NUMREN` smallint(6) NOT NULL COMMENT 'Número de renglón',
  `AM_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Subdeposito de farmacia',
  `AM_CODMON` varchar(4) DEFAULT NULL COMMENT 'Código del medicamento (validado con la Tabla Medic)',
  `AM_CANTPED` decimal(7,2) DEFAULT NULL COMMENT 'Cantidad pedida',
  `AM_CANTENT` decimal(7,2) DEFAULT NULL COMMENT 'Cantidad entregada',
  `AM_FECVTO` date DEFAULT NULL COMMENT 'Fecha de vencimiento del lote',
  PRIMARY KEY (`AM_NUMVALE`,`AM_NUMREN`),
  KEY `FK_ambu_ren_deposito` (`AM_DEPOSITO`),
  KEY `FK_ambu_ren_artic_gral` (`AM_CODMON`),
  CONSTRAINT `FK_ambu_ren_ambu_enc` FOREIGN KEY (`AM_NUMVALE`) REFERENCES `ambu_enc` (`AM_NUMVALE`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_ambu_ren_artic_gral` FOREIGN KEY (`AM_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_ambu_ren_deposito` FOREIGN KEY (`AM_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Los renglones de los vales de suminitro de medicamentos por ventanilla';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.artic_gral
CREATE TABLE IF NOT EXISTS `artic_gral` (
  `AG_CODIGO` varchar(4) NOT NULL COMMENT 'Codigo',
  `AG_NOMBRE` varchar(40) NOT NULL COMMENT 'Nombre',
  `AG_CODMED` varchar(4) DEFAULT NULL COMMENT 'Medicamento',
  `AG_PRES` text NOT NULL COMMENT 'Presentacion',
  `AG_CODRAF` varchar(16) NOT NULL COMMENT 'Código de Rafam ',
  `AG_STACT` decimal(12,2) NOT NULL COMMENT 'Stock Farmacia',
  `AG_STACDEP` decimal(12,2) NOT NULL COMMENT 'Stock Deposito',
  `AG_CODCLA` varchar(2) NOT NULL COMMENT 'Clase',
  `AG_FRACCQ` varchar(1) NOT NULL COMMENT 'Fraccionado',
  `AG_PSICOF` varchar(1) NOT NULL COMMENT 'Psicofármaco',
  `AG_PTOMIN` decimal(10,2) NOT NULL COMMENT 'Stock Minimo Deposito',
  `AG_FPTOMIN` decimal(10,2) NOT NULL COMMENT 'Stock Minimo Farmacia',
  `AG_PTOPED` decimal(10,2) NOT NULL COMMENT 'Stock Medio Deposito',
  `AG_FPTOPED` decimal(10,2) NOT NULL COMMENT 'Stock Medio Farmacia',
  `AG_PTOMAX` decimal(10,2) NOT NULL COMMENT 'Stock Maximo Deposito',
  `AG_FPTOMAX` decimal(10,2) NOT NULL COMMENT 'Stock Maximo Farmacia',
  `AG_CONSDIA` decimal(19,6) NOT NULL COMMENT 'Consumo Promedio Deposito',
  `AG_FCONSDI` decimal(19,6) NOT NULL COMMENT 'Consumo Promedio Farmacia',
  `AG_RENGLON` text NOT NULL COMMENT 'Solicitud Compras',
  `AG_PRECIO` decimal(12,3) NOT NULL COMMENT 'Precio ultima compra',
  `AG_REDOND` decimal(10,2) NOT NULL COMMENT 'Cantidad Minima a pedir',
  `AG_PUNTUAL` decimal(19,6) NOT NULL COMMENT 'Consumo Medio Deposito',
  `AG_FPUNTUAL` decimal(19,6) NOT NULL COMMENT 'Consumo Medio Farmacia',
  `AG_REPAUT` enum('F','T') NOT NULL COMMENT 'Reposición Automatica',
  `AG_ULTENT` date NOT NULL COMMENT 'Ultima entrada',
  `AG_ULTSAL` date NOT NULL COMMENT 'Ultima Salida',
  `AG_UENTDEP` date NOT NULL COMMENT 'Ultima entrada Deposito',
  `AG_USALDEP` date NOT NULL COMMENT 'Ultima salida Deposito',
  `AG_PROVINT` varchar(3) NOT NULL COMMENT 'Proveedor Interno',
  `AG_ACTIVO` enum('F','T') NOT NULL COMMENT 'Activo',
  `AG_VADEM` varchar(1) NOT NULL COMMENT 'Vademecum',
  `AG_ORIGUSUA` varchar(6) NOT NULL COMMENT 'Usuario',
  `AG_FRACSAL` varchar(1) NOT NULL COMMENT 'Fracciona en Sala',
  `AG_DROGA` varchar(4) NOT NULL COMMENT 'Droga',
  `AG_VIA` varchar(2) NOT NULL COMMENT 'Vía de acceso',
  `AG_DOSIS` decimal(12,4) NOT NULL COMMENT 'Dosis',
  `AG_ACCION` varchar(3) NOT NULL COMMENT 'Acción terapéutica',
  `AG_VISIBLE` enum('F','T') NOT NULL COMMENT 'Visisble desde Descartes',
  `AG_DEPOSITO` varchar(2) NOT NULL COMMENT 'Deposito',
  `AG_UNIENV` decimal(10,3) DEFAULT NULL COMMENT 'Unidades por envase',
  `AG_PRESENV` varchar(50) DEFAULT NULL COMMENT 'Presentación del envase',
  PRIMARY KEY (`AG_CODIGO`,`AG_DEPOSITO`),
  KEY `FK_artic_gral_deposito` (`AG_DEPOSITO`),
  KEY `FK_artic_gral_droga` (`AG_DROGA`),
  KEY `FK_artic_gral_vias` (`AG_VIA`),
  KEY `FK_artic_gral_clases` (`AG_CODCLA`),
  KEY `FK_artic_gral_acciont` (`AG_ACCION`),
  KEY `FK_artic_gral_servicio` (`AG_PROVINT`),
  CONSTRAINT `FK_artic_gral_acciont` FOREIGN KEY (`AG_ACCION`) REFERENCES `acciont` (`AC_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_artic_gral_clases` FOREIGN KEY (`AG_CODCLA`) REFERENCES `clases` (`CL_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_artic_gral_deposito` FOREIGN KEY (`AG_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_artic_gral_droga` FOREIGN KEY (`AG_DROGA`) REFERENCES `droga` (`DR_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_artic_gral_servicio` FOREIGN KEY (`AG_PROVINT`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_artic_gral_vias` FOREIGN KEY (`AG_VIA`) REFERENCES `vias` (`VI_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.barrios
CREATE TABLE IF NOT EXISTS `barrios` (
  `BA_CODIGO` int(3) NOT NULL AUTO_INCREMENT,
  `BA_NOMBRE` varchar(100) NOT NULL,
  PRIMARY KEY (`BA_CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.calles
CREATE TABLE IF NOT EXISTS `calles` (
  `CA_CODIGO` varchar(5) NOT NULL,
  `CA_NOM` varchar(40) DEFAULT NULL,
  `CA_CALLE` varchar(40) DEFAULT NULL,
  `CA_NACE` varchar(48) DEFAULT NULL,
  `CA_CORRE` varchar(73) DEFAULT NULL,
  `CA_DEN_ANT` varchar(33) DEFAULT NULL,
  `CA_COORDEN` varchar(10) DEFAULT NULL,
  `CA_OBSERV` varchar(57) DEFAULT NULL,
  PRIMARY KEY (`CA_CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.clases
CREATE TABLE IF NOT EXISTS `clases` (
  `CL_COD` varchar(2) NOT NULL COMMENT 'Codigo',
  `CL_NOM` varchar(30) NOT NULL COMMENT 'Descripción',
  PRIMARY KEY (`CL_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.consme3
CREATE TABLE IF NOT EXISTS `consme3` (
  `CM_NROREM` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Número de remito',
  `CM_FECHA` date DEFAULT NULL COMMENT 'Fecha',
  `CM_HORA` time DEFAULT NULL COMMENT 'Hora',
  `CM_SERSOL` varchar(3) DEFAULT NULL COMMENT 'Servicio Solicitante',
  `CM_ENFERM` varchar(6) DEFAULT NULL COMMENT 'Personal de Enfermería',
  `CM_CODOPE` varchar(6) DEFAULT NULL COMMENT 'Personal de Farmacia',
  `CM_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Subdepósito de farmacia',
  `CM_PROCESADO` tinyint(1) DEFAULT NULL COMMENT 'Procesado',
  PRIMARY KEY (`CM_NROREM`),
  KEY `FK_consme3_servicio` (`CM_SERSOL`),
  KEY `FK_consme3_legajos` (`CM_ENFERM`),
  KEY `FK_consme3_legajos_2` (`CM_CODOPE`),
  KEY `FK_consme3_deposito` (`CM_DEPOSITO`),
  CONSTRAINT `FK_consme3_deposito` FOREIGN KEY (`CM_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_consme3_legajos` FOREIGN KEY (`CM_ENFERM`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_consme3_legajos_2` FOREIGN KEY (`CM_CODOPE`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_consme3_servicio` FOREIGN KEY (`CM_SERSOL`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Es el encabezado de los remitos de medicamentos de entrega a servicios pedidos a granel\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.consmed
CREATE TABLE IF NOT EXISTS `consmed` (
  `CM_NROVAL` int(12) NOT NULL AUTO_INCREMENT COMMENT 'Número de Vale',
  `CM_NROREM` varchar(12) NOT NULL COMMENT 'Número de remito',
  `CM_HISCLI` int(11) DEFAULT NULL COMMENT 'Historia Clínica',
  `CM_FECHA` date DEFAULT NULL COMMENT 'Fecha',
  `CM_HORA` time DEFAULT NULL COMMENT 'Hora',
  `CM_SERSOL` varchar(3) DEFAULT NULL COMMENT 'Servicio Solicitante',
  `CM_CODOPE` varchar(6) DEFAULT NULL COMMENT 'Personal de Farmacia',
  `CM_UNIDIAG` varchar(3) DEFAULT NULL COMMENT 'Unidad de Diagnóstico',
  `CM_CONDPAC` enum('A','I') DEFAULT NULL COMMENT 'Condicion del paciente (Internado o Ambulatorio)',
  `CM_SUPERV` varchar(6) DEFAULT NULL COMMENT 'Personal de Enfermería',
  `CM_MEDICO` varchar(6) DEFAULT NULL COMMENT 'Médico',
  `CM_PROCESADO` tinyint(1) DEFAULT NULL COMMENT 'Si el vale está procesado (True) no se puede borrar ni modificar, este campo cambia a True cuando se genera el PDF del remito',
  `CM_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Depósito',
  `CM_IDINTERNA` bigint(20) DEFAULT NULL COMMENT 'Es el ID de la Internación (Tabla Interna)',
  PRIMARY KEY (`CM_NROVAL`),
  KEY `FK_consmed_paciente` (`CM_HISCLI`),
  KEY `FK_consmed_legajos` (`CM_MEDICO`),
  KEY `FK_consmed_servicio` (`CM_SERSOL`),
  KEY `FK_consmed_legajos_2` (`CM_CODOPE`),
  KEY `FK_consmed_legajos_3` (`CM_SUPERV`),
  KEY `FK_consmed_servicio_2` (`CM_UNIDIAG`),
  KEY `FK_consmed_deposito` (`CM_DEPOSITO`),
  KEY `FK_consmed_interna` (`CM_IDINTERNA`),
  CONSTRAINT `FK_consmed_deposito` FOREIGN KEY (`CM_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON UPDATE NO ACTION,
  CONSTRAINT `FK_consmed_interna` FOREIGN KEY (`CM_IDINTERNA`) REFERENCES `interna` (`IN_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_consmed_legajos` FOREIGN KEY (`CM_MEDICO`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_consmed_legajos_2` FOREIGN KEY (`CM_CODOPE`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_consmed_legajos_3` FOREIGN KEY (`CM_SUPERV`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_consmed_paciente` FOREIGN KEY (`CM_HISCLI`) REFERENCES `paciente` (`PA_HISCLI`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_consmed_servicio` FOREIGN KEY (`CM_SERSOL`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_consmed_servicio_2` FOREIGN KEY (`CM_UNIDIAG`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Entregas por Paciente';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.dc_devoprov
CREATE TABLE IF NOT EXISTS `dc_devoprov` (
  `DD_NROREM` int(12) NOT NULL AUTO_INCREMENT COMMENT 'Número del remito',
  `DD_FECHA` date DEFAULT NULL COMMENT 'Fecha de devolución',
  `DD_HORA` time DEFAULT NULL COMMENT 'Hora de devolución',
  `DD_PROVE` varchar(5) DEFAULT NULL COMMENT 'Código del Proveedor',
  `DD_CODOPE` varchar(6) DEFAULT NULL COMMENT 'Personal de Depósito',
  `DD_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Código del subdepósito',
  `DD_COMENTARIO` text COMMENT 'Comentario',
  PRIMARY KEY (`DD_NROREM`),
  KEY `FK_dc_devoprov_deposito` (`DD_DEPOSITO`),
  KEY `FK_dc_devoprov_legajos` (`DD_CODOPE`),
  KEY `FK_dc_devoprov_dc_proveedores` (`DD_PROVE`),
  CONSTRAINT `FK_dc_devoprov_dc_proveedores` FOREIGN KEY (`DD_PROVE`) REFERENCES `proveedores` (`PR_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_dc_devoprov_deposito` FOREIGN KEY (`DD_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_dc_devoprov_legajos` FOREIGN KEY (`DD_CODOPE`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Remitos de devolución de medicamentos a un proveedor externo - Encabezado';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.dc_movsto
CREATE TABLE IF NOT EXISTS `dc_movsto` (
  `DM_COD` varchar(1) NOT NULL,
  `DM_NOM` varchar(25) NOT NULL,
  `DM_SIGNO` tinyint(1) NOT NULL,
  `DM_VALIDO` tinyint(1) NOT NULL,
  PRIMARY KEY (`DM_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tipo de movimiento de stock de Depósito Central\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.dc_mov_dia
CREATE TABLE IF NOT EXISTS `dc_mov_dia` (
  `DM_FECHA` date NOT NULL COMMENT 'Fecha del movimiento',
  `DM_CODMOV` varchar(1) NOT NULL COMMENT 'Código del movimiento (Relacionado con dc_movsto)',
  `DM_CANT` decimal(12,2) DEFAULT NULL COMMENT 'Cantidad',
  `DM_FECVTO` date NOT NULL COMMENT 'Fecha de vencimiento',
  `DM_CODART` varchar(4) NOT NULL COMMENT 'Código de la articulo',
  `DM_DEPOSITO` varchar(2) NOT NULL COMMENT 'Código del subdepósito',
  PRIMARY KEY (`DM_CODART`,`DM_DEPOSITO`,`DM_FECVTO`,`DM_FECHA`,`DM_CODMOV`),
  KEY `FK_dc_mov_dia_deposito` (`DM_DEPOSITO`),
  KEY `FK_dc_mov_dia_movsto` (`DM_CODMOV`),
  CONSTRAINT `FK_dc_mov_dia_artic_gral` FOREIGN KEY (`DM_CODART`, `DM_DEPOSITO`) REFERENCES `artic_gral` (`AG_CODIGO`, `AG_DEPOSITO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_dc_mov_dia_deposito` FOREIGN KEY (`DM_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_dc_mov_dia_movsto` FOREIGN KEY (`DM_CODMOV`) REFERENCES `dc_movsto` (`DM_COD`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Es el archivo de movimientos diario de Depósito Central, intentado resumir las entradas y salidas del día';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.dc_perdidas
CREATE TABLE IF NOT EXISTS `dc_perdidas` (
  `DP_NROREM` int(12) NOT NULL AUTO_INCREMENT COMMENT 'Número Pérdida',
  `DP_FECHA` date DEFAULT NULL COMMENT 'Fecha',
  `DP_HORA` time DEFAULT NULL COMMENT 'Hora',
  `DP_MOTIVO` varchar(4) DEFAULT NULL COMMENT 'Motivo',
  `DP_CODOPE` varchar(6) DEFAULT NULL COMMENT 'Personal de Depósito',
  `DP_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Depósito',
  PRIMARY KEY (`DP_NROREM`),
  KEY `FK_dc_perdidas_mot_perd` (`DP_MOTIVO`),
  KEY `FK_dc_perdidas_deposito` (`DP_DEPOSITO`),
  KEY `FK_dc_perdidas_legajos` (`DP_CODOPE`),
  CONSTRAINT `FK_dc_perdidas_deposito` FOREIGN KEY (`DP_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_dc_perdidas_legajos` FOREIGN KEY (`DP_CODOPE`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_dc_perdidas_mot_perd` FOREIGN KEY (`DP_MOTIVO`) REFERENCES `mot_perd` (`MP_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Perdidas en depósito';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.dc_perd_reng
CREATE TABLE IF NOT EXISTS `dc_perd_reng` (
  `DR_NROREM` int(11) NOT NULL COMMENT 'Número de Remito Pérdida',
  `DR_DEPOSITO` varchar(2) NOT NULL COMMENT 'Subdepósito de farmacia',
  `DR_CODART` varchar(4) NOT NULL COMMENT 'Código del medicamento ',
  `DR_CANTID` decimal(9,2) DEFAULT NULL COMMENT 'Cantidad',
  `DR_FECVTO` date DEFAULT NULL COMMENT 'Fecha de vencimiento del medicamento',
  PRIMARY KEY (`DR_NROREM`,`DR_DEPOSITO`,`DR_CODART`),
  KEY `FK_dc_perd_reng_deposito` (`DR_DEPOSITO`),
  KEY `FK_dc_perd_reng_artic_gral` (`DR_CODART`,`DR_DEPOSITO`),
  CONSTRAINT `FK_dc_perd_reng_artic_gral` FOREIGN KEY (`DR_CODART`, `DR_DEPOSITO`) REFERENCES `artic_gral` (`AG_CODIGO`, `AG_DEPOSITO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_dc_perd_reng_deposito` FOREIGN KEY (`DR_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_dc_perd_reng_perdidas` FOREIGN KEY (`DR_NROREM`) REFERENCES `dc_perdidas` (`DP_NROREM`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.dc_tab_vtos
CREATE TABLE IF NOT EXISTS `dc_tab_vtos` (
  `DT_CODART` varchar(4) NOT NULL COMMENT 'Código del artículo',
  `DT_FECVEN` date NOT NULL COMMENT 'Fecha de vencimiento',
  `DT_SALDO` decimal(12,2) DEFAULT NULL COMMENT 'Saldo es lo que queda',
  `DT_DEPOSITO` varchar(2) NOT NULL COMMENT 'Código del subdepósito de farmacia',
  PRIMARY KEY (`DT_CODART`,`DT_FECVEN`,`DT_DEPOSITO`),
  KEY `FK_dc_tab_vtos_deposito` (`DT_DEPOSITO`),
  KEY `FK_dc_tab_vtos_artic_gral` (`DT_CODART`,`DT_DEPOSITO`),
  CONSTRAINT `FK_dc_tab_vtos_artic_gral` FOREIGN KEY (`DT_CODART`, `DT_DEPOSITO`) REFERENCES `artic_gral` (`AG_CODIGO`, `AG_DEPOSITO`),
  CONSTRAINT `FK_dc_tab_vtos_deposito` FOREIGN KEY (`DT_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vencimientos, por cada artículo y depósitos los distintos lotes que tienen cantidad y vencimiento';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.deposito
CREATE TABLE IF NOT EXISTS `deposito` (
  `DE_CODIGO` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT 'Codigo',
  `DE_DESCR` varchar(45) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Nombre',
  PRIMARY KEY (`DE_CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.devofar
CREATE TABLE IF NOT EXISTS `devofar` (
  `DF_NRODEVOL` int(11) NOT NULL,
  `DF_DEPOSITO` varchar(2) NOT NULL,
  `DF_CODMON` varchar(4) NOT NULL,
  `DF_CANTID` decimal(9,2) DEFAULT NULL,
  `DF_FECVTO` date NOT NULL,
  PRIMARY KEY (`DF_NRODEVOL`,`DF_DEPOSITO`,`DF_CODMON`,`DF_FECVTO`),
  KEY `FK_devofar_deposito` (`DF_DEPOSITO`),
  KEY `FK_devofar_artic_gral` (`DF_CODMON`),
  CONSTRAINT `FK_devofar_artic_gral` FOREIGN KEY (`DF_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devofar_deposito` FOREIGN KEY (`DF_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devofar_devoluc` FOREIGN KEY (`DF_NRODEVOL`) REFERENCES `devoluc` (`DE_NRODEVOL`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Devolución a granel renglones';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.devoluc
CREATE TABLE IF NOT EXISTS `devoluc` (
  `DE_NRODEVOL` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Número Devolución',
  `DE_FECHA` date DEFAULT NULL COMMENT 'Fecha',
  `DE_HORA` time DEFAULT NULL COMMENT 'Hora',
  `DE_SERSOL` varchar(3) DEFAULT NULL COMMENT 'Servicio solicitante',
  `DE_CODOPE` varchar(6) DEFAULT NULL COMMENT 'Personal de Farmacia',
  `DE_ENFERM` varchar(6) DEFAULT NULL COMMENT 'Personal de Enfermería',
  `DE_SOBRAN` tinyint(1) DEFAULT '0' COMMENT 'Indica si fue sobrante de Sala',
  `DE_NUMREMOR` int(11) DEFAULT NULL COMMENT 'Número del remito original',
  `DE_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Depósito',
  PRIMARY KEY (`DE_NRODEVOL`),
  KEY `FK_devoluc_servicio` (`DE_SERSOL`),
  KEY `FK_devoluc_legajos` (`DE_CODOPE`),
  KEY `FK_devoluc_legajos_2` (`DE_ENFERM`),
  KEY `FK_devoluc_deposito` (`DE_DEPOSITO`),
  KEY `FK_devoluc_consme3` (`DE_NUMREMOR`),
  CONSTRAINT `FK_devoluc_consme3` FOREIGN KEY (`DE_NUMREMOR`) REFERENCES `consme3` (`CM_NROREM`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devoluc_deposito` FOREIGN KEY (`DE_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devoluc_legajos` FOREIGN KEY (`DE_CODOPE`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devoluc_legajos_2` FOREIGN KEY (`DE_ENFERM`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devoluc_servicio` FOREIGN KEY (`DE_SERSOL`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Devolución a granel\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.devoluc2
CREATE TABLE IF NOT EXISTS `devoluc2` (
  `DE_NRODEVOL` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Número Devolución',
  `DE_HISCLI` int(11) DEFAULT NULL COMMENT 'Historia Clínica',
  `DE_FECHA` date DEFAULT NULL COMMENT 'Fecha',
  `DE_HORA` time DEFAULT NULL COMMENT 'Hora',
  `DE_SERSOL` varchar(3) DEFAULT NULL COMMENT 'Servicio solicitante',
  `DE_CODOPE` varchar(6) DEFAULT NULL COMMENT 'Personal de Farmacia',
  `DE_ENFERM` varchar(6) DEFAULT NULL COMMENT 'Personal de Enfermería',
  `DE_UNIDIAG` varchar(3) DEFAULT NULL COMMENT 'Unidad de diagnóstico solicitante',
  `DE_NUMVALOR` int(12) DEFAULT NULL COMMENT 'Número del vale original',
  `DE_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Depósito',
  `DE_IDINTERNA` bigint(20) DEFAULT NULL COMMENT 'Es el ID de la Internación (Tabla Interna)',
  PRIMARY KEY (`DE_NRODEVOL`),
  KEY `FK_devoluc2_paciente` (`DE_HISCLI`),
  KEY `FK_devoluc2_servicio` (`DE_SERSOL`),
  KEY `FK_devoluc2_legajos` (`DE_CODOPE`),
  KEY `FK_devoluc2_legajos_2` (`DE_ENFERM`),
  KEY `FK_devoluc2_deposito` (`DE_DEPOSITO`),
  KEY `FK_devoluc2_consmed` (`DE_NUMVALOR`),
  KEY `FK_devoluc2_interna` (`DE_IDINTERNA`),
  CONSTRAINT `FK_devoluc2_consmed` FOREIGN KEY (`DE_NUMVALOR`) REFERENCES `consmed` (`CM_NROVAL`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devoluc2_deposito` FOREIGN KEY (`DE_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devoluc2_interna` FOREIGN KEY (`DE_IDINTERNA`) REFERENCES `interna` (`IN_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devoluc2_legajos` FOREIGN KEY (`DE_CODOPE`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devoluc2_legajos_2` FOREIGN KEY (`DE_ENFERM`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devoluc2_paciente` FOREIGN KEY (`DE_HISCLI`) REFERENCES `paciente` (`PA_HISCLI`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devoluc2_servicio` FOREIGN KEY (`DE_SERSOL`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Devolución por paciente\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.devoprov
CREATE TABLE IF NOT EXISTS `devoprov` (
  `DE_NROREM` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Número del remito',
  `DE_FECHA` date DEFAULT NULL COMMENT 'Fecha de devolución',
  `DE_HORA` time DEFAULT NULL COMMENT 'Hora de devolución',
  `DE_DESTINO` varchar(1) DEFAULT NULL COMMENT 'D Depósito Central, E Externo',
  `DE_PROVE` varchar(4) DEFAULT NULL COMMENT 'Código del Proveedor (Tabla Laboratorio',
  `DE_COMENTARIO` text,
  `DE_CODOPE` varchar(6) DEFAULT NULL COMMENT 'Personal de Farmacia',
  `DE_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Código del subdepósito de farmacia',
  PRIMARY KEY (`DE_NROREM`),
  KEY `FK_devoprov_deposito` (`DE_DEPOSITO`),
  KEY `FK_devoprov_legajos` (`DE_CODOPE`),
  KEY `FK_devoprov_labo` (`DE_PROVE`),
  CONSTRAINT `FK_devoprov_deposito` FOREIGN KEY (`DE_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devoprov_labo` FOREIGN KEY (`DE_PROVE`) REFERENCES `labo` (`LA_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devoprov_legajos` FOREIGN KEY (`DE_CODOPE`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Remitos de devolución de medicamentos a un proveedor externo - Encabezado';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.devprov_reng
CREATE TABLE IF NOT EXISTS `devprov_reng` (
  `DP_NROREM` int(11) NOT NULL COMMENT 'Número de remito ',
  `DP_NUMRENG` smallint(6) NOT NULL COMMENT 'Número de Renglón',
  `DP_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Código del subdepósito',
  `DP_CODART` varchar(4) DEFAULT NULL COMMENT 'Código del articulo devuelto',
  `DP_CANTID` decimal(9,2) DEFAULT NULL COMMENT 'Cantidad devuelta',
  `DP_FECVTO` date DEFAULT NULL COMMENT 'Fecha del vencimiento del medicamento devuelto',
  PRIMARY KEY (`DP_NROREM`,`DP_NUMRENG`),
  KEY `FK_devprov_reng_deposito` (`DP_DEPOSITO`),
  KEY `FK_devprov_reng_artic_gral` (`DP_CODART`,`DP_DEPOSITO`),
  CONSTRAINT `FK_devprov_reng_artic_gral` FOREIGN KEY (`DP_CODART`, `DP_DEPOSITO`) REFERENCES `artic_gral` (`AG_CODIGO`, `AG_DEPOSITO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devprov_reng_dc_devoprov` FOREIGN KEY (`DP_NROREM`) REFERENCES `dc_devoprov` (`DD_NROREM`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_devprov_reng_deposito` FOREIGN KEY (`DP_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Renglones Remito de Devolución - DC_DEVOPROV';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.dev_prov
CREATE TABLE IF NOT EXISTS `dev_prov` (
  `DP_NROREM` int(11) NOT NULL COMMENT 'Número de remito ',
  `DP_NUMRENG` smallint(6) NOT NULL COMMENT 'Número de Renglón',
  `DP_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Código del subdepósito de farmacia',
  `DP_CODMON` varchar(4) DEFAULT NULL COMMENT 'Código del medicamento devuelto',
  `DP_CANTID` decimal(9,2) DEFAULT NULL COMMENT 'Cantidad devuelta',
  `DP_FECVTO` date DEFAULT NULL COMMENT 'Fecha del vencimiento del medicamento devuelto',
  PRIMARY KEY (`DP_NROREM`,`DP_NUMRENG`),
  KEY `FK_dev_prov_deposito` (`DP_DEPOSITO`),
  KEY `FK_dev_prov_artic_gral` (`DP_CODMON`),
  CONSTRAINT `FK_dev_prov_artic_gral` FOREIGN KEY (`DP_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`),
  CONSTRAINT `FK_dev_prov_deposito` FOREIGN KEY (`DP_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Renglones Remito de Devolución - DEVOPROV';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.dev_val
CREATE TABLE IF NOT EXISTS `dev_val` (
  `DV_NRODEVOL` int(11) NOT NULL COMMENT 'Número Devolución',
  `DV_HISCLI` int(11) NOT NULL COMMENT 'Historia Clínica',
  `DV_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Subdepósito de farmacia',
  `DV_CODMON` varchar(4) DEFAULT NULL COMMENT 'Código del medicamento devuelto',
  `DV_CANTID` decimal(7,2) DEFAULT NULL COMMENT 'Cantidad devuelta',
  `DV_FECVTO` date DEFAULT NULL COMMENT 'Fecha de vencimiento del medicamento',
  `DV_NUMRENG` smallint(6) NOT NULL COMMENT 'Número de renglón del vale de devolucion',
  PRIMARY KEY (`DV_NRODEVOL`,`DV_HISCLI`,`DV_NUMRENG`),
  KEY `FK_dev_val_deposito` (`DV_DEPOSITO`),
  KEY `FK_dev_val_artic_gral` (`DV_CODMON`),
  CONSTRAINT `FK_dev_val_artic_gral` FOREIGN KEY (`DV_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_dev_val_deposito` FOREIGN KEY (`DV_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_dev_val_devoluc2` FOREIGN KEY (`DV_NRODEVOL`) REFERENCES `devoluc2` (`DE_NRODEVOL`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.diagno
CREATE TABLE IF NOT EXISTS `diagno` (
  `DI_CODIGO` varchar(10) NOT NULL,
  `DI_DET` varchar(70) DEFAULT NULL,
  `DI_DET1` varchar(70) DEFAULT NULL,
  `DI_DET2` varchar(70) DEFAULT NULL,
  `DI_REVIS` varchar(1) DEFAULT NULL,
  `DI_CODOP` varchar(3) DEFAULT NULL,
  `DI_MODIF` date DEFAULT NULL,
  PRIMARY KEY (`DI_CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.droga
CREATE TABLE IF NOT EXISTS `droga` (
  `DR_CODIGO` varchar(4) CHARACTER SET utf8 NOT NULL COMMENT 'Código',
  `DR_DESCRI` text CHARACTER SET utf8 COMMENT 'Descripción',
  `DR_CLASE` varchar(2) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Clase',
  PRIMARY KEY (`DR_CODIGO`),
  KEY `FK_droga_clases` (`DR_CLASE`),
  CONSTRAINT `FK_droga_clases` FOREIGN KEY (`DR_CLASE`) REFERENCES `clases` (`CL_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.enti_der
CREATE TABLE IF NOT EXISTS `enti_der` (
  `ED_COD` varchar(3) NOT NULL,
  `ED_DETALLE` varchar(35) NOT NULL,
  PRIMARY KEY (`ED_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.etiqueta
CREATE TABLE IF NOT EXISTS `etiqueta` (
  `ET_COD` varchar(4) DEFAULT NULL,
  `ET_BMP` varchar(150) DEFAULT NULL,
  `ET_DESC` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.fa_remit
CREATE TABLE IF NOT EXISTS `fa_remit` (
  `RE_NUM` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Número de remito',
  `RE_FECHA` date DEFAULT NULL COMMENT 'Fecha',
  `RE_HORA` time DEFAULT NULL COMMENT 'Hora',
  `RE_CODOPE` varchar(6) DEFAULT NULL COMMENT 'Personal de Farmacia',
  `RE_CONCEP` text COMMENT 'Concepto',
  `RE_TIPMOV` varchar(1) DEFAULT NULL COMMENT 'Tipo de movimiento Compra o Donación (C o D) en caso de origen externo, NULL si proviene de Depósito Central',
  `RE_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Código del subdepósito de farmacia',
  `RE_REMDEP` int(11) DEFAULT NULL COMMENT 'Número de remito de Depósito',
  PRIMARY KEY (`RE_NUM`),
  KEY `FK_fa_remit_deposito` (`RE_DEPOSITO`),
  KEY `FK_fa_remit_legajos` (`RE_CODOPE`),
  KEY `FK_fa_remit_rs_encab` (`RE_REMDEP`),
  CONSTRAINT `FK_fa_remit_deposito` FOREIGN KEY (`RE_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_fa_remit_legajos` FOREIGN KEY (`RE_CODOPE`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_fa_remit_rs_encab` FOREIGN KEY (`RE_REMDEP`) REFERENCES `rs_encab` (`RS_NROREM`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Es el encabezado de los eventuales remitos de adquisición, únicas entradas que no provienen del Depósito Central';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.interna
CREATE TABLE IF NOT EXISTS `interna` (
  `IN_ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Id de la tabla',
  `IN_HISCLI` int(11) NOT NULL COMMENT 'Historia Clinica del Paciente',
  `IN_FECING` date NOT NULL COMMENT 'Fecha de Ingreso',
  `IN_HORING` time NOT NULL COMMENT 'Hora de Ingreso',
  `IN_MEDRES` varchar(6) DEFAULT NULL COMMENT 'Medico responsable de la internacion',
  `IN_CODOS` varchar(4) DEFAULT NULL COMMENT 'Codigo de la obra social, tabla obrasoci',
  `IN_TITU` varchar(1) DEFAULT NULL COMMENT 'Indica si es titular o no',
  `IN_COSEG` varchar(4) DEFAULT NULL COMMENT 'Codigo del Coseguro',
  `IN_NUMCOS` varchar(15) DEFAULT NULL COMMENT 'Numero de afiliado al coseguro',
  `IN_NUMCAR` varchar(15) DEFAULT NULL COMMENT 'Numero de carnet',
  `IN_SERING` varchar(3) DEFAULT NULL COMMENT 'Servicio de ingreso, tabla Servicio',
  `IN_SERINT` varchar(3) DEFAULT NULL COMMENT 'Servicio de internacion, tabla Servicio',
  `IN_SALA` varchar(2) DEFAULT NULL COMMENT 'Numero de la sala actual',
  `IN_NUMHAB` varchar(3) DEFAULT NULL COMMENT 'Numero de habitacion actual',
  `IN_UNDIAG` varchar(3) DEFAULT NULL COMMENT 'Unidad de diagnostico actual',
  `IN_MOTING` varchar(10) DEFAULT NULL COMMENT 'Motivo de ingreso',
  `IN_DIAG1` varchar(10) DEFAULT NULL COMMENT 'Diagnostico de egreso, tabla Egresos',
  `IN_DIAG2` varchar(10) DEFAULT NULL COMMENT 'Diagnostico de egreso, tabla Egresos',
  `IN_FECEGR` date DEFAULT NULL COMMENT 'Fecha de egreso',
  `IN_HOREGR` time DEFAULT NULL COMMENT 'Hora de egreso',
  `IN_TIPEGR` varchar(2) DEFAULT NULL COMMENT 'Condicion de egreso',
  `IN_COMPLEJIDAD` int(2) DEFAULT NULL COMMENT 'Complejidad del paciente, tabla Tipo_hab',
  `IN_DIAGDIF` varchar(55) DEFAULT NULL COMMENT 'Diagnostico diferenciales, separado por barras /',
  `IN_APFA` varchar(35) DEFAULT NULL COMMENT 'Apellido familiar',
  `IN_TELFA` varchar(14) DEFAULT NULL COMMENT 'Telefono familiar',
  `IN_MEDALT` varchar(6) DEFAULT NULL COMMENT 'Medico responsable del alta',
  `IN_NUMCAM` int(4) DEFAULT NULL COMMENT 'Numero de cama, puede saberse la complejidad de la misma',
  `IN_CLIQUI` varchar(1) DEFAULT NULL COMMENT 'Indica si es Internacion Clinica o Quirurgica',
  `IN_MONTO` varchar(10) DEFAULT NULL COMMENT 'Monto facturado en toda la internacion',
  `IN_UDORIG` varchar(3) DEFAULT NULL COMMENT 'Unidad de Diag Origen, tabla ',
  `IN_REVIS` enum('D','N') DEFAULT NULL COMMENT 'Revision si son decima o novena',
  `IN_AREAIN` varchar(3) DEFAULT NULL COMMENT 'Es el sector de ingreso',
  `IN_MOTNFC` text COMMENT 'Motivo por el cual no se facturo',
  `IN_TDOCMA` varchar(3) DEFAULT NULL COMMENT 'Tipo de documento de la madre',
  `IN_NDOCMA` varchar(12) DEFAULT NULL COMMENT 'Nro documento de la madre',
  `IN_ASOCIAD` varchar(1) DEFAULT NULL COMMENT 'Asociado tabla Asociado',
  `IN_OTRCIRC` varchar(55) DEFAULT NULL COMMENT 'Otra circunst de internacion prolongada',
  `IN_COMPRO` varchar(10) DEFAULT NULL COMMENT 'Como se produjo, tabla Diagno',
  `IN_FECALTA` date DEFAULT NULL COMMENT 'Fecha de alta medica',
  `IN_FOTDOCU` enum('S','N','X') DEFAULT NULL COMMENT 'Indica si entrego fotoc del dni o no corresponde',
  `IN_FOTCARN` enum('S','N','X') DEFAULT NULL COMMENT 'Indica si entrego fotoc del carnet o no corresponde',
  `IN_FOTULRE` enum('S','N','X') DEFAULT NULL COMMENT 'Indica si entrego fotoc del recibo de sueldo o no corresponde',
  `IN_PATRES` tinyint(1) DEFAULT NULL,
  `IN_ANEXFAM` varchar(1) DEFAULT NULL COMMENT 'Indica si el anexo esta firmado por el familiar',
  `IN_ANEXMED` varchar(1) DEFAULT NULL COMMENT 'Indica si el anexo esta firmado por el medico',
  `IN_ORDENIN` tinyint(1) DEFAULT NULL COMMENT 'Indica si trajo la orden de internacion',
  `IN_EMPEMPLE` varchar(40) DEFAULT NULL COMMENT 'Nombre de la empresa empleadora',
  `IN_EMPDIR` varchar(35) DEFAULT NULL COMMENT 'Domicilio de la empresa empleadora',
  `IN_CUITEMP` varchar(13) DEFAULT NULL COMMENT 'CUIT de la empresa empleadora',
  `IN_OBSING` text,
  `IN_OBSEG` text,
  `IN_NIVEL` varchar(1) DEFAULT NULL,
  `IN_VENNIV` date DEFAULT NULL,
  `IN_MOTNET` varchar(1) DEFAULT NULL,
  `IN_OBSNENT` text,
  `IN_INFOSOC` text,
  `IN_HORALTA` time DEFAULT NULL,
  PRIMARY KEY (`IN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Guarda los datos principales de la internación';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.kairos_basate
CREATE TABLE IF NOT EXISTS `kairos_basate` (
  `codigo` varchar(4) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Acciones Terapéuticas';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.kairos_basatp
CREATE TABLE IF NOT EXISTS `kairos_basatp` (
  `codigo_accion` varchar(4) DEFAULT NULL,
  `codigo_producto` varchar(6) DEFAULT NULL,
  `presentacion` varchar(6) DEFAULT NULL,
  `via_administracion` varchar(6) DEFAULT NULL,
  `medio_presentacion` varchar(6) DEFAULT NULL,
  `importancia` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Enlace de Acciones Terapéuticas con Productos';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.kairos_basdp
CREATE TABLE IF NOT EXISTS `kairos_basdp` (
  `codigo_droga` varchar(4) DEFAULT NULL,
  `codigo_producto` varchar(6) DEFAULT NULL,
  `presentacion` varchar(6) DEFAULT NULL,
  `via_administracion` varchar(6) DEFAULT NULL,
  `medio_presentacion` varchar(6) DEFAULT NULL,
  `importancia` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Enlace de Drogas con Productos';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.kairos_basdro
CREATE TABLE IF NOT EXISTS `kairos_basdro` (
  `codigo` varchar(4) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `estado` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Drogas';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.kairos_basiom
CREATE TABLE IF NOT EXISTS `kairos_basiom` (
  `codigo_producto` varchar(6) DEFAULT NULL,
  `codigo_presentacion` varchar(2) DEFAULT NULL,
  `monto` varchar(13) DEFAULT NULL,
  `fecha_vigencia` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Monto Fijo IOMA';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.kairos_baslab
CREATE TABLE IF NOT EXISTS `kairos_baslab` (
  `codigo` varchar(5) DEFAULT NULL,
  `descripcion` varchar(15) DEFAULT NULL,
  `estado` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.kairos_baspam
CREATE TABLE IF NOT EXISTS `kairos_baspam` (
  `codigo_producto` varchar(6) DEFAULT NULL,
  `codigo_presentacion` varchar(2) DEFAULT NULL,
  `monto` varchar(13) DEFAULT NULL,
  `fecha_vigencia` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Monto Fijo PAMI';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.kairos_basprc
CREATE TABLE IF NOT EXISTS `kairos_basprc` (
  `codigo_producto` varchar(6) DEFAULT NULL,
  `codigo_presentacion` varchar(2) DEFAULT NULL,
  `precio_publico` varchar(13) DEFAULT NULL,
  `fecha_vigencia` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Precios';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.kairos_baspre
CREATE TABLE IF NOT EXISTS `kairos_baspre` (
  `codigo_producto` varchar(6) NOT NULL,
  `codigo_presentacion` varchar(2) NOT NULL,
  `descripcion` varchar(60) DEFAULT NULL,
  `iva` varchar(1) DEFAULT NULL,
  `pami` varchar(1) DEFAULT NULL,
  `codigo_troquel` varchar(8) DEFAULT NULL,
  `ioma` varchar(1) DEFAULT NULL,
  `ioma_normatizado` varchar(1) DEFAULT NULL,
  `codigo_barras` varchar(13) DEFAULT NULL,
  `estado` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Presentaciones';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.kairos_baspro
CREATE TABLE IF NOT EXISTS `kairos_baspro` (
  `codigo` varchar(6) NOT NULL,
  `descripcion` varchar(40) DEFAULT NULL,
  `laboratorio` varchar(5) DEFAULT NULL,
  `origen` varchar(1) DEFAULT NULL,
  `psicofarmaco` varchar(1) DEFAULT NULL,
  `codigo_venta` varchar(1) DEFAULT NULL,
  `estupefaciente` varchar(1) DEFAULT NULL,
  `estado` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Productos';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.kairos_bastip
CREATE TABLE IF NOT EXISTS `kairos_bastip` (
  `codigo_producto` varchar(6) DEFAULT NULL,
  `codigo_presentacion` varchar(2) DEFAULT NULL,
  `especificacion` varchar(6) DEFAULT NULL,
  `via` varchar(6) DEFAULT NULL,
  `forma` varchar(6) DEFAULT NULL,
  `concentracion` varchar(11) DEFAULT NULL,
  `unid_concentracion` varchar(6) DEFAULT NULL,
  `comentario_concentracion` varchar(10) DEFAULT NULL,
  `cantidad_envase` varchar(4) DEFAULT NULL,
  `cantidad_unidad` varchar(7) DEFAULT NULL,
  `unidad_cantidad` varchar(6) DEFAULT NULL,
  `dosis` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tipificación productos';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.labo
CREATE TABLE IF NOT EXISTS `labo` (
  `LA_CODIGO` varchar(5) NOT NULL,
  `LA_NOMBRE` varchar(40) NOT NULL,
  `LA_TIPO` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`LA_CODIGO`),
  KEY `la_cod` (`LA_CODIGO`),
  KEY `la_nom` (`LA_NOMBRE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.legajos
CREATE TABLE IF NOT EXISTS `legajos` (
  `LE_NUMLEGA` varchar(6) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `LE_APENOM` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `LE_NOMAPE` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `LE_FECNAC` date DEFAULT NULL,
  `LE_SEXO` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `LE_LOCNAC` char(3) CHARACTER SET utf8 DEFAULT NULL,
  `LE_LOCDESC` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `LE_PROVNAC` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `LE_NACION` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `LE_TIPDOC` char(3) CHARACTER SET utf8 DEFAULT NULL,
  `LE_NUMDOC` varchar(12) CHARACTER SET utf8 DEFAULT NULL,
  `LE_CUIL` varchar(13) CHARACTER SET utf8 DEFAULT NULL,
  `LE_CTACRED` varchar(7) CHARACTER SET utf8 DEFAULT NULL,
  `LE_TIPOCTA` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `LE_CTAPATA` varchar(7) CHARACTER SET utf8 DEFAULT NULL,
  `LE_ESTUD` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `LE_PROFES` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `LE_PROFES_A` varchar(4) CHARACTER SET utf8 DEFAULT NULL,
  `LE_MATRIC` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `LE_TITUL1` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `LE_MATRI1` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `LE_TITUL2` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `LE_MATRI2` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `LE_TITUL3` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `LE_MATRI3` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `LE_TITUL4` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `LE_MATRI4` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `LE_ESTCIV` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `LE_APECONY` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `LE_DIREC` varchar(35) CHARACTER SET utf8 DEFAULT NULL,
  `LE_TELEF` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `LE_CELULAR` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `LE_ACTIVO` enum('F','T') CHARACTER SET utf8 DEFAULT NULL,
  `LE_BAJREEM` enum('F','T') CHARACTER SET utf8 DEFAULT NULL,
  `LE_OTROAGE` enum('F','T') CHARACTER SET utf8 DEFAULT NULL,
  `LE_AGEMUNI` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `salt` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `permisos` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `grupo` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `auth_key` varchar(32) NOT NULL,
  PRIMARY KEY (`LE_NUMLEGA`),
  KEY `password` (`password`),
  KEY `LE_NUMLEGA` (`LE_NUMLEGA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.locali
CREATE TABLE IF NOT EXISTS `locali` (
  `LO_COD` varchar(3) CHARACTER SET utf8 DEFAULT NULL,
  `LO_DETALLE` varchar(35) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.medic
CREATE TABLE IF NOT EXISTS `medic` (
  `ME_CODIGO` varchar(4) NOT NULL COMMENT 'Código del medicamento',
  `ME_NOMCOM` varchar(40) NOT NULL COMMENT 'Nombre comercial del medicamento',
  `ME_CODKAI` varchar(8) NOT NULL COMMENT 'Código según Kairos',
  `ME_CODRAF` varchar(9) NOT NULL COMMENT 'Código según Rafam',
  `ME_KAIBAR` varchar(13) NOT NULL COMMENT 'Código de barras según Kairos',
  `ME_KAITRO` varchar(8) NOT NULL COMMENT 'Código de troquel según Kairos',
  `ME_CODMON` varchar(4) NOT NULL COMMENT 'Código de la monodroga',
  `ME_CODLAB` varchar(4) NOT NULL COMMENT 'Código del proveedor',
  `ME_PRES` text NOT NULL COMMENT 'Texto que indica la presentación',
  `ME_FRACCQ` varchar(1) NOT NULL COMMENT 'Indica si se fracciona al enviar a Quirófano',
  `ME_VALVEN` double NOT NULL COMMENT 'Valor de venta',
  `ME_ULTCOM` date NOT NULL COMMENT 'Fecha de última compra',
  `ME_VALCOM` decimal(12,2) NOT NULL COMMENT 'Valor de la última compra',
  `ME_ULTSAL` date NOT NULL COMMENT 'Fecha de última salida',
  `ME_STMIN` double NOT NULL COMMENT 'Stock mínimo',
  `ME_STMAX` double NOT NULL COMMENT 'Stock máximo',
  `ME_RUBRO` varchar(2) NOT NULL COMMENT 'Rubro de facturación',
  `ME_UNIENV` decimal(12,2) NOT NULL COMMENT 'Unidades por envase',
  `ME_DEPOSITO` varchar(2) NOT NULL COMMENT 'Código del subdepósito de farmacia',
  PRIMARY KEY (`ME_CODIGO`),
  KEY `FK_medic_artic_gral` (`ME_CODMON`),
  KEY `FK_medic_labo` (`ME_CODLAB`),
  KEY `FK_medic_deposito` (`ME_DEPOSITO`),
  CONSTRAINT `FK_medic_artic_gral` FOREIGN KEY (`ME_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_medic_deposito` FOREIGN KEY (`ME_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_medic_labo` FOREIGN KEY (`ME_CODLAB`) REFERENCES `labo` (`LA_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.mot_perd
CREATE TABLE IF NOT EXISTS `mot_perd` (
  `MP_COD` varchar(4) CHARACTER SET utf8 NOT NULL COMMENT 'Codigo',
  `MP_NOM` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT 'Descripción',
  PRIMARY KEY (`MP_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.movsto
CREATE TABLE IF NOT EXISTS `movsto` (
  `MS_COD` varchar(1) NOT NULL,
  `MS_NOM` varchar(25) NOT NULL,
  `MS_SIGNO` tinyint(1) NOT NULL,
  `MS_VALIDO` tinyint(1) NOT NULL,
  PRIMARY KEY (`MS_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tipo de movimiento de stock de Farmacia\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.movstosa
CREATE TABLE IF NOT EXISTS `movstosa` (
  `MS_COD` varchar(1) NOT NULL,
  `MS_NOM` varchar(35) NOT NULL,
  `MS_SIGNO` tinyint(1) NOT NULL,
  `MS_VALIDO` tinyint(1) NOT NULL,
  `MS_ORDEN` tinyint(2) NOT NULL,
  PRIMARY KEY (`MS_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tipo de movimiento de stock de Servicios\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.movst_qui
CREATE TABLE IF NOT EXISTS `movst_qui` (
  ` MS_COD` varchar(1) NOT NULL,
  ` MS_NOM` varchar(25) DEFAULT NULL,
  ` MS_SIGNO` tinyint(4) DEFAULT NULL,
  ` MS_VALIDO` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (` MS_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Es el tipo de movimiento de stock de Quirófano\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.mov_dia
CREATE TABLE IF NOT EXISTS `mov_dia` (
  `MD_FECHA` date NOT NULL COMMENT 'Fecha del movimiento',
  `MD_CODMOV` varchar(1) NOT NULL COMMENT 'Código del movimiento (Relacionado con MovSto)',
  `MD_CANT` decimal(12,2) DEFAULT NULL COMMENT 'Cantidad',
  `MD_FECVEN` date NOT NULL COMMENT 'Fecha de vencimiento',
  `MD_CODMON` varchar(4) NOT NULL COMMENT 'Código de la monodroga',
  `MD_DEPOSITO` varchar(2) NOT NULL COMMENT 'Código del subdepósito de farmacia',
  PRIMARY KEY (`MD_CODMON`,`MD_DEPOSITO`,`MD_FECVEN`,`MD_FECHA`,`MD_CODMOV`),
  KEY `FK_mov_dia_deposito` (`MD_DEPOSITO`),
  KEY `FK_mov_dia_movsto` (`MD_CODMOV`),
  CONSTRAINT `FK_mov_dia_artic_gral` FOREIGN KEY (`MD_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_mov_dia_deposito` FOREIGN KEY (`MD_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_mov_dia_movsto` FOREIGN KEY (`MD_CODMOV`) REFERENCES `movsto` (`MS_COD`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Es el archivo de movimientos diario de la Farmacia, intentado resumir las entradas y salidas del día';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.mov_quiro
CREATE TABLE IF NOT EXISTS `mov_quiro` (
  `MO_IDFOJA` bigint(20) DEFAULT NULL COMMENT 'Foja',
  `MO_FECHA` date NOT NULL COMMENT 'Fecha',
  `MO_HORA` time NOT NULL COMMENT 'Hora',
  `MO_DEPOSITO` varchar(2) NOT NULL COMMENT 'Depósito',
  `MO_CODART` varchar(4) NOT NULL COMMENT 'Artículo',
  `MO_SECTOR` varchar(3) NOT NULL COMMENT 'Servicio ',
  `MO_CANTIDA` decimal(15,3) DEFAULT NULL COMMENT 'Cantidad solicitada',
  `MO_DESCART` decimal(15,3) DEFAULT NULL COMMENT 'Cantidad descartada',
  `MO_TIPMOV` varchar(1) NOT NULL COMMENT 'Tipo Movimiento',
  PRIMARY KEY (`MO_FECHA`,`MO_DEPOSITO`,`MO_CODART`,`MO_TIPMOV`),
  KEY `FK_mov_quiro_artic_gral` (`MO_CODART`),
  KEY `FK_mov_quiro_movst_qui` (`MO_TIPMOV`),
  KEY `FK_mov_quiro_servicio` (`MO_SECTOR`),
  CONSTRAINT `FK_mov_quiro_artic_gral` FOREIGN KEY (`MO_CODART`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_mov_quiro_movst_qui` FOREIGN KEY (`MO_TIPMOV`) REFERENCES `movst_qui` (` MS_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_mov_quiro_servicio` FOREIGN KEY (`MO_SECTOR`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Movimientos diarios de quirófano';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.mov_sala
CREATE TABLE IF NOT EXISTS `mov_sala` (
  `MO_HISCLI` int(11) DEFAULT NULL COMMENT 'Historia Clínica',
  `MO_CODSERV` varchar(3) NOT NULL COMMENT 'Sercvicio',
  `MO_FECHA` date NOT NULL COMMENT 'Fecha',
  `MO_HORA` time DEFAULT NULL COMMENT 'Hora',
  `MO_DEPOSITO` varchar(2) NOT NULL COMMENT 'Depósito',
  `MO_CODMON` varchar(4) NOT NULL COMMENT 'Medicamento',
  `MO_CANT` decimal(15,3) DEFAULT NULL COMMENT 'Cantidad',
  `MO_TIPMOV` varchar(1) NOT NULL COMMENT 'Tipo Movimiento',
  `MO_ORDEN` varchar(2) DEFAULT NULL COMMENT 'Ordenamiento para la ficha de ordenamiento',
  `MO_SUPOPE` varchar(6) DEFAULT NULL COMMENT 'Dependiendo del tipo de mov Supervisor o Personal de enfermería',
  PRIMARY KEY (`MO_CODSERV`,`MO_FECHA`,`MO_CODMON`,`MO_TIPMOV`,`MO_DEPOSITO`),
  KEY `FK_MOV_SALA_paciente` (`MO_HISCLI`),
  KEY `FK_MOV_SALA_deposito` (`MO_DEPOSITO`),
  KEY `FK_MOV_SALA_artic_gral` (`MO_CODMON`),
  KEY `FK_mov_sala_movstosa` (`MO_TIPMOV`),
  CONSTRAINT `FK_MOV_SALA_artic_gral` FOREIGN KEY (`MO_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_MOV_SALA_deposito` FOREIGN KEY (`MO_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_MOV_SALA_paciente` FOREIGN KEY (`MO_HISCLI`) REFERENCES `paciente` (`PA_HISCLI`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_MOV_SALA_servicio` FOREIGN KEY (`MO_CODSERV`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_mov_sala_movstosa` FOREIGN KEY (`MO_TIPMOV`) REFERENCES `movstosa` (`MS_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Son los movimientos diarios de cada servicios, se hace una actualización directa, cuando Farmacia registra una entrega\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.nacional
CREATE TABLE IF NOT EXISTS `nacional` (
  `NA_COD` char(2) CHARACTER SET utf8 NOT NULL,
  `NA_DETALLE` varchar(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`NA_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.nivel_se
CREATE TABLE IF NOT EXISTS `nivel_se` (
  `NI_RENG1` text,
  `NI_COD` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.nivinst
CREATE TABLE IF NOT EXISTS `nivinst` (
  `NI_CODIGO` int(2) NOT NULL AUTO_INCREMENT,
  `NI_DETALLE` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`NI_CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.obrasoci
CREATE TABLE IF NOT EXISTS `obrasoci` (
  `OB_COD` varchar(4) NOT NULL,
  `OB_NOM` varchar(50) DEFAULT NULL,
  `OB_NOMCOMP` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `OB_SINON` varchar(30) DEFAULT NULL,
  `OB_DIRECC` varchar(40) DEFAULT NULL,
  `OB_CUIT` varchar(15) DEFAULT NULL,
  `OB_DGI` varchar(3) DEFAULT NULL,
  `OB_SUBCOD` varchar(6) DEFAULT NULL,
  `OB_COSEG` varchar(1) DEFAULT NULL,
  `OB_ENTE` varchar(3) DEFAULT NULL,
  `OB_CONVEN` varchar(1) DEFAULT NULL,
  `OB_CJTONOM` varchar(40) DEFAULT NULL,
  `OB_RUBNFG` varchar(40) DEFAULT NULL,
  `OB_RUBNFH` varchar(40) DEFAULT NULL,
  `OB_CTACTE` varchar(6) DEFAULT NULL,
  `OB_DIAPRES` int(11) DEFAULT NULL,
  `OB_FECPRES` int(2) DEFAULT NULL,
  `OB_PORGAS` double DEFAULT NULL,
  `OB_PORHON` double DEFAULT NULL,
  `OB_USGOPER` double DEFAULT NULL,
  `OB_USOTGAS` double DEFAULT NULL,
  `OB_USGRADI` double DEFAULT NULL,
  `OB_USGCLIN` double DEFAULT NULL,
  `OB_USPENSI` double DEFAULT NULL,
  `OB_USGALEN` double DEFAULT NULL,
  `OB_UGASBIO` double DEFAULT NULL,
  `OB_REQUISI` varchar(2) DEFAULT NULL,
  `OB_FACTDIR` varchar(1) DEFAULT NULL,
  `OB_RUBHONO` varchar(50) DEFAULT NULL,
  `OB_FECHA` date DEFAULT NULL,
  `OB_USGALQU` double DEFAULT NULL,
  `OB_USGAPA` double DEFAULT NULL,
  `OB_ARTSEG` enum('F','T') DEFAULT NULL,
  `OB_CAPITA` enum('F','T') DEFAULT NULL,
  `OB_INSTSMU` text,
  `OB_INSTCEX` text,
  `OB_INSTDXI` text,
  `OB_INSTLAB` text,
  `OB_INSTINT` text,
  `OB_ACTIVA` enum('F','T') DEFAULT NULL,
  `OB_TEL` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`OB_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.ocupacion
CREATE TABLE IF NOT EXISTS `ocupacion` (
  `OC_COD` char(2) DEFAULT NULL,
  `OC_DESCRI` varchar(50) NOT NULL,
  KEY `OC_COD` (`OC_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.ordenes_compra
CREATE TABLE IF NOT EXISTS `ordenes_compra` (
  `OC_NRO` varchar(10) NOT NULL COMMENT 'Número',
  `OC_PROVEED` varchar(5) DEFAULT NULL COMMENT 'Proveedor',
  `OC_FECHA` date DEFAULT NULL COMMENT 'Fecha',
  `OC_FINALIZADA` tinyint(1) DEFAULT NULL COMMENT 'Indica si fue entregada totalmente o no',
  `OC_PEDADQ` int(12) DEFAULT NULL COMMENT 'Número Pedido',
  PRIMARY KEY (`OC_NRO`),
  KEY `FK_ordenes_compra_proveedores` (`OC_PROVEED`),
  KEY `FK_ordenes_compra_ped_adq` (`OC_PEDADQ`),
  CONSTRAINT `FK_ordenes_compra_ped_adq` FOREIGN KEY (`OC_PEDADQ`) REFERENCES `ped_adq` (`PE_NUM`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_ordenes_compra_proveedores` FOREIGN KEY (`OC_PROVEED`) REFERENCES `proveedores` (`PR_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Replica Ordenes de Compra de Rafam';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.paciente
CREATE TABLE IF NOT EXISTS `paciente` (
  `PA_CODPAR` varchar(3) DEFAULT NULL,
  `PA_APENOM` varchar(50) DEFAULT NULL,
  `PA_HISCLI` int(11) NOT NULL AUTO_INCREMENT,
  `PA_TIPDOC` varchar(3) DEFAULT NULL,
  `PA_NUMDOC` varchar(14) DEFAULT NULL,
  `PA_FECNAC` date DEFAULT NULL,
  `PA_NACION` varchar(2) DEFAULT NULL,
  `PA_SEXO` varchar(1) DEFAULT NULL,
  `PA_DIREC` varchar(100) DEFAULT NULL,
  `PA_CODCALL` varchar(5) DEFAULT NULL,
  `PA_NROCALL` varchar(6) DEFAULT NULL,
  `PA_BARRIO` int(3) DEFAULT NULL,
  `PA_CUERPO` varchar(20) DEFAULT NULL,
  `PA_PISO` varchar(5) DEFAULT NULL,
  `PA_TIPOVIV` varchar(1) DEFAULT NULL,
  `PA_DPTO` varchar(10) DEFAULT NULL,
  `PA_CODLOC` varchar(3) DEFAULT NULL,
  `PA_CODPRO` varchar(2) DEFAULT NULL,
  `PA_TELEF` varchar(13) DEFAULT NULL,
  `PA_OBSERV` text,
  `PA_NIVEL` varchar(1) DEFAULT NULL,
  `PA_VENNIV` date DEFAULT NULL,
  `PA_CODOS` varchar(4) DEFAULT NULL,
  `PA_NROAFI` varchar(15) DEFAULT NULL,
  `PA_ADEU` varchar(7) DEFAULT NULL,
  `PA_ENTDE` varchar(3) DEFAULT NULL,
  `PA_LOCNAC` varchar(3) DEFAULT NULL,
  `PA_APEMA` varchar(35) DEFAULT NULL,
  `PA_UBIC` varchar(8) DEFAULT NULL,
  `PA_USANIT` varchar(3) DEFAULT NULL,
  `PA_MEDDER` varchar(6) DEFAULT NULL,
  `PA_APEMEDD` varchar(30) DEFAULT NULL,
  `PA_CODPAIS` varchar(3) DEFAULT NULL,
  `PA_ASOCIAD` varchar(1) DEFAULT NULL,
  `PA_NIVINST` varchar(2) DEFAULT NULL,
  `PA_SITLABO` varchar(1) DEFAULT NULL,
  `PA_OCUPAC` varchar(50) DEFAULT NULL,
  `PA_APEFA` varchar(35) DEFAULT NULL,
  `PA_TELFA` varchar(14) DEFAULT NULL,
  `PA_FOTDOCU` varchar(1) DEFAULT NULL,
  `PA_FALLEC` enum('F','T') DEFAULT NULL,
  `PA_NOMELEG` varchar(35) DEFAULT NULL,
  `PA_EMPEMPL` varchar(40) DEFAULT NULL,
  `PA_EMPDIR` varchar(35) DEFAULT NULL,
  `PA_CUITEMP` varchar(13) DEFAULT NULL,
  `PA_EMAIL` varchar(75) CHARACTER SET utf8 DEFAULT NULL,
  `PA_REGISTRADO` enum('F','T') CHARACTER SET utf8 NOT NULL DEFAULT 'F',
  `PA_ART` varchar(4) CHARACTER SET utf8 NOT NULL DEFAULT '''''',
  `PA_USANITSU` varchar(3) NOT NULL,
  `PA_NOMBRE` varchar(35) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `PA_APELLIDO` varchar(35) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `PA_ORIGEN` varchar(3) DEFAULT NULL,
  `PA_PARTIDONAC` varchar(3) DEFAULT NULL,
  `PA_PROVNAC` varchar(2) DEFAULT NULL,
  `PA_PAISNAC` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`PA_HISCLI`),
  KEY `PA_HISCLI` (`PA_HISCLI`),
  KEY `PA_HISCLI_2` (`PA_HISCLI`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.pac_etiq
CREATE TABLE IF NOT EXISTS `pac_etiq` (
  `et_hiscli` int(11) NOT NULL,
  `et_etiq` varchar(5) NOT NULL,
  `et_feccrea` date NOT NULL,
  `et_horcrea` time NOT NULL,
  `et_fecbaj` date NOT NULL,
  `et_horbaj` time NOT NULL,
  `et_comment` text NOT NULL,
  `et_memat` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.paises
CREATE TABLE IF NOT EXISTS `paises` (
  `PA_COD` varchar(3) NOT NULL,
  `PA_DETALLE` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`PA_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.partido
CREATE TABLE IF NOT EXISTS `partido` (
  `PT_COD` varchar(3) NOT NULL,
  `PT_DETALLE` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`PT_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.pd_reng
CREATE TABLE IF NOT EXISTS `pd_reng` (
  `PR_NRODEVOL` int(12) NOT NULL,
  `PR_DEPOSITO` varchar(2) NOT NULL,
  `PR_CODART` varchar(4) NOT NULL,
  `PR_CANTID` decimal(9,2) DEFAULT NULL,
  `PR_FECVTO` date NOT NULL,
  PRIMARY KEY (`PR_NRODEVOL`,`PR_DEPOSITO`,`PR_CODART`,`PR_FECVTO`),
  KEY `FK_pd_reng_deposito` (`PR_DEPOSITO`),
  KEY `FK_pd_reng_artic_gral` (`PR_CODART`,`PR_DEPOSITO`),
  CONSTRAINT `FK_pd_reng_artic_gral` FOREIGN KEY (`PR_CODART`, `PR_DEPOSITO`) REFERENCES `artic_gral` (`AG_CODIGO`, `AG_DEPOSITO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_pd_reng_deposito` FOREIGN KEY (`PR_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_pd_reng_plan_dev` FOREIGN KEY (`PR_NRODEVOL`) REFERENCES `plan_dev` (`DE_NRODEVOL`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Devolución a granel renglones';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.pead_mov
CREATE TABLE IF NOT EXISTS `pead_mov` (
  `PE_NUM` int(12) NOT NULL COMMENT 'Número del pedido',
  `PE_NRORENG` smallint(6) NOT NULL COMMENT 'Número de renglón',
  `PE_CODART` varchar(4) DEFAULT NULL COMMENT 'Código del artículo ',
  `PE_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Código del depósito',
  `PE_CLASE` varchar(2) DEFAULT NULL COMMENT 'Clase del artículo',
  `PE_CANT` int(5) DEFAULT NULL COMMENT 'Cantidad pendiente de entrega',
  `PE_PRECIO` decimal(19,2) DEFAULT NULL COMMENT 'Precio unitario',
  `PE_REDONDEO` int(5) DEFAULT NULL COMMENT 'Redondeo',
  ` PE_CANTPED` int(5) DEFAULT NULL COMMENT 'Cantidad pedida definitiva',
  ` PE_SUGERIDO` int(5) DEFAULT NULL COMMENT 'Cantidad sugerida',
  ` PE_EXISTENCIA` int(5) DEFAULT NULL COMMENT 'Existencia al momento de generar el pedido',
  ` PE_PENDIENTE` int(5) DEFAULT NULL COMMENT 'Cantidad pendiente al momento de generar el pedido',
  ` PE_CONSUMO` decimal(10,3) DEFAULT NULL COMMENT 'Consumo promedio que se utilizo al generar el pedido',
  PRIMARY KEY (`PE_NUM`,`PE_NRORENG`),
  KEY `FK_pead_mov_deposito` (`PE_DEPOSITO`),
  KEY `FK_pead_mov_artic_gral` (`PE_CODART`,`PE_DEPOSITO`),
  KEY `FK_pead_mov_clases` (`PE_CLASE`),
  CONSTRAINT `FK_pead_mov_artic_gral` FOREIGN KEY (`PE_CODART`, `PE_DEPOSITO`) REFERENCES `artic_gral` (`AG_CODIGO`, `AG_DEPOSITO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_pead_mov_clases` FOREIGN KEY (`PE_CLASE`) REFERENCES `clases` (`CL_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_pead_mov_deposito` FOREIGN KEY (`PE_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_pead_mov_ped_adq` FOREIGN KEY (`PE_NUM`) REFERENCES `ped_adq` (`PE_NUM`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Renglones Pedido de Adquisición';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.pedentre
CREATE TABLE IF NOT EXISTS `pedentre` (
  `PE_NROPED` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Número de pedido',
  `PE_FECHA` date NOT NULL COMMENT 'Fecha del pedido',
  `PE_HORA` time DEFAULT NULL COMMENT 'Hora del pedido',
  `PE_SERSOL` varchar(3) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Servicio solicitante',
  `PE_DEPOSITO` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `PE_REFERENCIA` text CHARACTER SET utf8,
  `PE_CLASE` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `PE_SUPERV` varchar(6) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Personal de Enfermería',
  `PE_PROCESADO` enum('F','T') CHARACTER SET utf8 NOT NULL DEFAULT 'F' COMMENT 'Indica si fue procesado o no',
  PRIMARY KEY (`PE_NROPED`),
  KEY `FK_pedentre_deposito` (`PE_DEPOSITO`),
  CONSTRAINT `FK_pedentre_deposito` FOREIGN KEY (`PE_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.ped_adq
CREATE TABLE IF NOT EXISTS `ped_adq` (
  `PE_NUM` int(12) NOT NULL AUTO_INCREMENT COMMENT 'Número del pedido',
  `PE_FECHA` date NOT NULL COMMENT 'Fecha del pedido',
  `PE_HORA` time NOT NULL COMMENT 'Hora del pedido',
  `PE_COSTO` decimal(19,2) NOT NULL COMMENT 'Costo total del pedido',
  `PE_REFERENCIA` text NOT NULL COMMENT 'Referencia libre',
  `PE_NROEXP` varchar(10) NOT NULL COMMENT 'Número de expediente, una vez caratulado',
  `PE_FECADJ` date NOT NULL COMMENT 'Fecha de adjudicación',
  `PE_DEPOSITO` varchar(2) NOT NULL COMMENT 'Código del depósito para el cual es el pedido',
  `PE_ARTDES` varchar(4) NOT NULL COMMENT 'Artículo desde el cual se generó el pedido',
  `PE_ARTHAS` varchar(4) NOT NULL COMMENT 'Artículo hasta el cual se generó el pedido',
  `PE_CLASES` varchar(120) NOT NULL COMMENT 'El conjunto de clases para las cuales se generó el pedido',
  `PE_TIPO` varchar(1) NOT NULL COMMENT 'Activos o todos',
  `PE_EXISACT` tinyint(1) NOT NULL COMMENT 'Si se considera la existencia actual para generar las cantidades sugeridas',
  `PE_PEDPEND` tinyint(1) NOT NULL COMMENT 'Si se considera las cantidades pendientes de entrega en pedidos previos',
  `PE_PONDHIS` decimal(6,2) NOT NULL COMMENT 'La ponderación o peso que se le da al consumo histórico',
  `PE_PONDPUN` decimal(6,2) NOT NULL COMMENT 'La ponderación o peso que se le da al consumo puntual',
  `PE_CLASABC` tinyint(1) NOT NULL COMMENT 'Si quiere filtrar por clase A,B ó C',
  `PE_DIASABC` int(3) NOT NULL COMMENT 'Cantidad de días para atrás que usa para calcular y clasificar a los artículos en A, B o C',
  `PE_DIASPREVIS` int(3) NOT NULL COMMENT 'Días de previsión para los cuales se quiere reponer (colchón)',
  `PE_DIASDEMORA` int(3) NOT NULL COMMENT 'Días que se estima que demorará el trámite para que ingresen los artículos comprados',
  PRIMARY KEY (`PE_NUM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Pedido de Adquisición de depósito';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.peen_mov
CREATE TABLE IF NOT EXISTS `peen_mov` (
  `PE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PE_NROPED` int(11) NOT NULL,
  `PE_NRORENG` smallint(6) NOT NULL,
  `PE_CODMON` varchar(4) CHARACTER SET utf8 NOT NULL COMMENT 'Monodroga',
  `PE_CANTPED` decimal(9,2) NOT NULL COMMENT 'Cantidad pedida',
  `PE_CANTENT` decimal(9,2) DEFAULT NULL COMMENT 'Cantidad entregada',
  PRIMARY KEY (`PE_ID`),
  KEY `FK_peen_mov_pedentre` (`PE_NROPED`),
  KEY `FK_peen_mov_artic_gral` (`PE_CODMON`),
  CONSTRAINT `FK_peen_mov_artic_gral` FOREIGN KEY (`PE_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_peen_mov_pedentre` FOREIGN KEY (`PE_NROPED`) REFERENCES `pedentre` (`PE_NROPED`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.perdfar
CREATE TABLE IF NOT EXISTS `perdfar` (
  `PF_NROREM` int(11) NOT NULL COMMENT 'Número de Remito Pérdida',
  `PF_DEPOSITO` varchar(2) NOT NULL COMMENT 'Subdepósito de farmacia',
  `PF_CODMON` varchar(4) NOT NULL COMMENT 'Código del medicamento ',
  `PF_CANTID` decimal(9,2) DEFAULT NULL COMMENT 'Cantidad',
  `PF_FECVTO` date DEFAULT NULL COMMENT 'Fecha de vencimiento del medicamento',
  PRIMARY KEY (`PF_NROREM`,`PF_DEPOSITO`,`PF_CODMON`),
  KEY `FK_perdfar_deposito` (`PF_DEPOSITO`),
  KEY `FK_perdfar_artic_gral` (`PF_CODMON`),
  CONSTRAINT `FK_perdfar_artic_gral` FOREIGN KEY (`PF_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_perdfar_deposito` FOREIGN KEY (`PF_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_perdfar_perdidas` FOREIGN KEY (`PF_NROREM`) REFERENCES `perdidas` (`PE_NROREM`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.perdidas
CREATE TABLE IF NOT EXISTS `perdidas` (
  `PE_NROREM` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Número Pérdida',
  `PE_FECHA` date DEFAULT NULL COMMENT 'Fecha',
  `PE_HORA` time DEFAULT NULL COMMENT 'Hora',
  `PE_MOTIVO` varchar(4) DEFAULT NULL COMMENT 'Motivo',
  `PE_CODOPE` varchar(6) DEFAULT NULL COMMENT 'Personal de Farmacia',
  `PE_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Depósito',
  PRIMARY KEY (`PE_NROREM`),
  KEY `FK_perdidas_mot_perd` (`PE_MOTIVO`),
  KEY `FK_perdidas_deposito` (`PE_DEPOSITO`),
  KEY `FK_perdidas_legajos` (`PE_CODOPE`),
  CONSTRAINT `FK_perdidas_deposito` FOREIGN KEY (`PE_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_perdidas_legajos` FOREIGN KEY (`PE_CODOPE`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_perdidas_mot_perd` FOREIGN KEY (`PE_MOTIVO`) REFERENCES `mot_perd` (`MP_COD`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Perdidas\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.pe_reng
CREATE TABLE IF NOT EXISTS `pe_reng` (
  `PR_NROREM` int(12) NOT NULL COMMENT 'Número de remito',
  `PR_CODART` varchar(4) NOT NULL COMMENT 'Código del medicamento',
  `PR_DEPOSITO` varchar(2) NOT NULL COMMENT 'Depósito',
  `PR_CANTID` decimal(10,2) NOT NULL COMMENT 'Cantidad entregada',
  `PR_FECVTO` date NOT NULL COMMENT 'Fecha de vencimiento',
  PRIMARY KEY (`PR_NROREM`,`PR_CODART`,`PR_FECVTO`),
  KEY `FK_pe_reng_dc_tab_vtos` (`PR_CODART`,`PR_FECVTO`,`PR_DEPOSITO`),
  KEY `FK_pe_reng_deposito` (`PR_DEPOSITO`),
  KEY `FK_pe_reng_artic_gral` (`PR_CODART`,`PR_DEPOSITO`),
  CONSTRAINT `FK_pe_reng_artic_gral` FOREIGN KEY (`PR_CODART`, `PR_DEPOSITO`) REFERENCES `artic_gral` (`AG_CODIGO`, `AG_DEPOSITO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_pe_reng_dc_tab_vtos` FOREIGN KEY (`PR_CODART`, `PR_FECVTO`, `PR_DEPOSITO`) REFERENCES `dc_tab_vtos` (`DT_CODART`, `DT_FECVEN`, `DT_DEPOSITO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_pe_reng_deposito` FOREIGN KEY (`PR_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_pe_reng_plan_ent` FOREIGN KEY (`PR_NROREM`) REFERENCES `plan_ent` (`PE_NROREM`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Entregas a granel a los servicios Renglones';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.planfar
CREATE TABLE IF NOT EXISTS `planfar` (
  `PF_NROREM` int(12) NOT NULL COMMENT 'Número de remito',
  `PF_CODMON` varchar(4) NOT NULL COMMENT 'Código del medicamento',
  `PF_DEPOSITO` varchar(2) NOT NULL COMMENT 'Depósito',
  `PF_CANTID` decimal(10,2) NOT NULL COMMENT 'Cantidad entregada',
  `PF_FECVTO` date NOT NULL COMMENT 'Fecha de vencimiento',
  PRIMARY KEY (`PF_NROREM`,`PF_CODMON`,`PF_FECVTO`),
  KEY `FK_planfar_tab_vtos` (`PF_CODMON`,`PF_FECVTO`,`PF_DEPOSITO`),
  KEY `FK_planfar_deposito` (`PF_DEPOSITO`),
  CONSTRAINT `FK_planfar_artic_gral` FOREIGN KEY (`PF_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_planfar_consme3` FOREIGN KEY (`PF_NROREM`) REFERENCES `consme3` (`CM_NROREM`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_planfar_deposito` FOREIGN KEY (`PF_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_planfar_tab_vtos` FOREIGN KEY (`PF_CODMON`, `PF_FECVTO`, `PF_DEPOSITO`) REFERENCES `tab_vtos` (`TV_CODART`, `TV_FECVEN`, `TV_DEPOSITO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Entregas a granel a los servicios Renglones';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.plan_dev
CREATE TABLE IF NOT EXISTS `plan_dev` (
  `DE_NRODEVOL` int(12) NOT NULL AUTO_INCREMENT COMMENT 'Número plan_devión',
  `DE_FECHA` date DEFAULT NULL COMMENT 'Fecha',
  `DE_HORA` time DEFAULT NULL COMMENT 'Hora',
  `DE_SERSOL` varchar(3) DEFAULT NULL COMMENT 'Servicio solicitante',
  `DE_CODOPE` varchar(6) DEFAULT NULL COMMENT 'Personal de Depósito',
  `DE_ENFERM` varchar(6) DEFAULT NULL COMMENT 'Personal de Enfermería',
  `DE_SOBRAN` tinyint(1) DEFAULT '0' COMMENT 'Indica si fue sobrante de Sala',
  `DE_NUMREMOR` int(12) DEFAULT NULL COMMENT 'Número del remito original',
  `DE_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Depósito',
  PRIMARY KEY (`DE_NRODEVOL`),
  KEY `FK_plan_dev_servicio` (`DE_SERSOL`),
  KEY `FK_plan_dev_legajos` (`DE_CODOPE`),
  KEY `FK_plan_dev_legajos_2` (`DE_ENFERM`),
  KEY `FK_plan_dev_deposito` (`DE_DEPOSITO`),
  KEY `FK_plan_dev_plan_ent` (`DE_NUMREMOR`),
  CONSTRAINT `FK_plan_dev_deposito` FOREIGN KEY (`DE_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_plan_dev_legajos` FOREIGN KEY (`DE_CODOPE`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_plan_dev_legajos_2` FOREIGN KEY (`DE_ENFERM`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_plan_dev_plan_ent` FOREIGN KEY (`DE_NUMREMOR`) REFERENCES `plan_ent` (`PE_NROREM`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_plan_dev_servicio` FOREIGN KEY (`DE_SERSOL`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='devolución a granel\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.plan_ent
CREATE TABLE IF NOT EXISTS `plan_ent` (
  `PE_NROREM` int(12) NOT NULL AUTO_INCREMENT COMMENT 'Número de remito',
  `PE_FECHA` date NOT NULL COMMENT 'Fecha',
  `PE_HORA` time NOT NULL COMMENT 'Hora',
  `PE_SERSOL` varchar(3) DEFAULT NULL COMMENT 'Servicio Solicitante',
  `PE_ENFERM` varchar(6) DEFAULT NULL COMMENT 'Personal de Enfermería',
  `PE_CODOPE` varchar(6) DEFAULT NULL COMMENT 'Personal de Depósito',
  `PE_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Subdepósito',
  `PE_PROCESADO` tinyint(1) NOT NULL COMMENT 'Procesado',
  `PE_NUMVALE` int(12) DEFAULT NULL COMMENT 'Número Vale de Pedido',
  PRIMARY KEY (`PE_NROREM`),
  KEY `FK_plan_ent_servicio` (`PE_SERSOL`),
  KEY `FK_plan_ent_legajos` (`PE_ENFERM`),
  KEY `FK_plan_ent_legajos_2` (`PE_CODOPE`),
  KEY `FK_plan_ent_deposito` (`PE_DEPOSITO`),
  KEY `FK_plan_ent_vale_des` (`PE_NUMVALE`),
  CONSTRAINT `FK_plan_ent_deposito` FOREIGN KEY (`PE_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_plan_ent_legajos` FOREIGN KEY (`PE_ENFERM`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_plan_ent_legajos_2` FOREIGN KEY (`PE_CODOPE`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_plan_ent_servicio` FOREIGN KEY (`PE_SERSOL`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_plan_ent_vale_des` FOREIGN KEY (`PE_NUMVALE`) REFERENCES `vale_des` (`VD_NUMVALE`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Es el encabezado de los remitos de entrega a servicios pedidos a granel\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.programa
CREATE TABLE IF NOT EXISTS `programa` (
  `PR_CODIGO` varchar(2) NOT NULL COMMENT 'Código',
  `PR_NOMBRE` varchar(30) DEFAULT NULL COMMENT 'Nombre',
  PRIMARY KEY (`PR_CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Catálogo de Programas de Asistencia Social (Locales, provinciales, etc) se usan en la entrega de medicamentos por ventanilla\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.prog_med
CREATE TABLE IF NOT EXISTS `prog_med` (
  `PM_CODPROG` varchar(2) NOT NULL COMMENT 'Programa',
  `PM_DEPOSITO` varchar(2) NOT NULL COMMENT 'Depósito',
  `PM_CODMON` varchar(4) NOT NULL COMMENT 'Medicamento',
  `PM_CANTENT` decimal(7,2) DEFAULT NULL COMMENT 'Cantidad a entregar',
  PRIMARY KEY (`PM_CODPROG`,`PM_DEPOSITO`,`PM_CODMON`),
  KEY `FK__deposito` (`PM_DEPOSITO`),
  KEY `FK__artic_gral` (`PM_CODMON`),
  CONSTRAINT `FK__artic_gral` FOREIGN KEY (`PM_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK__deposito` FOREIGN KEY (`PM_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_prog_med_programa` FOREIGN KEY (`PM_CODPROG`) REFERENCES `programa` (`PR_CODIGO`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Son los medicamentos que están incluídos en un programa de Asistencia Social\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.proveedores
CREATE TABLE IF NOT EXISTS `proveedores` (
  `PR_CODIGO` varchar(5) NOT NULL COMMENT 'Código Prveedor',
  `PR_RAZONSOC` varchar(70) NOT NULL COMMENT 'Razón Social',
  `PR_TITULAR` varchar(70) NOT NULL COMMENT 'Apellido y nombre del titular',
  `PR_CODRAFAM` varchar(5) NOT NULL COMMENT 'Código Proveedor Rafam',
  `PR_CUIT` varchar(13) NOT NULL COMMENT 'CUIT',
  `PR_DOMIC` varchar(55) NOT NULL COMMENT 'Domicilio',
  `PR_TELEF` varchar(35) DEFAULT NULL COMMENT 'Teléfono',
  `PR_EMAIL` varchar(60) DEFAULT NULL COMMENT 'Email',
  `PR_OBS` text COMMENT 'Observaciones',
  `PR_CONTACTO` varchar(45) DEFAULT NULL COMMENT 'Contacto',
  PRIMARY KEY (`PR_CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.provin
CREATE TABLE IF NOT EXISTS `provin` (
  `PR_COD` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `PR_DETALLE` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `PR_CODART` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `PR_MODIF` date DEFAULT NULL,
  `PR_CODOP` char(3) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.recetas_enc
CREATE TABLE IF NOT EXISTS `recetas_enc` (
  `RE_NRORECETA` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Número',
  `RE_HISCLI` int(11) NOT NULL COMMENT 'Historia Clínica del Paciente',
  `RE_FECINI` date NOT NULL COMMENT 'Fecha de inicio',
  `RE_FECFIN` date NOT NULL COMMENT 'Fecha de fin',
  `RE_MEDICO` varchar(6) NOT NULL COMMENT 'Matrícula del Médico',
  `RE_NOTA` text NOT NULL COMMENT 'Narrativa',
  PRIMARY KEY (`RE_NRORECETA`),
  KEY `FK_recetas_enc_paciente` (`RE_HISCLI`),
  KEY `FK_recetas_enc_legajos` (`RE_MEDICO`),
  CONSTRAINT `FK_recetas_enc_legajos` FOREIGN KEY (`RE_MEDICO`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_recetas_enc_paciente` FOREIGN KEY (`RE_HISCLI`) REFERENCES `paciente` (`PA_HISCLI`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Es el encabezado de las recetas electrónicas\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.recetas_reng
CREATE TABLE IF NOT EXISTS `recetas_reng` (
  `RE_NRORECETA` int(11) NOT NULL COMMENT 'Número Receta',
  `RE_DEPOSITO` varchar(2) NOT NULL COMMENT 'Depósito',
  `RE_CODMON` varchar(6) NOT NULL COMMENT 'Medicamento',
  `RE_CANTDIA` tinyint(4) DEFAULT NULL COMMENT 'Dosis diaria',
  `RE_INDICACION` text COMMENT 'Narrativa',
  `RE_DIAGNO` varchar(10) NOT NULL COMMENT 'Diagnóstico',
  PRIMARY KEY (`RE_NRORECETA`,`RE_DEPOSITO`,`RE_CODMON`),
  KEY `FK_recetas_reng_deposito` (`RE_DEPOSITO`),
  KEY `FK_recetas_reng_artic_gral` (`RE_CODMON`),
  CONSTRAINT `FK_recetas_reng_artic_gral` FOREIGN KEY (`RE_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_recetas_reng_deposito` FOREIGN KEY (`RE_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_recetas_reng_recetas_enc` FOREIGN KEY (`RE_NRORECETA`) REFERENCES `recetas_enc` (`RE_NRORECETA`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Renglones de receta electrónica';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.remito_adq
CREATE TABLE IF NOT EXISTS `remito_adq` (
  `RA_NUM` int(12) NOT NULL AUTO_INCREMENT COMMENT 'Número de remito (autonumérico)',
  `RA_FECHA` date DEFAULT NULL COMMENT 'Fecha de entrada de mercadería',
  `RA_HORA` time DEFAULT NULL COMMENT 'Hora de entrada de mercadería',
  `RA_CODOPE` varchar(6) DEFAULT NULL COMMENT 'Personal Depósito',
  `RA_CONCEP` text COMMENT 'Concepto',
  `RA_TIPMOV` varchar(1) DEFAULT NULL COMMENT 'Tipo de movimiento Compra o Donación (C o D) en caso de origen externo, NULL si proviene de Depósito Central',
  `RA_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Depósito',
  `RA_OCNRO` varchar(10) DEFAULT NULL COMMENT 'Número Orden Compra',
  PRIMARY KEY (`RA_NUM`),
  KEY `FK_remito_adq_legajos` (`RA_CODOPE`),
  KEY `FK_remito_adq_deposito` (`RA_DEPOSITO`),
  KEY `FK_remito_adq_ordenes_compra` (`RA_OCNRO`),
  CONSTRAINT `FK_remito_adq_deposito` FOREIGN KEY (`RA_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_remito_adq_legajos` FOREIGN KEY (`RA_CODOPE`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_remito_adq_ordenes_compra` FOREIGN KEY (`RA_OCNRO`) REFERENCES `ordenes_compra` (`OC_NRO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Remito Adquisicion';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.rem_mov
CREATE TABLE IF NOT EXISTS `rem_mov` (
  `RM_RENUM` int(11) NOT NULL COMMENT 'Número de remito',
  `RM_DEPOSITO` varchar(2) NOT NULL COMMENT 'Código del subdepósito de farmacia',
  `RM_NUMRENG` smallint(6) NOT NULL,
  `RM_CODMON` varchar(4) NOT NULL COMMENT 'Código de monodroga',
  `RM_PRECIO` decimal(12,3) NOT NULL COMMENT 'Precio de la compra',
  `RM_CANTID` decimal(10,2) NOT NULL COMMENT 'Cantidad entregada',
  `RM_FECVTO` date NOT NULL COMMENT 'Fecha de vencimiento',
  PRIMARY KEY (`RM_RENUM`,`RM_DEPOSITO`,`RM_CODMON`),
  KEY `FK_rem_mov_deposito` (`RM_DEPOSITO`),
  KEY `FK_rem_mov_artic_gral` (`RM_CODMON`),
  CONSTRAINT `FK_rem_mov_artic_gral` FOREIGN KEY (`RM_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`),
  CONSTRAINT `FK_rem_mov_deposito` FOREIGN KEY (`RM_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`),
  CONSTRAINT `FK_rem_mov_fa_remit` FOREIGN KEY (`RM_RENUM`) REFERENCES `fa_remit` (`RE_NUM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Renglones de Remitos de adquisición de farmacia fa_remit';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.reng_oc
CREATE TABLE IF NOT EXISTS `reng_oc` (
  `EN_NROOC` varchar(10) NOT NULL,
  `EN_ITEM` smallint(6) NOT NULL,
  `EN_CODART` varchar(4) DEFAULT NULL,
  `EN_DEPOSITO` varchar(2) DEFAULT NULL,
  `EN_CODRAFAM` varchar(16) DEFAULT NULL COMMENT 'Código Rafam',
  `EN_CANT` double DEFAULT NULL,
  `EN_COSTO` double DEFAULT NULL,
  PRIMARY KEY (`EN_ITEM`,`EN_NROOC`),
  KEY `FK_reng_oc_ordenes_compra` (`EN_NROOC`),
  KEY `FK_reng_oc_artic_gral` (`EN_CODART`,`EN_DEPOSITO`),
  CONSTRAINT `FK_reng_oc_artic_gral` FOREIGN KEY (`EN_CODART`, `EN_DEPOSITO`) REFERENCES `artic_gral` (`AG_CODIGO`, `AG_DEPOSITO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_reng_oc_ordenes_compra` FOREIGN KEY (`EN_NROOC`) REFERENCES `ordenes_compra` (`OC_NRO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Replica renglones Orden de Compra Rafam';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.rs_encab
CREATE TABLE IF NOT EXISTS `rs_encab` (
  `RS_CODEP` varchar(2) DEFAULT NULL COMMENT 'Código del depósito',
  `RS_NROREM` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Número Remito',
  `RS_FECHA` date DEFAULT NULL COMMENT 'Fecha',
  `RS_HORA` time DEFAULT NULL COMMENT 'Hora',
  `RS_CODOPE` varchar(6) DEFAULT NULL COMMENT 'Personal Deposito Central',
  `RS_NUMPED` int(12) DEFAULT NULL COMMENT 'Número Pedido',
  `RS_SERSOL` varchar(3) DEFAULT NULL COMMENT 'Servicio Solicitante',
  `RS_IMPORT` enum('F','T') DEFAULT NULL COMMENT 'Indica si está importado o no',
  PRIMARY KEY (`RS_NROREM`),
  KEY `FK_rs_encab_servicio` (`RS_SERSOL`),
  KEY `FK_rs_encab_deposito` (`RS_CODEP`),
  KEY `FK_rs_encab_plan_ent` (`RS_NUMPED`),
  KEY `FK_rs_encab_legajos` (`RS_CODOPE`),
  CONSTRAINT `FK_rs_encab_deposito` FOREIGN KEY (`RS_CODEP`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_rs_encab_legajos` FOREIGN KEY (`RS_CODOPE`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_rs_encab_plan_ent` FOREIGN KEY (`RS_NUMPED`) REFERENCES `plan_ent` (`PE_NROREM`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_rs_encab_servicio` FOREIGN KEY (`RS_SERSOL`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Son los remitos de entrega de medicamentos del Depósito Central a Farmacia';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.rs_reng
CREATE TABLE IF NOT EXISTS `rs_reng` (
  `RS_CODEP` varchar(2) DEFAULT NULL,
  `RS_NROREM` int(11) NOT NULL,
  `RS_NUMRENG` smallint(6) NOT NULL,
  `RS_CODMON` varchar(4) DEFAULT NULL,
  `RS_CANTID` decimal(10,2) DEFAULT NULL,
  `RS_FECVTO` date DEFAULT NULL,
  `RS_VALULTCOMP` decimal(9,2) DEFAULT NULL,
  PRIMARY KEY (`RS_NROREM`,`RS_NUMRENG`),
  KEY `FK_rs_reng_artic_gral` (`RS_CODMON`),
  KEY `FK_rs_reng_deposito` (`RS_CODEP`),
  CONSTRAINT `FK_rs_reng_artic_gral` FOREIGN KEY (`RS_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`),
  CONSTRAINT `FK_rs_reng_deposito` FOREIGN KEY (`RS_CODEP`) REFERENCES `deposito` (`DE_CODIGO`),
  CONSTRAINT `FK_rs_reng_rs_encab` FOREIGN KEY (`RS_NROREM`) REFERENCES `rs_encab` (`RS_NROREM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Renglones de Remito de Deposito Rs_encab';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.servicio
CREATE TABLE IF NOT EXISTS `servicio` (
  `SE_CODIGO` varchar(3) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `SE_DESCRI` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `SE_TPOSER` enum('S','U','I','A','E') CHARACTER SET utf8 DEFAULT NULL,
  `SE_CCOSTO` varchar(3) CHARACTER SET utf8 DEFAULT NULL,
  `SE_SALA` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `SE_AREA` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `SE_INFO` enum('F','T') CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`SE_CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.serv_soc_tip_ocupac
CREATE TABLE IF NOT EXISTS `serv_soc_tip_ocupac` (
  `cod` varchar(3) NOT NULL,
  `descri` varchar(75) NOT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.tab_vtos
CREATE TABLE IF NOT EXISTS `tab_vtos` (
  `TV_CODART` varchar(4) NOT NULL COMMENT 'Código del artículo',
  `TV_FECVEN` date NOT NULL COMMENT 'Fecha de vencimiento',
  `TV_SALDO` decimal(12,2) DEFAULT NULL COMMENT 'Saldo es lo que queda',
  `TV_DEPOSITO` varchar(2) NOT NULL COMMENT 'Código del subdepósito de farmacia',
  PRIMARY KEY (`TV_CODART`,`TV_FECVEN`,`TV_DEPOSITO`),
  KEY `FK_tab_vtos_deposito` (`TV_DEPOSITO`),
  CONSTRAINT `FK_tab_vtos_artic_gral` FOREIGN KEY (`TV_CODART`) REFERENCES `artic_gral` (`AG_CODIGO`),
  CONSTRAINT `FK_tab_vtos_deposito` FOREIGN KEY (`TV_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vencimientos, por cada artículo y depósitos los distintos lotes que tienen cantidad y vencimiento';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.tip_doc
CREATE TABLE IF NOT EXISTS `tip_doc` (
  `TI_COD` varchar(3) NOT NULL,
  `TI_NOM` varchar(20) DEFAULT NULL,
  `TI_CODART` varchar(2) DEFAULT NULL,
  `TI_MODIF` date DEFAULT NULL,
  `TI_CODOP` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`TI_COD`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.tip_vivienda
CREATE TABLE IF NOT EXISTS `tip_vivienda` (
  `TV_CODIGO` varchar(1) DEFAULT NULL,
  `TV_DETALLE` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.topeart
CREATE TABLE IF NOT EXISTS `topeart` (
  `TA_CODSERV` varchar(3) CHARACTER SET utf8 NOT NULL COMMENT 'Servicio',
  `TA_DEPOSITO` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT 'Deposito',
  `TA_CODART` varchar(4) CHARACTER SET utf8 NOT NULL COMMENT 'Monodroga',
  `TA_CANTID` decimal(12,2) DEFAULT NULL COMMENT 'Cantidad',
  PRIMARY KEY (`TA_CODSERV`,`TA_DEPOSITO`,`TA_CODART`),
  KEY `FK_topeart_artic_gral` (`TA_CODART`,`TA_DEPOSITO`),
  CONSTRAINT `FK_topeart_artic_gral` FOREIGN KEY (`TA_CODART`, `TA_DEPOSITO`) REFERENCES `artic_gral` (`AG_CODIGO`, `AG_DEPOSITO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_topeart_servicio` FOREIGN KEY (`TA_CODSERV`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.topemedi
CREATE TABLE IF NOT EXISTS `topemedi` (
  `id_techo` int(11) NOT NULL AUTO_INCREMENT,
  `TM_CODSERV` varchar(3) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Servicio',
  `TM_DEPOSITO` varchar(2) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Deposito',
  `TM_CODMON` varchar(4) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Monodroga',
  `TM_CANTID` decimal(12,2) DEFAULT NULL COMMENT 'Cantidad',
  PRIMARY KEY (`id_techo`),
  KEY `TM_DEPOSITO` (`TM_DEPOSITO`),
  KEY `FK_topemedi_servicio` (`TM_CODSERV`),
  KEY `FK_topemedi_artic_gral` (`TM_CODMON`),
  KEY `FK_topemedi_artic_gral2` (`TM_CODMON`,`TM_DEPOSITO`),
  CONSTRAINT `FK_topemedi_artic_gral2` FOREIGN KEY (`TM_CODMON`, `TM_DEPOSITO`) REFERENCES `artic_gral` (`AG_CODIGO`, `AG_DEPOSITO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_topemedi_deposito` FOREIGN KEY (`TM_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_topemedi_servicio` FOREIGN KEY (`TM_CODSERV`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.vade_ren
CREATE TABLE IF NOT EXISTS `vade_ren` (
  `VD_NUMVALE` int(12) NOT NULL COMMENT 'Número de vale',
  `VD_NUMRENG` smallint(6) NOT NULL COMMENT 'Número de renglón',
  `VD_CODMON` varchar(4) CHARACTER SET utf8 NOT NULL COMMENT 'Código monodroga',
  `VD_DEPOSITO` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT 'Depósito',
  `VD_CANTID` decimal(7,2) NOT NULL COMMENT 'Cantidad pedida',
  PRIMARY KEY (`VD_NUMVALE`,`VD_NUMRENG`),
  KEY `FK_vade_ren_artic_gral` (`VD_CODMON`),
  KEY `FK_vade_ren_deposito` (`VD_DEPOSITO`),
  CONSTRAINT `FK_vade_ren_artic_gral` FOREIGN KEY (`VD_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vade_ren_deposito` FOREIGN KEY (`VD_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vade_ren_vale_des` FOREIGN KEY (`VD_NUMVALE`) REFERENCES `vale_des` (`VD_NUMVALE`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='pedidos de insumos renglones';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.vaen_ren
CREATE TABLE IF NOT EXISTS `vaen_ren` (
  `VE_NUMVALE` int(11) NOT NULL COMMENT 'Número Vale',
  `VE_NUMRENG` smallint(6) NOT NULL COMMENT 'Renglón',
  `VE_CODMON` varchar(4) CHARACTER SET utf8 NOT NULL COMMENT 'Medicamento',
  `VE_DEPOSITO` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT 'Depósito',
  `VE_CANTID` decimal(7,2) NOT NULL COMMENT 'Cantidad pedida',
  PRIMARY KEY (`VE_NUMVALE`,`VE_NUMRENG`),
  KEY `FK_vaen_ren_artic_gral` (`VE_CODMON`),
  KEY `FK_vaen_ren_deposito` (`VE_DEPOSITO`),
  CONSTRAINT `FK_vaen_ren_artic_gral` FOREIGN KEY (`VE_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vaen_ren_deposito` FOREIGN KEY (`VE_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vaen_ren_vale_enf` FOREIGN KEY (`VE_NUMVALE`) REFERENCES `vale_enf` (`VE_NUMVALE`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Pedido de enfermería por HC renglones';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.valefar
CREATE TABLE IF NOT EXISTS `valefar` (
  `VA_NROVALE` int(12) NOT NULL COMMENT 'Número Vale Farmacia',
  `VA_NUMRENG` smallint(6) NOT NULL COMMENT 'Renglon',
  `VA_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Depósito',
  `VA_CODMON` varchar(4) DEFAULT NULL COMMENT 'Medicamento',
  `VA_CANTID` decimal(10,2) DEFAULT NULL COMMENT 'Cantidad',
  `VA_FECVTO` date DEFAULT NULL COMMENT 'Fecha Vencimiento',
  PRIMARY KEY (`VA_NROVALE`,`VA_NUMRENG`),
  KEY `FK_valefar_deposito` (`VA_DEPOSITO`),
  KEY `FK_valefar_artic_gral` (`VA_CODMON`),
  CONSTRAINT `FK_valefar_artic_gral` FOREIGN KEY (`VA_CODMON`) REFERENCES `artic_gral` (`AG_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_valefar_consmed` FOREIGN KEY (`VA_NROVALE`) REFERENCES `consmed` (`CM_NROVAL`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_valefar_deposito` FOREIGN KEY (`VA_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Son los renglones de los vales de entrega cuyo encabezado es Consmed\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.vale_des
CREATE TABLE IF NOT EXISTS `vale_des` (
  `VD_SERSOL` varchar(3) DEFAULT NULL COMMENT 'Servicio',
  `VD_NUMVALE` int(12) NOT NULL AUTO_INCREMENT COMMENT 'Número',
  `VD_FECHA` date DEFAULT NULL COMMENT 'Fecha',
  `VD_HORA` time DEFAULT NULL COMMENT 'Hora',
  `VD_SUPERV` varchar(6) DEFAULT NULL COMMENT 'Personal Enfermería',
  `VD_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Depósito',
  `VD_PROCESADO` tinyint(1) DEFAULT NULL COMMENT 'Procesado',
  PRIMARY KEY (`VD_NUMVALE`),
  KEY `FK_vale_des_servicio` (`VD_SERSOL`),
  KEY `FK_vale_des_legajos` (`VD_SUPERV`),
  KEY `FK_vale_des_deposito` (`VD_DEPOSITO`),
  CONSTRAINT `FK_vale_des_deposito` FOREIGN KEY (`VD_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vale_des_legajos` FOREIGN KEY (`VD_SUPERV`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vale_des_servicio` FOREIGN KEY (`VD_SERSOL`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vales de pedido de Insumos';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.vale_enf
CREATE TABLE IF NOT EXISTS `vale_enf` (
  `VE_NUMVALE` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Número',
  `VE_HISCLI` int(11) DEFAULT NULL COMMENT 'Historia Clínica',
  `VE_FECHA` date NOT NULL COMMENT 'Fecha',
  `VE_HORA` time NOT NULL COMMENT 'Hora',
  `VE_MEDICO` varchar(6) NOT NULL COMMENT 'Médico',
  `VE_SUPERV` varchar(6) NOT NULL COMMENT 'Supervisor',
  `VE_SERSOL` varchar(3) DEFAULT NULL COMMENT 'Servicio',
  `VE_UDSOL` varchar(3) DEFAULT NULL COMMENT 'Unidad de Diasgnostico Solicitante',
  `VE_CONDPAC` enum('A','I') NOT NULL COMMENT 'Tipo Paciente',
  `VE_IDINTERNA` bigint(20) DEFAULT NULL COMMENT 'Internación',
  `VE_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Depósito',
  `VE_PROCESADO` tinyint(1) NOT NULL COMMENT 'Procesado',
  PRIMARY KEY (`VE_NUMVALE`),
  KEY `FK_vale_enf_paciente` (`VE_HISCLI`),
  KEY `FK_vale_enf_servicio` (`VE_UDSOL`),
  KEY `FK_vale_enf_servicio_2` (`VE_SERSOL`),
  KEY `FK_vale_enf_deposito` (`VE_DEPOSITO`),
  KEY `FK_vale_enf_interna` (`VE_IDINTERNA`),
  KEY `FK_vale_enf_legajos` (`VE_SUPERV`),
  KEY `FK_vale_enf_legajos_2` (`VE_MEDICO`),
  CONSTRAINT `FK_vale_enf_deposito` FOREIGN KEY (`VE_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vale_enf_interna` FOREIGN KEY (`VE_IDINTERNA`) REFERENCES `interna` (`IN_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vale_enf_legajos` FOREIGN KEY (`VE_SUPERV`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vale_enf_legajos_2` FOREIGN KEY (`VE_MEDICO`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vale_enf_paciente` FOREIGN KEY (`VE_HISCLI`) REFERENCES `paciente` (`PA_HISCLI`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vale_enf_servicio` FOREIGN KEY (`VE_UDSOL`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vale_enf_servicio_2` FOREIGN KEY (`VE_SERSOL`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vales de pedido Enfermería por HC';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.vale_mon
CREATE TABLE IF NOT EXISTS `vale_mon` (
  `VM_SERSOL` varchar(3) DEFAULT NULL COMMENT 'Servicio',
  `VM_NUMVALE` int(12) NOT NULL AUTO_INCREMENT COMMENT 'Número',
  `VM_FECHA` date DEFAULT NULL COMMENT 'Fecha',
  `VM_HORA` time DEFAULT NULL COMMENT 'Hora',
  `VM_SUPERV` varchar(6) DEFAULT NULL COMMENT 'Personal Enfermería',
  `VM_DEPOSITO` varchar(2) DEFAULT NULL COMMENT 'Depósito',
  `VM_PROCESADO` tinyint(1) DEFAULT NULL COMMENT 'Procesado',
  PRIMARY KEY (`VM_NUMVALE`),
  KEY `FK_vale_mon_servicio` (`VM_SERSOL`),
  KEY `FK_vale_mon_legajos` (`VM_SUPERV`),
  KEY `FK_vale_mon_deposito` (`VM_DEPOSITO`),
  CONSTRAINT `FK_vale_mon_deposito` FOREIGN KEY (`VM_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vale_mon_legajos` FOREIGN KEY (`VM_SUPERV`) REFERENCES `legajos` (`LE_NUMLEGA`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vale_mon_servicio` FOREIGN KEY (`VM_SERSOL`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vales de pedido Enfermería a Granel';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.vali_rem
CREATE TABLE IF NOT EXISTS `vali_rem` (
  `VR_NROREM` int(12) NOT NULL COMMENT 'Número Remito',
  `VR_SERSOL` varchar(3) NOT NULL COMMENT 'Servicio Solicitante',
  `VR_CONDPAC` enum('A','I') NOT NULL COMMENT 'Ambulatorio o Internado',
  `VR_FECDES` date DEFAULT NULL COMMENT 'Fecha Desde',
  `VR_FECHAS` date DEFAULT NULL COMMENT 'Fecha Hasta',
  `VR_HORDES` time DEFAULT NULL COMMENT 'Hora Desde',
  `VR_HORHAS` time DEFAULT NULL COMMENT 'Hora Hasta',
  PRIMARY KEY (`VR_NROREM`,`VR_SERSOL`,`VR_CONDPAC`),
  KEY `FK_vali_rem_servicio` (`VR_SERSOL`),
  CONSTRAINT `FK_vali_rem_servicio` FOREIGN KEY (`VR_SERSOL`) REFERENCES `servicio` (`SE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Es un contador de número de remitos por cada servicio\r\n';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.vamo_ren
CREATE TABLE IF NOT EXISTS `vamo_ren` (
  `VM_NUMVALE` int(12) NOT NULL COMMENT 'Número de vale',
  `VM_NUMRENG` smallint(6) NOT NULL COMMENT 'Número de renglón',
  `VM_CODMON` varchar(4) CHARACTER SET utf8 NOT NULL COMMENT 'Código monodroga',
  `VM_DEPOSITO` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT 'Depósito',
  `VM_CANTID` decimal(7,2) NOT NULL COMMENT 'Cantidad pedida',
  PRIMARY KEY (`VM_NUMVALE`,`VM_NUMRENG`),
  KEY `FK_vamo_ren_deposito` (`VM_DEPOSITO`),
  KEY `FK_vamo_ren_artic_gral_2` (`VM_CODMON`,`VM_DEPOSITO`),
  CONSTRAINT `FK_vamo_ren_artic_gral_2` FOREIGN KEY (`VM_CODMON`, `VM_DEPOSITO`) REFERENCES `artic_gral` (`AG_CODIGO`, `AG_DEPOSITO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vamo_ren_deposito` FOREIGN KEY (`VM_DEPOSITO`) REFERENCES `deposito` (`DE_CODIGO`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_vamo_ren_vale_mon` FOREIGN KEY (`VM_NUMVALE`) REFERENCES `vale_mon` (`VM_NUMVALE`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='pedidos a granel renglones';

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla hospital.vias
CREATE TABLE IF NOT EXISTS `vias` (
  `VI_CODIGO` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT 'Código',
  `VI_DESCRI` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Descripción',
  PRIMARY KEY (`VI_CODIGO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- La exportación de datos fue deseleccionada.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
