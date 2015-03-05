/*
SQLyog Community v12.08 (64 bit)
MySQL - 5.6.17 : Database - min_agricultura
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`min_agricultura` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `min_agricultura`;

/*Table structure for table `acuerdo` */

DROP TABLE IF EXISTS `acuerdo`;

CREATE TABLE `acuerdo` (
  `acuerdo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `acuerdo_nombre` varchar(100) NOT NULL,
  `acuerdo_descripcion` text NOT NULL,
  `acuerdo_intercambio` enum('impo','expo') NOT NULL,
  `acuerdo_fvigente` date NOT NULL,
  `acuerdo_ffirma` date NOT NULL,
  `acuerdo_ley` varchar(100) NOT NULL,
  `acuerdo_decreto` varchar(100) NOT NULL,
  `acuerdo_url` varchar(200) NOT NULL,
  `acuerdo_tipo_acuerdo` varchar(100) NOT NULL,
  `acuerdo_uinsert` int(10) unsigned NOT NULL,
  `acuerdo_finsert` datetime NOT NULL,
  `acuerdo_uupdate` int(10) unsigned NOT NULL,
  `acuerdo_fupdate` datetime NOT NULL,
  `acuerdo_mercado_id` int(10) unsigned NOT NULL,
  `acuerdo_id_pais` smallint(3) unsigned NOT NULL,
  PRIMARY KEY (`acuerdo_id`),
  KEY `fk_acuerdo_mercado1_idx` (`acuerdo_mercado_id`),
  KEY `fk_acuerdo_pais1_idx` (`acuerdo_id_pais`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Table structure for table `acuerdo_det` */

DROP TABLE IF EXISTS `acuerdo_det`;

CREATE TABLE `acuerdo_det` (
  `acuerdo_det_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `acuerdo_det_arancel_base` smallint(5) NOT NULL,
  `acuerdo_det_productos` text NOT NULL,
  `acuerdo_det_productos_desc` varchar(45) NOT NULL,
  `acuerdo_det_administracion` text NOT NULL,
  `acuerdo_det_administrador` varchar(150) NOT NULL,
  `acuerdo_det_nperiodos` smallint(4) unsigned NOT NULL,
  `acuerdo_det_acuerdo_id` int(10) unsigned NOT NULL,
  `acuerdo_det_contingente_acumulado_pais` enum('0','1') NOT NULL,
  `acuerdo_det_desgravacion_igual_pais` enum('0','1') NOT NULL,
  PRIMARY KEY (`acuerdo_det_id`,`acuerdo_det_acuerdo_id`),
  KEY `fk_acuerdo_det_acuerdo_idx` (`acuerdo_det_acuerdo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=123 DEFAULT CHARSET=utf8;

/*Table structure for table `alerta` */

DROP TABLE IF EXISTS `alerta`;

CREATE TABLE `alerta` (
  `alerta_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alerta_contingente_verde` smallint(4) NOT NULL,
  `alerta_contingente_amarilla` smallint(4) NOT NULL,
  `alerta_contingente_roja` smallint(4) NOT NULL,
  `alerta_salvaguardia_verde` smallint(4) NOT NULL,
  `alerta_salvaguardia_amarilla` smallint(4) NOT NULL,
  `alerta_salvaguardia_roja` smallint(4) NOT NULL,
  `alerta_emails` text NOT NULL,
  `alerta_contingente_id` int(10) unsigned NOT NULL,
  `alerta_contingente_acuerdo_det_id` int(10) unsigned NOT NULL,
  `alerta_contingente_acuerdo_det_acuerdo_id` int(10) unsigned NOT NULL,
  `alerta_disp1` char(1) NOT NULL,
  `alerta_disp2` char(1) NOT NULL,
  `alerta_disp3` char(1) NOT NULL,
  `alerta_disp4` char(1) NOT NULL,
  `alerta_disp5` char(1) NOT NULL,
  `alerta_disp6` char(1) NOT NULL,
  PRIMARY KEY (`alerta_id`,`alerta_contingente_id`,`alerta_contingente_acuerdo_det_id`,`alerta_contingente_acuerdo_det_acuerdo_id`),
  UNIQUE KEY `fk_alerta_contingente1_idx` (`alerta_contingente_id`,`alerta_contingente_acuerdo_det_id`,`alerta_contingente_acuerdo_det_acuerdo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=242 DEFAULT CHARSET=utf8;

/*Table structure for table `arancel` */

DROP TABLE IF EXISTS `arancel`;

CREATE TABLE `arancel` (
  `id_seccion` int(2) NOT NULL DEFAULT '0',
  `cod_capitulo` int(2) unsigned zerofill NOT NULL DEFAULT '00',
  `cod_partida` int(2) unsigned zerofill DEFAULT NULL,
  `cod_subpartida` int(2) unsigned zerofill DEFAULT NULL,
  `cod_posicion` int(4) unsigned zerofill DEFAULT NULL,
  `descripcion` varchar(200) NOT NULL DEFAULT '',
  `gravamen` varchar(5) DEFAULT NULL,
  `iva` varchar(5) DEFAULT NULL,
  `ciiu` int(5) DEFAULT NULL,
  `cuode` int(5) DEFAULT NULL,
  `notas` longtext,
  KEY `idx_posicion` (`cod_capitulo`,`cod_partida`,`cod_subpartida`,`cod_posicion`),
  KEY `cod_capitulo` (`cod_capitulo`),
  KEY `cod_partida` (`cod_partida`),
  KEY `cod_subpartida` (`cod_subpartida`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `audit` */

DROP TABLE IF EXISTS `audit`;

CREATE TABLE `audit` (
  `audit_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `audit_table` varchar(30) CHARACTER SET latin1 NOT NULL,
  `audit_script` varchar(100) CHARACTER SET latin1 NOT NULL,
  `audit_method` varchar(100) CHARACTER SET latin1 NOT NULL,
  `audit_parameters` text CHARACTER SET latin1 NOT NULL,
  `audit_uinsert` int(10) unsigned NOT NULL,
  `audit_finsert` datetime NOT NULL,
  PRIMARY KEY (`audit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

/*Table structure for table `category_menu` */

DROP TABLE IF EXISTS `category_menu`;

CREATE TABLE `category_menu` (
  `category_menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_menu_name` varchar(45) NOT NULL,
  `category_menu_order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`category_menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `ciiu` */

DROP TABLE IF EXISTS `ciiu`;

CREATE TABLE `ciiu` (
  `id_ciiu` smallint(4) unsigned NOT NULL DEFAULT '0',
  `ciiu` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ciiu`),
  UNIQUE KEY `ciiu` (`ciiu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `comtrade_country` */

DROP TABLE IF EXISTS `comtrade_country`;

CREATE TABLE `comtrade_country` (
  `id_country` int(10) unsigned NOT NULL,
  `country` varchar(100) NOT NULL,
  PRIMARY KEY (`id_country`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `contingente` */

DROP TABLE IF EXISTS `contingente`;

CREATE TABLE `contingente` (
  `contingente_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contingente_id_pais` smallint(3) unsigned NOT NULL,
  `contingente_mcontingente` enum('0','1') NOT NULL,
  `contingente_desc` text NOT NULL,
  `contingente_msalvaguardia` enum('0','1') NOT NULL,
  `contingente_salvaguardia_sobretasa` smallint(4) unsigned NOT NULL,
  `contingente_acuerdo_det_id` int(10) unsigned NOT NULL,
  `contingente_acuerdo_det_acuerdo_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`contingente_id`,`contingente_acuerdo_det_id`,`contingente_acuerdo_det_acuerdo_id`),
  KEY `fk_contingente_acuerdo_det1_idx` (`contingente_acuerdo_det_id`,`contingente_acuerdo_det_acuerdo_id`),
  KEY `fk_contingente_pais1_idx` (`contingente_id_pais`)
) ENGINE=MyISAM AUTO_INCREMENT=219 DEFAULT CHARSET=utf8;

/*Table structure for table `contingente_det` */

DROP TABLE IF EXISTS `contingente_det`;

CREATE TABLE `contingente_det` (
  `contingente_det_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contingente_det_anio_ini` smallint(4) unsigned NOT NULL,
  `contingente_det_anio_fin` smallint(4) unsigned NOT NULL,
  `contingente_det_peso_neto` decimal(13,2) unsigned NOT NULL,
  `contingente_det_tipo_operacion` enum('igual','aumento_porcentual','aumento_toneladas') NOT NULL DEFAULT 'igual',
  `contingente_det_contingente_id` int(10) unsigned NOT NULL,
  `contingente_det_contingente_acuerdo_det_id` int(10) unsigned NOT NULL,
  `contingente_det_contingente_acuerdo_det_acuerdo_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`contingente_det_id`,`contingente_det_contingente_id`,`contingente_det_contingente_acuerdo_det_id`,`contingente_det_contingente_acuerdo_det_acuerdo_id`),
  KEY `fk_contingente_det_contingente1_idx` (`contingente_det_contingente_id`,`contingente_det_contingente_acuerdo_det_id`,`contingente_det_contingente_acuerdo_det_acuerdo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7140 DEFAULT CHARSET=utf8;

/*Table structure for table `correlativa` */

DROP TABLE IF EXISTS `correlativa`;

CREATE TABLE `correlativa` (
  `correlativa_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `correlativa_fvigente` date NOT NULL,
  `correlativa_decreto` varchar(15) NOT NULL,
  `correlativa_observacion` text NOT NULL,
  `correlativa_origen` text NOT NULL,
  `correlativa_destino` text NOT NULL,
  `correlativa_uinsert` int(10) unsigned NOT NULL,
  `correlativa_finsert` datetime NOT NULL,
  `correlativa_uupdate` int(10) unsigned NOT NULL,
  `correlativa_fupdate` datetime NOT NULL,
  PRIMARY KEY (`correlativa_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `declaraexp` */

DROP TABLE IF EXISTS `declaraexp`;

CREATE TABLE `declaraexp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anio` smallint(4) unsigned NOT NULL,
  `periodo` smallint(2) unsigned NOT NULL,
  `id_empresa` varchar(20) NOT NULL,
  `id_paisdestino` smallint(3) unsigned NOT NULL,
  `id_deptorigen` smallint(4) unsigned NOT NULL,
  `id_capitulo` char(2) NOT NULL,
  `id_partida` char(4) NOT NULL,
  `id_subpartida` char(6) NOT NULL,
  `id_posicion` char(10) NOT NULL,
  `id_ciiu` smallint(3) unsigned NOT NULL,
  `valorfob` float(13,2) unsigned NOT NULL,
  `valorcif` float(13,2) unsigned NOT NULL,
  `valor_pesos` float(15,2) unsigned NOT NULL,
  `peso_neto` float(13,2) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_empresa` (`id_empresa`),
  KEY `id_paisdestino` (`id_paisdestino`),
  KEY `id_posicion` (`id_posicion`),
  KEY `anio` (`anio`),
  KEY `id_deptorigen` (`id_deptorigen`)
) ENGINE=MyISAM AUTO_INCREMENT=2308373 DEFAULT CHARSET=utf8;

/*Table structure for table `declaraimp` */

DROP TABLE IF EXISTS `declaraimp`;

CREATE TABLE `declaraimp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anio` smallint(4) unsigned NOT NULL,
  `periodo` smallint(2) unsigned NOT NULL,
  `id_empresa` varchar(20) NOT NULL,
  `id_paisorigen` smallint(3) unsigned NOT NULL,
  `id_paiscompra` smallint(3) unsigned NOT NULL,
  `id_paisprocedencia` smallint(3) unsigned NOT NULL,
  `id_deptorigen` smallint(4) unsigned NOT NULL,
  `id_capitulo` char(2) NOT NULL,
  `id_partida` char(4) NOT NULL,
  `id_subpartida` char(6) NOT NULL,
  `id_posicion` char(10) NOT NULL,
  `id_ciiu` smallint(3) unsigned NOT NULL,
  `valorcif` float(13,2) NOT NULL,
  `valorfob` float(13,2) NOT NULL,
  `peso_neto` float(13,2) NOT NULL,
  `arancel_pagado` float(13,2) NOT NULL,
  `valorarancel` float(13,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `anio` (`anio`),
  KEY `id_empresa` (`id_empresa`),
  KEY `id_paisprocedencia` (`id_paisprocedencia`),
  KEY `id_posicion` (`id_posicion`),
  KEY `id_ciiu` (`id_ciiu`),
  KEY `id_deptorigen` (`id_deptorigen`)
) ENGINE=MyISAM AUTO_INCREMENT=11811579 DEFAULT CHARSET=utf8;

/*Table structure for table `departamento` */

DROP TABLE IF EXISTS `departamento`;

CREATE TABLE `departamento` (
  `id_departamento` smallint(4) unsigned NOT NULL DEFAULT '0',
  `departamento` varchar(35) CHARACTER SET latin1 NOT NULL,
  `id_region` smallint(2) unsigned NOT NULL,
  PRIMARY KEY (`id_departamento`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `desgravacion` */

DROP TABLE IF EXISTS `desgravacion`;

CREATE TABLE `desgravacion` (
  `desgravacion_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `desgravacion_id_pais` smallint(3) unsigned NOT NULL,
  `desgravacion_mdesgravacion` enum('0','1') NOT NULL,
  `desgravacion_desc` text NOT NULL,
  `desgravacion_acuerdo_det_id` int(10) unsigned NOT NULL,
  `desgravacion_acuerdo_det_acuerdo_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`desgravacion_id`,`desgravacion_acuerdo_det_id`,`desgravacion_acuerdo_det_acuerdo_id`),
  KEY `fk_desgravacion_acuerdo_det1_idx` (`desgravacion_acuerdo_det_id`,`desgravacion_acuerdo_det_acuerdo_id`),
  KEY `fk_desgravacion_pais1_idx` (`desgravacion_id_pais`)
) ENGINE=MyISAM AUTO_INCREMENT=211 DEFAULT CHARSET=utf8;

/*Table structure for table `desgravacion_det` */

DROP TABLE IF EXISTS `desgravacion_det`;

CREATE TABLE `desgravacion_det` (
  `desgravacion_det_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `desgravacion_det_anio_ini` smallint(4) unsigned NOT NULL,
  `desgravacion_det_anio_fin` smallint(4) unsigned NOT NULL,
  `desgravacion_det_tasa` decimal(13,2) unsigned NOT NULL,
  `desgravacion_det_tipo_operacion` enum('igual','reduccion_porcentual') NOT NULL DEFAULT 'igual',
  `desgravacion_det_desgravacion_id` int(10) unsigned NOT NULL,
  `desgravacion_det_desgravacion_acuerdo_det_id` int(10) unsigned NOT NULL,
  `desgravacion_det_desgravacion_acuerdo_det_acuerdo_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`desgravacion_det_id`,`desgravacion_det_desgravacion_id`,`desgravacion_det_desgravacion_acuerdo_det_id`,`desgravacion_det_desgravacion_acuerdo_det_acuerdo_id`),
  KEY `fk_desgravacion_det_desgravacion1_idx` (`desgravacion_det_desgravacion_id`,`desgravacion_det_desgravacion_acuerdo_det_id`,`desgravacion_det_desgravacion_acuerdo_det_acuerdo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5472 DEFAULT CHARSET=utf8;

/*Table structure for table `empresa` */

DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `id_empresa` varchar(20) NOT NULL DEFAULT '0',
  `digito_cheq` varchar(1) NOT NULL DEFAULT '0',
  `empresa` varchar(60) NOT NULL,
  `representante` varchar(50) NOT NULL DEFAULT '',
  `id_departamentos` smallint(2) DEFAULT NULL,
  `departamentos` varchar(21) NOT NULL,
  `id_ciudad` smallint(5) DEFAULT NULL,
  `ciudad` varchar(30) NOT NULL DEFAULT '',
  `direccion` varchar(60) DEFAULT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `telefono2` varchar(10) DEFAULT NULL,
  `telefono3` varchar(10) DEFAULT NULL,
  `fax` varchar(10) DEFAULT NULL,
  `fax2` varchar(10) DEFAULT NULL,
  `fax3` varchar(10) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `clase` varchar(1) DEFAULT '0',
  `uap` char(2) NOT NULL DEFAULT '',
  `altex` char(2) NOT NULL DEFAULT '',
  `web` varchar(60) DEFAULT '',
  `contacto1` varchar(100) DEFAULT '',
  `id_tipo_empresa` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_empresa`),
  KEY `id_departamentos` (`id_departamentos`),
  KEY `id_ciudad` (`id_ciudad`),
  KEY `id_tipo_empresa` (`id_tipo_empresa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `indicador` */

DROP TABLE IF EXISTS `indicador`;

CREATE TABLE `indicador` (
  `indicador_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indicador_nombre` varchar(100) NOT NULL,
  `indicador_tipo_indicador_id` int(10) unsigned NOT NULL,
  `indicador_campos` text NOT NULL,
  `indicador_filtros` text NOT NULL,
  `indicador_leaf` enum('0','1') NOT NULL,
  `indicador_parent` int(10) unsigned NOT NULL,
  `indicador_uinsert` int(10) unsigned NOT NULL,
  `indicador_finsert` datetime NOT NULL,
  `indicador_fupdate` datetime NOT NULL,
  PRIMARY KEY (`indicador_id`)
) ENGINE=MyISAM AUTO_INCREMENT=153 DEFAULT CHARSET=utf8;

/*Table structure for table `menu` */

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(45) NOT NULL,
  `menu_category_menu_id` int(10) unsigned NOT NULL,
  `menu_url` varchar(45) NOT NULL,
  `menu_order` int(11) NOT NULL DEFAULT '1',
  `menu_hidden` enum('0','1') NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Table structure for table `mercado` */

DROP TABLE IF EXISTS `mercado`;

CREATE TABLE `mercado` (
  `mercado_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mercado_nombre` varchar(100) NOT NULL,
  `mercado_paises` text NOT NULL,
  `mercado_bandera` char(15) NOT NULL,
  `mercado_uinsert` int(10) unsigned NOT NULL,
  `mercado_finsert` datetime NOT NULL,
  `mercado_uupdate` int(10) unsigned NOT NULL,
  `mercado_fupdate` datetime NOT NULL,
  PRIMARY KEY (`mercado_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `pais` */

DROP TABLE IF EXISTS `pais`;

CREATE TABLE `pais` (
  `id_pais` smallint(3) unsigned NOT NULL,
  `pais` varchar(40) NOT NULL,
  `pais_iata` char(3) NOT NULL,
  PRIMARY KEY (`id_pais`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `permissions_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permissions_profile_id` int(10) unsigned NOT NULL,
  `permissions_menu_id` int(10) unsigned NOT NULL,
  `permissions_list` enum('0','1') NOT NULL,
  `permissions_modify` enum('0','1') NOT NULL,
  `permissions_create` enum('0','1') NOT NULL,
  `permissions_delete` enum('0','1') NOT NULL,
  `permissions_export` enum('0','1') NOT NULL,
  PRIMARY KEY (`permissions_id`),
  UNIQUE KEY `permissions_profile_id` (`permissions_profile_id`,`permissions_menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Table structure for table `pib` */

DROP TABLE IF EXISTS `pib`;

CREATE TABLE `pib` (
  `pib_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pib_anio` smallint(4) unsigned NOT NULL,
  `pib_periodo` smallint(2) unsigned NOT NULL,
  `pib_agricultura` float(13,2) unsigned NOT NULL,
  `pib_nacional` float(13,2) unsigned NOT NULL,
  `pib_finsert` datetime NOT NULL,
  `pib_uinsert` int(10) unsigned NOT NULL,
  `pib_fupdate` datetime NOT NULL,
  `pib_uupdate` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pib_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `posicion` */

DROP TABLE IF EXISTS `posicion`;

CREATE TABLE `posicion` (
  `id_posicion` char(10) NOT NULL,
  `posicion` varchar(250) NOT NULL,
  `id_capitulo` char(2) NOT NULL,
  `id_partida` char(4) NOT NULL,
  `id_subpartida` char(6) NOT NULL,
  PRIMARY KEY (`id_posicion`),
  KEY `id_capitulo` (`id_capitulo`),
  KEY `id_partida` (`id_partida`),
  KEY `id_subpartida` (`id_subpartida`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `produccion` */

DROP TABLE IF EXISTS `produccion`;

CREATE TABLE `produccion` (
  `produccion_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `produccion_sector_id` int(10) unsigned NOT NULL,
  `produccion_anio` smallint(4) unsigned NOT NULL,
  `produccion_peso_neto` float(13,2) unsigned NOT NULL,
  `produccion_finsert` datetime NOT NULL,
  `produccion_uinsert` int(10) unsigned NOT NULL,
  `produccion_fupdate` datetime NOT NULL,
  `produccion_uupdate` int(10) unsigned NOT NULL,
  PRIMARY KEY (`produccion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Table structure for table `profile` */

DROP TABLE IF EXISTS `profile`;

CREATE TABLE `profile` (
  `profile_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(45) NOT NULL,
  PRIMARY KEY (`profile_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `region` */

DROP TABLE IF EXISTS `region`;

CREATE TABLE `region` (
  `id_region` smallint(2) unsigned NOT NULL,
  `region` varchar(30) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id_region`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `sector` */

DROP TABLE IF EXISTS `sector`;

CREATE TABLE `sector` (
  `sector_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sector_nombre` varchar(100) NOT NULL,
  `sector_productos` text NOT NULL,
  `sector_uinsert` int(10) unsigned NOT NULL,
  `sector_finsert` datetime NOT NULL,
  `sector_uupdate` int(10) unsigned NOT NULL,
  `sector_fupdate` datetime NOT NULL,
  PRIMARY KEY (`sector_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Table structure for table `session` */

DROP TABLE IF EXISTS `session`;

CREATE TABLE `session` (
  `session_user_id` int(10) unsigned NOT NULL,
  `session_php_id` varchar(45) NOT NULL,
  `session_date` datetime NOT NULL,
  `session_active` enum('0','1') NOT NULL,
  PRIMARY KEY (`session_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `subpartida` */

DROP TABLE IF EXISTS `subpartida`;

CREATE TABLE `subpartida` (
  `id_subpartida` char(6) CHARACTER SET latin1 NOT NULL,
  `subpartida` varchar(100) CHARACTER SET latin1 NOT NULL,
  `id_capitulo` char(2) NOT NULL,
  `id_partida` char(4) NOT NULL,
  PRIMARY KEY (`id_subpartida`),
  KEY `id_capitulo` (`id_capitulo`),
  KEY `id_partida` (`id_partida`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `tipo_contingente` */

DROP TABLE IF EXISTS `tipo_contingente`;

CREATE TABLE `tipo_contingente` (
  `tipo_contingente_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_contingente_nombre` varchar(100) NOT NULL,
  `tipo_contingente_mlimite` enum('0','1') NOT NULL,
  `tipo_contingente_mmultiano` enum('0','1') NOT NULL,
  `tipo_contingente_mmultipais` enum('0','1') NOT NULL,
  PRIMARY KEY (`tipo_contingente_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `tipo_indicador` */

DROP TABLE IF EXISTS `tipo_indicador`;

CREATE TABLE `tipo_indicador` (
  `tipo_indicador_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_indicador_nombre` varchar(100) NOT NULL,
  `tipo_indicador_abrev` varchar(30) NOT NULL,
  `tipo_indicador_activador` enum('precio','volumen') NOT NULL,
  `tipo_indicador_calculo` text NOT NULL,
  `tipo_indicador_definicion` text NOT NULL,
  `tipo_indicador_html` text NOT NULL,
  PRIMARY KEY (`tipo_indicador_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_full_name` varchar(255) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` char(35) NOT NULL,
  `user_active` enum('0','1') NOT NULL,
  `user_profile_id` int(10) unsigned NOT NULL,
  `user_uinsert` int(10) unsigned NOT NULL,
  `user_finsert` datetime NOT NULL,
  `user_uupdate` int(10) unsigned NOT NULL,
  `user_fupdate` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
