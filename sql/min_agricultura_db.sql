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
CREATE DATABASE /*!32312 IF NOT EXISTS*/`min_agricultura` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `min_agricultura`;

/*Table structure for table `category_menu` */

DROP TABLE IF EXISTS `category_menu`;

CREATE TABLE `category_menu` (
  `category_menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_menu_name` varchar(45) NOT NULL,
  `category_menu_order` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`category_menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `category_menu` */

/*Table structure for table `indicator` */

DROP TABLE IF EXISTS `indicator`;

CREATE TABLE `indicator` (
  `indicator_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `indicator_name` varchar(255) NOT NULL,
  PRIMARY KEY (`indicator_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `indicator` */

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `menu` */

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `permissions` */

/*Table structure for table `profile` */

DROP TABLE IF EXISTS `profile`;

CREATE TABLE `profile` (
  `profile_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(45) NOT NULL,
  PRIMARY KEY (`profile_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `profile` */

insert  into `profile`(`profile_id`,`profile_name`) values (1,'Admin'),(2,'User');

/*Table structure for table `session` */

DROP TABLE IF EXISTS `session`;

CREATE TABLE `session` (
  `session_user_id` int(10) unsigned NOT NULL,
  `session_php_id` varchar(45) NOT NULL,
  `session_date` datetime NOT NULL,
  `session_active` enum('0','1') NOT NULL,
  PRIMARY KEY (`session_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `session` */

insert  into `session`(`session_user_id`,`session_php_id`,`session_date`,`session_active`) values (1,'25trnmt5fflnci083kkgqnaah0','2014-11-11 03:48:05','1');

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
  `user_fupdate` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`user_id`,`user_full_name`,`user_email`,`user_password`,`user_active`,`user_profile_id`,`user_uinsert`,`user_finsert`,`user_fupdate`) values (1,'FABIAN SIATAMA','fsiatama@sicex.com','202cb962ac59075b964b07152d234b70','1',1,1,'2014-11-07 10:44:48','0000-00-00 00:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
