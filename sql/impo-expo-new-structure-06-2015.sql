/*
SQLyog Community v12.12 (64 bit)
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

/*Table structure for table `declaraexp` */

DROP TABLE IF EXISTS `declaraexp`;

CREATE TABLE `declaraexp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anio` smallint(4) unsigned NOT NULL,
  `periodo` smallint(2) unsigned NOT NULL,
  `fecha` date NOT NULL,
  `id_empresa` varchar(20) NOT NULL,
  `id_paisdestino` smallint(3) unsigned NOT NULL,
  `id_deptorigen` smallint(4) unsigned NOT NULL,
  `id_capitulo` char(2) NOT NULL,
  `id_partida` char(4) NOT NULL,
  `id_subpartida` char(6) NOT NULL,
  `id_posicion` char(10) NOT NULL,
  `id_ciiu` smallint(3) unsigned NOT NULL,
  `valorfob` decimal(13,2) unsigned NOT NULL,
  `valorcif` decimal(13,2) unsigned NOT NULL,
  `valor_pesos` decimal(15,2) unsigned NOT NULL,
  `peso_neto` decimal(13,2) unsigned NOT NULL,
  `cantidad` decimal(13,2) unsigned NOT NULL,
  `unidad` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_empresa` (`id_empresa`),
  KEY `id_paisdestino` (`id_paisdestino`),
  KEY `id_posicion` (`id_posicion`),
  KEY `anio` (`anio`),
  KEY `id_deptorigen` (`id_deptorigen`),
  KEY `id_capitulo` (`id_capitulo`),
  KEY `id_partida` (`id_partida`),
  KEY `id_subpartida` (`id_subpartida`),
  KEY `fecha` (`fecha`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `declaraimp` */

DROP TABLE IF EXISTS `declaraimp`;

CREATE TABLE `declaraimp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anio` smallint(4) unsigned NOT NULL,
  `periodo` smallint(2) unsigned NOT NULL,
  `fecha` date NOT NULL,
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
  `valorcif` decimal(13,2) unsigned NOT NULL,
  `valorfob` decimal(13,2) unsigned NOT NULL,
  `peso_neto` decimal(13,2) unsigned NOT NULL,
  `arancel_pagado` decimal(13,2) unsigned NOT NULL,
  `valorarancel` decimal(13,2) unsigned NOT NULL,
  `porcentaje_arancel` decimal(13,2) unsigned NOT NULL,
  `cantidad` decimal(13,2) unsigned NOT NULL,
  `unidad` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `anio` (`anio`),
  KEY `id_empresa` (`id_empresa`),
  KEY `id_paisprocedencia` (`id_paisprocedencia`),
  KEY `id_posicion` (`id_posicion`),
  KEY `id_ciiu` (`id_ciiu`),
  KEY `id_deptorigen` (`id_deptorigen`),
  KEY `id_capitulo` (`id_capitulo`),
  KEY `id_partida` (`id_partida`),
  KEY `id_subpartida` (`id_subpartida`),
  KEY `id_capitulo_2` (`id_capitulo`,`id_partida`,`id_subpartida`),
  KEY `fecha` (`fecha`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
