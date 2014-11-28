/*
SQLyog Community v12.02 (64 bit)
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
  `acuerdo_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `acuerdo_nombre` VARCHAR(100) NOT NULL,
  `acuerdo_descripcion` TEXT NOT NULL,
  `acuerdo_intercambio` ENUM('impo','expo') NOT NULL,
  `acuerdo_paises` TEXT NOT NULL,
  `acuerdo_fvigente` DATE NOT NULL,
  `acuerdo_uinsert` INT(10) UNSIGNED NOT NULL,
  `acuerdo_finsert` DATETIME NOT NULL,
  `acuerdo_uupdate` INT(10) UNSIGNED NOT NULL,
  `acuerdo_fupdate` DATETIME NOT NULL,
  PRIMARY KEY (`acuerdo_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

/*Table structure for table `acuerdo_det` */

DROP TABLE IF EXISTS `acuerdo_det`;

CREATE TABLE `acuerdo_det` (
  `acuerdo_det_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `acuerdo_det_acuerdo_id` INT(10) UNSIGNED NOT NULL,
  `acuerdo_det_posiciones` TEXT NOT NULL,
  `acuerdo_det_arancel_base` SMALLINT(5) NOT NULL,
  `acuerdo_det_tipo_contingente_id` INT(10) UNSIGNED NOT NULL,
  `acuerdo_det_tipo_desgravacion_id` INT(10) UNSIGNED NOT NULL,
  `acuerdo_det_nperiodos` SMALLINT(5) UNSIGNED NOT NULL,
  `acuerdo_det_msalvaguardia` ENUM('0','1') NOT NULL,
  `acuerdo_det_administracion` TEXT NOT NULL,
  `acuerdo_det_administrador` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`acuerdo_det_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

/*Table structure for table `arancel` */

DROP TABLE IF EXISTS `arancel`;

CREATE TABLE `arancel` (
  `id_seccion` INT(2) NOT NULL DEFAULT '0',
  `cod_capitulo` INT(2) UNSIGNED ZEROFILL NOT NULL DEFAULT '00',
  `cod_partida` INT(2) UNSIGNED ZEROFILL DEFAULT NULL,
  `cod_subpartida` INT(2) UNSIGNED ZEROFILL DEFAULT NULL,
  `cod_posicion` INT(4) UNSIGNED ZEROFILL DEFAULT NULL,
  `descripcion` VARCHAR(200) NOT NULL DEFAULT '',
  `gravamen` VARCHAR(5) DEFAULT NULL,
  `iva` VARCHAR(5) DEFAULT NULL,
  `ciiu` INT(5) DEFAULT NULL,
  `cuode` INT(5) DEFAULT NULL,
  `notas` LONGTEXT,
  KEY `idx_posicion` (`cod_capitulo`,`cod_partida`,`cod_subpartida`,`cod_posicion`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

/*Table structure for table `category_menu` */

DROP TABLE IF EXISTS `category_menu`;

CREATE TABLE `category_menu` (
  `category_menu_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_menu_name` VARCHAR(45) NOT NULL,
  `category_menu_order` SMALLINT(5) UNSIGNED NOT NULL,
  PRIMARY KEY (`category_menu_id`)
) ENGINE=MYISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `ciiu` */

DROP TABLE IF EXISTS `ciiu`;

CREATE TABLE `ciiu` (
  `id_ciiu` SMALLINT(4) UNSIGNED NOT NULL DEFAULT '0',
  `ciiu` VARCHAR(100) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ciiu`),
  UNIQUE KEY `ciiu` (`ciiu`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

/*Table structure for table `correlativa` */

DROP TABLE IF EXISTS `correlativa`;

CREATE TABLE `correlativa` (
  `correlativa_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `correlativa_fvigente` DATE NOT NULL,
  `correlativa_decreto` VARCHAR(15) NOT NULL,
  `correlativa_observacion` TEXT NOT NULL,
  `correlativa_origen` TEXT NOT NULL,
  `correlativa_destino` TEXT NOT NULL,
  `correlativa_uinsert` INT(10) UNSIGNED NOT NULL,
  `correlativa_finsert` DATETIME NOT NULL,
  `correlativa_uupdate` INT(11) UNSIGNED NOT NULL,
  `correlativa_fupdate` DATETIME NOT NULL,
  PRIMARY KEY (`correlativa_id`)
) ENGINE=MYISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `declaraexp` */

DROP TABLE IF EXISTS `declaraexp`;

CREATE TABLE `declaraexp` (
  `id` INT(10) UNSIGNED NOT NULL,
  `anio` SMALLINT(4) UNSIGNED NOT NULL,
  `periodo` SMALLINT(2) UNSIGNED NOT NULL,
  `id_empresa` VARCHAR(20) NOT NULL,
  `id_paisdestino` SMALLINT(3) UNSIGNED NOT NULL,
  `id_capitulo` CHAR(2) NOT NULL,
  `id_partida` CHAR(4) NOT NULL,
  `id_subpartida` CHAR(6) NOT NULL,
  `id_posicion` CHAR(10) NOT NULL,
  `id_ciiu` SMALLINT(3) UNSIGNED NOT NULL,
  `valorfob` FLOAT(13,2) NOT NULL,
  `valorcif` FLOAT(13,2) NOT NULL,
  `peso_neto` FLOAT(13,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_empresa` (`id_empresa`),
  KEY `id_paisdestino` (`id_paisdestino`),
  KEY `id_posicion` (`id_posicion`),
  KEY `anio` (`anio`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

/*Table structure for table `declaraimp` */

DROP TABLE IF EXISTS `declaraimp`;

CREATE TABLE `declaraimp` (
  `id` INT(10) UNSIGNED NOT NULL,
  `anio` SMALLINT(4) UNSIGNED NOT NULL,
  `periodo` SMALLINT(2) UNSIGNED NOT NULL,
  `id_empresa` VARCHAR(20) NOT NULL,
  `id_paisorigen` SMALLINT(3) UNSIGNED NOT NULL,
  `id_paiscompra` SMALLINT(3) UNSIGNED NOT NULL,
  `id_paisprocedencia` SMALLINT(3) UNSIGNED NOT NULL,
  `id_capitulo` CHAR(2) NOT NULL,
  `id_partida` CHAR(4) NOT NULL,
  `id_subpartida` CHAR(6) NOT NULL,
  `id_posicion` CHAR(10) NOT NULL,
  `id_ciiu` SMALLINT(3) UNSIGNED NOT NULL,
  `valorcif` FLOAT(13,2) NOT NULL,
  `valorfob` FLOAT(13,2) NOT NULL,
  `peso_neto` FLOAT(13,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `anio` (`anio`),
  KEY `id_empresa` (`id_empresa`),
  KEY `id_paisprocedencia` (`id_paisprocedencia`),
  KEY `id_posicion` (`id_posicion`),
  KEY `id_ciiu` (`id_ciiu`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

/*Table structure for table `empresa` */

DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `id_empresa` VARCHAR(20) NOT NULL DEFAULT '0',
  `digito_cheq` VARCHAR(1) NOT NULL DEFAULT '0',
  `empresa` VARCHAR(60) NOT NULL,
  `representante` VARCHAR(50) NOT NULL DEFAULT '',
  `id_departamentos` SMALLINT(2) DEFAULT NULL,
  `departamentos` VARCHAR(21) NOT NULL,
  `id_ciudad` SMALLINT(5) DEFAULT NULL,
  `ciudad` VARCHAR(30) NOT NULL DEFAULT '',
  `direccion` VARCHAR(60) DEFAULT NULL,
  `telefono` VARCHAR(10) DEFAULT NULL,
  `telefono2` VARCHAR(10) DEFAULT NULL,
  `telefono3` VARCHAR(10) DEFAULT NULL,
  `fax` VARCHAR(10) DEFAULT NULL,
  `fax2` VARCHAR(10) DEFAULT NULL,
  `fax3` VARCHAR(10) DEFAULT NULL,
  `email` VARCHAR(60) DEFAULT NULL,
  `clase` VARCHAR(1) DEFAULT '0',
  `uap` CHAR(2) NOT NULL DEFAULT '',
  `altex` CHAR(2) NOT NULL DEFAULT '',
  `web` VARCHAR(60) DEFAULT '',
  `contacto1` VARCHAR(100) DEFAULT '',
  `id_tipo_empresa` VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (`id_empresa`),
  KEY `id_departamentos` (`id_departamentos`),
  KEY `id_ciudad` (`id_ciudad`),
  KEY `id_tipo_empresa` (`id_tipo_empresa`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

/*Table structure for table `indicador` */

DROP TABLE IF EXISTS `indicador`;

CREATE TABLE `indicador` (
  `indicador_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `indicador_nombre` VARCHAR(100) NOT NULL,
  `indicador_tipo_indicador_id` INT(10) UNSIGNED NOT NULL,
  `indicador_campos` TEXT NOT NULL,
  `indicador_filtros` TEXT NOT NULL,
  `indicador_leaf` ENUM('0','1') NOT NULL,
  `indicador_parent` INT(10) UNSIGNED NOT NULL,
  `indicador_uinsert` INT(10) UNSIGNED NOT NULL,
  `indicador_finsert` DATETIME NOT NULL,
  `indicador_fupdate` DATETIME NOT NULL,
  PRIMARY KEY (`indicador_id`)
) ENGINE=MYISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `menu` */

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `menu_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_name` VARCHAR(45) NOT NULL,
  `menu_category_menu_id` INT(10) UNSIGNED NOT NULL,
  `menu_url` VARCHAR(45) NOT NULL,
  `menu_order` INT(11) NOT NULL DEFAULT '1',
  `menu_hidden` ENUM('0','1') NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=MYISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `pais` */

DROP TABLE IF EXISTS `pais`;

CREATE TABLE `pais` (
  `id_pais` SMALLINT(3) UNSIGNED NOT NULL,
  `pais` VARCHAR(40) NOT NULL,
  PRIMARY KEY (`id_pais`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `permissions_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `permissions_profile_id` INT(10) UNSIGNED NOT NULL,
  `permissions_menu_id` INT(10) UNSIGNED NOT NULL,
  `permissions_list` ENUM('0','1') NOT NULL,
  `permissions_modify` ENUM('0','1') NOT NULL,
  `permissions_create` ENUM('0','1') NOT NULL,
  `permissions_delete` ENUM('0','1') NOT NULL,
  `permissions_export` ENUM('0','1') NOT NULL,
  PRIMARY KEY (`permissions_id`),
  UNIQUE KEY `permissions_profile_id` (`permissions_profile_id`,`permissions_menu_id`)
) ENGINE=MYISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `posicion` */

DROP TABLE IF EXISTS `posicion`;

CREATE TABLE `posicion` (
  `id_posicion` VARCHAR(10) NOT NULL,
  `posicion` VARCHAR(250) NOT NULL,
  `id_capitulo` CHAR(2) NOT NULL,
  `id_partida` CHAR(4) NOT NULL,
  `id_subpartida` CHAR(6) NOT NULL,
  PRIMARY KEY (`id_posicion`),
  KEY `id_capitulo` (`id_capitulo`),
  KEY `id_partida` (`id_partida`),
  KEY `id_subpartida` (`id_subpartida`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

/*Table structure for table `profile` */

DROP TABLE IF EXISTS `profile`;

CREATE TABLE `profile` (
  `profile_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `profile_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`profile_id`)
) ENGINE=MYISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `session` */

DROP TABLE IF EXISTS `session`;

CREATE TABLE `session` (
  `session_user_id` INT(10) UNSIGNED NOT NULL,
  `session_php_id` VARCHAR(45) NOT NULL,
  `session_date` DATETIME NOT NULL,
  `session_active` ENUM('0','1') NOT NULL,
  PRIMARY KEY (`session_user_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

/*Table structure for table `tipo_contingente` */

DROP TABLE IF EXISTS `tipo_contingente`;

CREATE TABLE `tipo_contingente` (
  `tipo_contingente_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo_contingente_nombre` VARCHAR(100) NOT NULL,
  `tipo_contingente_mlimite` ENUM('0','1') NOT NULL,
  `tipo_contingente_mmultiano` ENUM('0','1') NOT NULL,
  `tipo_contingente_mmultipais` ENUM('0','1') NOT NULL,
  PRIMARY KEY (`tipo_contingente_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

/*Table structure for table `tipo_indicador` */

DROP TABLE IF EXISTS `tipo_indicador`;

CREATE TABLE `tipo_indicador` (
  `tipo_indicador_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo_indicador_nombre` VARCHAR(100) NOT NULL,
  `tipo_indicador_abrev` VARCHAR(10) NOT NULL,
  `tipo_indicador_activador` ENUM('precio','volumen') NOT NULL,
  `tipo_indicador_calculo` TEXT NOT NULL,
  `tipo_indicador_definicion` TEXT NOT NULL,
  PRIMARY KEY (`tipo_indicador_id`)
) ENGINE=MYISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_full_name` VARCHAR(255) NOT NULL,
  `user_email` VARCHAR(100) NOT NULL,
  `user_password` CHAR(35) NOT NULL,
  `user_active` ENUM('0','1') NOT NULL,
  `user_profile_id` INT(10) UNSIGNED NOT NULL,
  `user_uinsert` INT(10) UNSIGNED NOT NULL,
  `user_finsert` DATETIME NOT NULL,
  `user_uupdate` INT(10) UNSIGNED NOT NULL,
  `user_fupdate` DATETIME NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MYISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
