/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

USE `qal3_db`;

/* Alter table in target */
ALTER TABLE `analizadores`
    CHANGE `senyal_salida` `senyal_salida` varchar(10)  COLLATE utf8_general_ci NULL after `extractivo` ,
    CHANGE `fecha_instalacion` `fecha_instalacion` datetime   NULL after `correccion_oxigeno` ,
    DROP COLUMN `limite_legal_diario` ,
    DROP COLUMN `rango_medida` ,
    DROP COLUMN `unidad` ,
    DROP COLUMN `incertidumbre_equipo` ,
    DROP COLUMN `func_calibracion` ,
    DROP COLUMN `limite_alarma_dcs` ,
    DROP COLUMN `valor_limite_emision` ;

/* Alter table in target */
ALTER TABLE `analizadores_componentes`
    ADD COLUMN `func_calibracion` varchar(10)  COLLATE utf8_general_ci NULL after `componente` ,
    ADD COLUMN `limite_alarma_dcs` varchar(10)  COLLATE utf8_general_ci NULL after `func_calibracion` ,
    ADD COLUMN `valor_limite_emision` varchar(10)  COLLATE utf8_general_ci NULL after `limite_alarma_dcs` ,
    ADD COLUMN `limite_legal_diario` varchar(10)  COLLATE utf8_general_ci NULL after `valor_limite_emision` ,
    ADD COLUMN `rango_medida` varchar(10)  COLLATE utf8_general_ci NULL after `limite_legal_diario` ,
    ADD COLUMN `incertidumbre_equipo` varchar(10)  COLLATE utf8_general_ci NULL after `rango_medida` ,
    ADD COLUMN `unidad` varchar(10)  COLLATE utf8_general_ci NULL after `incertidumbre_equipo` ;
ALTER TABLE `analizadores_componentes`
    ADD CONSTRAINT `fk_analizadores_componentes`
        FOREIGN KEY (`fk_analizadores`) REFERENCES `analizadores` (`id_analizadores`) ON DELETE CASCADE ON UPDATE CASCADE ;


/* Alter table in target */
ALTER TABLE `verificaciones_cero`
    DROP KEY `fk_botellas_cero` ,
    DROP FOREIGN KEY `fk_botellas_cero`  ;

/* Alter table in target */
ALTER TABLE `verificaciones_rango`
    DROP KEY `fk_botellas_rango` ,
    DROP FOREIGN KEY `fk_botellas_rango`  ;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
