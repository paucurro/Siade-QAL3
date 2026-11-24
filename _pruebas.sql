
CREATE TABLE qal3_db . analizadores(
    id_analizadores INT UNSIGNED NOT NULL AUTO_INCREMENT,
    tag INT UNSIGNED NOT NULL,
    linea VARCHAR(4),
    tipo_analizador VARCHAR (15),
    -- componente COMPONENTES
    descripcion VARCHAR(100),
    fk_botellas INT UNSIGNED DEFAULT NULL,
    marca VARCHAR(100),
    modelo VARCHAR(100),
    extractivo ENUM ('No', 'Si'),
    rango_medida VARCHAR(10),
    unidad VARCHAR(10),
    incertidumbre_equipo smallint(2),
    funcu_calibracion VARCHAR(10),
    senyal_salida VARCHAR(10),
    control_emisiones ENUM ('No', 'Si'),
    tag_senyal_de_control INT UNSIGNED DEFAULT NULL,
    correccion_humedad ENUM ('No', 'Si'),
    correccion_oxigeno ENUM ('No', 'Si'),
    limite_alarma_dcs VARCHAR(10),
    valor_limite_emision VARCHAR(10),
    limite_legal_diario VARCHAR(10),
    fecha_instalacion DATETIME,
    fecha_ult_revision DATETIME,
    estado ENUM('OPERATIVO', 'MANTENIMIENTO','AVERIADO','INVENTARIO','RETIRADO'),
    observaciones LONGTEXT,
    PRIMARY KEY(id_analizadores)
) ENGINE = INNODB CHARSET = utf8 COLLATE = utf8_general_ci;



