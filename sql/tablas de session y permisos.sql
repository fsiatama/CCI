/*
SQLyog Community v12.02 (64 bit)
MySQL - 5.6.17 : Database - ssgroup
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ssgroup` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `ssgroup`;

/*Table structure for table `opc_menu` */

DROP TABLE IF EXISTS `opc_menu`;

CREATE TABLE `opc_menu` (
  `opc_menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `opc_menu_nombre` varchar(45) NOT NULL,
  `opc_menu_categoria_menu_id` int(11) NOT NULL,
  `opc_menu_url` varchar(45) NOT NULL,
  `opc_menu_order` int(11) NOT NULL DEFAULT '1',
  `opc_menu_oculto` enum('0','1') NOT NULL,
  PRIMARY KEY (`opc_menu_id`),
  KEY `fk_opc_menu_categoria_menu1` (`opc_menu_categoria_menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

/*Table structure for table `perfil` */

DROP TABLE IF EXISTS `perfil`;

CREATE TABLE `perfil` (
  `perfil_id` int(11) NOT NULL AUTO_INCREMENT,
  `perfil_nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`perfil_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Table structure for table `permisos` */

DROP TABLE IF EXISTS `permisos`;

CREATE TABLE `permisos` (
  `permisos_id` int(11) NOT NULL AUTO_INCREMENT,
  `permisos_perfil_id` int(11) NOT NULL,
  `permisos_opc_menu_id` int(11) NOT NULL,
  `permisos_listar` tinyint(4) NOT NULL DEFAULT '1',
  `permisos_modificar` tinyint(4) NOT NULL DEFAULT '1',
  `permisos_crear` tinyint(4) NOT NULL DEFAULT '1',
  `permisos_borrar` tinyint(4) NOT NULL DEFAULT '1',
  `permisos_exportar` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`permisos_id`),
  UNIQUE KEY `permisos_perfil_id` (`permisos_perfil_id`,`permisos_opc_menu_id`),
  KEY `fk_permisos_perfil1` (`permisos_perfil_id`),
  KEY `fk_permisos_opc_menu1` (`permisos_opc_menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

/*Table structure for table `session` */

DROP TABLE IF EXISTS `session`;

CREATE TABLE `session` (
  `session_usuario_id` int(11) NOT NULL,
  `session_php_id` varchar(45) NOT NULL,
  `session_date` datetime NOT NULL,
  `session_activa` enum('0','1') NOT NULL,
  PRIMARY KEY (`session_usuario_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `usuario_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_pnombre` varchar(45) NOT NULL,
  `usuario_snombre` varchar(45) DEFAULT NULL,
  `usuario_papellido` varchar(45) NOT NULL,
  `usuario_sapellido` varchar(45) DEFAULT NULL,
  `usuario_email` varchar(45) NOT NULL,
  `usuario_password` varchar(45) NOT NULL,
  `usuario_root` enum('0','1') NOT NULL,
  `usuario_activo` enum('0','1') NOT NULL,
  `usuario_perfil_id` int(11) NOT NULL,
  `usuario_finsert` datetime NOT NULL,
  `usuario_uinsert` int(11) NOT NULL DEFAULT '0',
  `usuario_CityId` varchar(60) NOT NULL COMMENT 'ciudad natal (se cambia a texto)',
  `usuario_CountryId` smallint(6) NOT NULL COMMENT 'pais natal',
  `usuario_CountryId2` smallint(6) NOT NULL COMMENT 'pais de residencia',
  `usuario_CityId2` varchar(60) NOT NULL COMMENT '(se cambia a texto y se utiliza para la region)',
  `usuario_SkypeId` varchar(45) NOT NULL,
  `usuario_tipos_identificacion_id` int(11) NOT NULL,
  `usuario_documento_ident` varchar(45) NOT NULL COMMENT 'Numero del documento de identificacion',
  `usuario_genero` enum('0','1') NOT NULL COMMENT 'genero\n0 - Hombre\n1 - Mujer',
  `usuario_fnacimiento` date NOT NULL,
  `usuario_activationKey` varchar(20) NOT NULL COMMENT 'llave de activacion en el sistema',
  `usuario_reclutador_id` int(11) NOT NULL COMMENT 'el id del reclutador que lo asesoro',
  `usuario_identificacion_imagen` char(40) NOT NULL,
  `usuario_firma` char(40) NOT NULL COMMENT 'Firma escaneada',
  `usuario_fecha_formatos1` date NOT NULL,
  `usuario_campo_disponible2` char(1) DEFAULT NULL,
  `usuario_campo_disponible3` char(1) DEFAULT NULL,
  `usuario_campo_disponible4` char(1) DEFAULT NULL,
  `usuario_campo_disponible5` char(1) DEFAULT NULL,
  PRIMARY KEY (`usuario_id`),
  KEY `usuario_activationKey` (`usuario_activationKey`),
  KEY `usuario_CountryId` (`usuario_CountryId`),
  KEY `usuario_CountryId2` (`usuario_CountryId2`),
  KEY `usuario_perfil_id` (`usuario_perfil_id`),
  KEY `usuario_reclutador_id` (`usuario_reclutador_id`),
  KEY `usuario_email` (`usuario_email`),
  FULLTEXT KEY `usuario_pnombre` (`usuario_pnombre`,`usuario_snombre`,`usuario_papellido`,`usuario_sapellido`,`usuario_email`)
) ENGINE=MyISAM AUTO_INCREMENT=7577 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
