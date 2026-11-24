/*
SQLyog Ultimate v13.1.5  (64 bit)
MySQL - 10.3.14-MariaDB : Database - qal3_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`qal3_db` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `qal3_db`;

/*Table structure for table `analizadores_componentes` */

DROP TABLE IF EXISTS `analizadores_componentes`;

CREATE TABLE `analizadores_componentes` (
  `fk_analizadores` int(10) unsigned NOT NULL,
  `componente` varchar(15) NOT NULL,
  PRIMARY KEY (`fk_analizadores`,`componente`),
  CONSTRAINT `analizadores` FOREIGN KEY (`fk_analizadores`) REFERENCES `analizadores` (`id_analizadores`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `analizadores_componentes` */

insert  into `analizadores_componentes`(`fk_analizadores`,`componente`) values 
(1,'CO'),
(1,'HCL'),
(1,'NOX'),
(1,'O2'),
(1,'PARTICULAS'),
(1,'SO2'),
(2,'CO'),
(2,'HCL'),
(2,'NO2'),
(2,'NOX'),
(2,'TOC');

/*Table structure for table `botellas_componentes` */

DROP TABLE IF EXISTS `botellas_componentes`;

CREATE TABLE `botellas_componentes` (
  `fk_botellas` int(10) unsigned NOT NULL,
  `componente` varchar(15) NOT NULL,
  `porcentaje` float DEFAULT NULL,
  `mg_nm3` float DEFAULT NULL COMMENT 'SI es O2 o CO2 es %vol, y si no MG',
  `ppm` float DEFAULT NULL,
  `incertidumbre` float DEFAULT NULL,
  PRIMARY KEY (`fk_botellas`,`componente`),
  CONSTRAINT `botellas` FOREIGN KEY (`fk_botellas`) REFERENCES `botellas` (`id_botellas`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `botellas_componentes` */

insert  into `botellas_componentes`(`fk_botellas`,`componente`,`porcentaje`,`mg_nm3`,`ppm`,`incertidumbre`) values 
(1,'CO2',10,NULL,NULL,100),
(1,'NO2',NULL,120,58.451,200),
(1,'SO2',NULL,257.13,90,300),
(2,'HCL',NULL,120,73.665,1.25);

/*Table structure for table `tipos_botellas_componentes` */

DROP TABLE IF EXISTS `tipos_botellas_componentes`;

CREATE TABLE `tipos_botellas_componentes` (
  `fk_tipos_botellas` int(10) unsigned NOT NULL,
  `componente` varchar(15) NOT NULL,
  `tb_operador` float DEFAULT NULL,
  `tb_mg_nm3` float DEFAULT NULL,
  `tb_ppm` float DEFAULT NULL,
  PRIMARY KEY (`fk_tipos_botellas`,`componente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tipos_botellas_componentes` */

insert  into `tipos_botellas_componentes`(`fk_tipos_botellas`,`componente`,`tb_operador`,`tb_mg_nm3`,`tb_ppm`) values 
(1,'HCL',1.629,13.032,8),
(3,'NH3',0.758,15.918,21),
(4,'O2',1.428,2,2),
(5,'NO2',2.053,41.06,20),
(6,'C3H8',1.964,23.568,12),
(7,'NO',1.339,468.65,350),
(8,'SO2',2.857,257.13,90),
(9,'CO',1.25,125,100),
(13,'O2',1.428,10,10),
(15,'O2',1.428,10,10),
(16,'NO',1.339,66.95,50),
(17,'SO2',2.857,428.55,150),
(22,'CO2',1.3,18,0),
(22,'NO2',2.053,120,58.451),
(22,'SO2',2.857,26,20),
(23,'NO',1.339,300,0),
(23,'SO2',2.857,364,280);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
