/*
SQLyog Community v12.01 (64 bit)
MySQL - 5.6.17 : Database - min_agricultura
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`min_agricultura` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `min_agricultura`;

/*Table structure for table `indicator` */

DROP TABLE IF EXISTS `indicator`;

CREATE TABLE `indicator` (
  `indicator_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indicator_name` varchar(255) NOT NULL,
  PRIMARY KEY (`indicator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `indicator` */

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_full_name` varchar(255) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` char(35) NOT NULL,
  `user_uinsert` int(10) unsigned NOT NULL,
  `user_finsert` datetime NOT NULL,
  `user_fupdate` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `user` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
