/*
SQLyog Ultimate v13.1.5  (64 bit)
MySQL - 10.4.13-MariaDB : Database - qal3_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
USE `qal3_db`;

/*Table structure for table `excel_a_generar` */

DROP TABLE IF EXISTS `excel_a_generar`;

CREATE TABLE `excel_a_generar` (
  `nombre_documento` varchar(400) COLLATE utf8_bin DEFAULT NULL,
  `row_pos` smallint(6) DEFAULT NULL,
  `col_pos` smallint(6) DEFAULT NULL,
  `valor` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `es_numerico` tinyint(1) DEFAULT 0,
  `procesado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `excel_a_generar` */

insert  into `excel_a_generar`(`nombre_documento`,`row_pos`,`col_pos`,`valor`,`es_numerico`,`procesado`) values 
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',1,13,'Fechas desde: 2021-01-01 hasta: 2021-12-31',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',7,10,'L1 PRINCIPAL',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',10,2,'01-AAN11-001',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',10,4,'Analizador multicomponente L1 Principal',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',10,10,'CO',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',12,2,'SICK',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',12,4,'MCS100E',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',12,7,'',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',12,10,'',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',15,2,'0 - 250 ',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',15,4,'50',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',15,7,'2',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',15,10,'',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',17,2,'2019-06-03 00:00:00',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',17,4,'0 – 10 mg/Nm3		',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',17,7,'ŷ = x',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',17,10,'Sira MC 040044/02	',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',19,2,'',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',19,4,'',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',19,7,'1.5393',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',19,10,'1.4983',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',22,2,'3.08',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',22,4,'4.62',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',22,7,'-3.08',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',22,10,'-4.62',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',25,2,'',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',25,4,'Aire cero (Instrumento)',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',25,7,'Botella Nitrogeno',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',25,10,'0,0',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',28,3,'2021-02-09',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',28,4,'admin admin',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',28,5,'Nitrogeno',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',28,6,'1',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',28,7,'2',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',28,8,'3',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',28,9,'Nm3',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',28,11,'NO',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',29,3,'2021-03-09',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',29,4,'admin admin',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',29,5,'Nitrogeno',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',29,6,'0',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',29,7,'0.05',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',29,8,'0.05',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',29,9,'mg_nm3',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',29,11,'NO',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',30,3,'2021-05-26',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',30,4,'Javier Sancho',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',30,5,'Nitrogeno',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',30,6,'0',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',30,7,'0.02',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',30,8,'0',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',30,9,'mg_nm3',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',30,11,'NO',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',65,2,'63.12',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',65,4,'64.61',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',65,7,'57.12',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',65,10,'55.63',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',68,2,'',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',68,4,'Nº Botella:  / Certificado: 040008161908',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',68,7,'MULTICOMPONENTE BAJO',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',68,10,'60.12',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',71,3,'2021-03-09',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',71,4,'admin admin',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',71,5,'800202',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',71,6,'60',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',71,7,'59.7',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',71,8,'59.7',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',71,9,'mg_nm3',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',71,11,'NO',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',72,3,'2021-05-26',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',72,4,'Javier Sancho',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',72,5,'34011',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',72,6,'60.12',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',72,7,'59.61',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',72,8,'0',1,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',72,9,'mg_nm3',0,0),
('informe_01-AAN11-001_L1_CO__2021-01-01__2021-12-31.xlsx',72,11,'NO',0,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
