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
-- Volcando datos para la tabla administrativa.sessions_permisos_intranet: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `sessions_permisos_intranet` DISABLE KEYS */;
INSERT INTO `sessions_intranet_privilegios` (`cod`, `descri`) VALUES
	('213001principal','Acceso a Módulo Depósito Central'),

	('213017blanquear','Depósito Central-Selección Depósito para Blanqueo de Stock' ),
	('213017blanquear_stock','Depósito Central-Blanqueo de Stock Lotes'),
	('213017update','Depósito Central-Modificación Movimientos Diarios'),
	('213017seleccionar_movimientos','Depósito Central-Selección de Fecha y Depósito Movimientos Diarios'),

	('213016index','Depósito Central-Listado de Pérdidas'),
	('213016view','Depósito Central-Visualizar Pérdida'),
	('213016create','Depósito Central-Crear Pérdida'),
    ('213016report','Depósito Central-Imprimir Remito Pérdida'),

	('213015index','Depósito Central-Listado Devoluciones Sobrantes'),
	('213015view','Depósito Central-Visualizar Devoluciones Sobrantes'),
	('213015create','Depósito Central-Crear Devoluciones Sobrantes'),
    ('213015report','Depósito Central-Imprimir Devoluciones Sobrantes'),

	('213014report','Depósito Central-Imprimir Devoluciones Planillas de Sala'),
	('213014index','Depósito Central-Listado Devoluciones Planillas de Sala'),
	('213014view','Depósito Central-Visualizar Devoluciones Planillas de Sala'),
	('213014seleccion_remito','Depósito Central-Selección Remito para Devoluciones Planillas de Sala'),
	('213014iniciar_creacion','Depósito Central-Iniciar creación Devoluciones Planillas de Sala'),	
	('213014create','Depósito Central-Crear Devoluciones Planillas de Sala'),

	('213013seleccion_servicio','Depósito Central-Selección Pedido Insumo para suministro'),
	('213013index','Depósito Central-Listado Suministros Salas'),
	('213013view','Depósito Central-Visualizar Suministros Salas'),
	('213013report','Depósito Central-Imprimir Suministros Salas'),
	('213013create','Depósito Central-Crear Suministros Salas'),
	('213013create_sin_pedido','Depósito Central-Crear Suministros sin Pedido'),
	('213013iniciar_creacion','Depósito Central-Iniciar Creación Suministros Salas'),

	('213012index','Depósito Central-Listado Devoluciones a Proveedor'),
	('213012view','Depósito Central-Visualizar Devoluciones a Proveedor'),
	('213012create','Depósito Central-Crear Devoluciones a Proveedor'),
	('213012report','Depósito Central-Imprimir Devolución a proveedor'),

	('213011index','Depósito Central-Listado Remitos Adquisición'),
	('213011view','Depósito Central-Visualizar Remitos Adquisición'),
	('213011seleccion_orden_compra','Depósito Central-Seleccionar Orden de Compra para adquisición'),
	('213011asociar_pedido','Depósito Central-Asociar Pedido a Orden de Compra para Adquisición'),
	('213011create_orden_compra','Depósito Central-Crear Adquisición desde Orden de Compra'),
	('213011report','Depósito Central-Imprimir Remito Adquisición'),
	('213011create_adquisicion','Depósito Central-Crear Adquisición sin Orden de Compra'),

	('213010index','Depósito Central-Listado Pedidos Reposición'),
	('213010view','Depósito Central-Visualizar Pedidos Reposición'),
	('213010create','Depósito Central-Crear Pedidos Reposición'),
	('213010generar_pedido','Depósito Central-Generar el Pedidos de Reposición')


	;
	