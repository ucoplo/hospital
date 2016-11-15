ALTER TABLE `reng_oc`
	ADD COLUMN `EN_CODRAFAM` VARCHAR(16) NULL COMMENT 'Código Rafam' AFTER `EN_COSTO`;
	
ALTER TABLE `pead_mov`
	CHANGE COLUMN ` PE_CANTPED` `PE_CANTPED` INT(5) NULL DEFAULT NULL COMMENT 'Cantidad pedida definitiva' AFTER `PE_REDONDEO`,
	CHANGE COLUMN ` PE_SUGERIDO` `PE_SUGERIDO` INT(5) NULL DEFAULT NULL COMMENT 'Cantidad sugerida' AFTER `PE_CANTPED`,
	CHANGE COLUMN ` PE_EXISTENCIA` `PE_EXISTENCIA` INT(5) NULL DEFAULT NULL COMMENT 'Existencia al momento de generar el pedido' AFTER `PE_SUGERIDO`,
	CHANGE COLUMN ` PE_PENDIENTE` `PE_PENDIENTE` INT(5) NULL DEFAULT NULL COMMENT 'Cantidad pendiente al momento de generar el pedido' AFTER `PE_EXISTENCIA`,
	CHANGE COLUMN ` PE_CONSUMO` `PE_CONSUMO` DECIMAL(10,3) NULL DEFAULT NULL COMMENT 'Consumo promedio que se utilizo al generar el pedido' AFTER `PE_PENDIENTE`;
	
ALTER TABLE `movst_qui`
	ALTER ` MS_COD` DROP DEFAULT;
ALTER TABLE `movst_qui`
	CHANGE COLUMN ` MS_COD` `MS_COD` VARCHAR(1) NOT NULL FIRST,
	CHANGE COLUMN ` MS_NOM` `MS_NOM` VARCHAR(25) NULL DEFAULT NULL AFTER `MS_COD`,
	CHANGE COLUMN ` MS_SIGNO` `MS_SIGNO` TINYINT(4) NULL DEFAULT NULL AFTER `MS_NOM`,
	CHANGE COLUMN ` MS_VALIDO` `MS_VALIDO` TINYINT(4) NULL DEFAULT NULL AFTER `MS_SIGNO`;