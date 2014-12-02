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

/*Table structure for table `tipo_indicador` */

DROP TABLE IF EXISTS `tipo_indicador`;

CREATE TABLE `tipo_indicador` (
  `tipo_indicador_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_indicador_nombre` varchar(100) NOT NULL,
  `tipo_indicador_abrev` varchar(10) NOT NULL,
  `tipo_indicador_activador` enum('precio','volumen') NOT NULL,
  `tipo_indicador_calculo` text NOT NULL,
  `tipo_indicador_definicion` text NOT NULL,
  PRIMARY KEY (`tipo_indicador_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_indicador` */

insert  into `tipo_indicador`(`tipo_indicador_id`,`tipo_indicador_nombre`,`tipo_indicador_abrev`,`tipo_indicador_activador`,`tipo_indicador_calculo`,`tipo_indicador_definicion`) values (1,'Balanza comercial Relativa ','BCt','precio','BCt= (Xij-Mij) / (Xij+Mij), \r\nDonde Xij = Exportaciones de un producto i por un país j,\r\nMij = Importaciones de un producto i a un país j.\r\n','Mide la relación entre la balanza comercial neta y flujo total de comercio de un producto determinado.\r\n\r\n.'),(2,'Balanza comercial','BC','precio','BC= ( Xij - Mij)\r\nDonde Xij = Exportaciones de un producto i por un país j,\r\nMij = Importaciones de un producto i a un país j.\r\n','La balanza comercial es la diferencia que existe entre el total de las exportaciones menos el total de las importaciones de un país. \r\n'),(3,'Variación de la Balanza Comercial','%BCt','precio','%BCt= (Xijt-Mijtp2) - (Xijt-Mijt-p1) /(Xijt-Mijt-p1)\r\nDonde Xijt = Exportaciones de un producto i por un país j en un periodo t+1, \r\nMij = Importaciones de un producto i a un país j en un periodo t.','Se define como indicador para establecer el crecimiento o decrecimiento del la balanza comercial antes y después de la firma de un TLC o en cualquier periodo de tiempo.'),(4,'Concentración de la Oferta Exportable  Agropecuaria y Agroindustrial de Colombia por Socio comercial','Oferta Exp','precio','Número de productos que representan el 80%  de las exportaciones agropecuarios al mercado de un socio comercial.\r\nIdentificar los productos que concentran el 80% de las exportaciones agropecuarias y agroindustriales de Colombia a un mercado de destino. ','Mide la concentración de la oferta exportable agropecuaria de Colombia al mercado de un socio comercial en un número de productos determinado.');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
