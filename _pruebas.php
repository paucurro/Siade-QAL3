
CREATE TABLE qal3_db . analizadores(
    id_analizadores INT UNSIGNED NOT NULL AUTO_INCREMENT,

    tag
    linea
    tipo_analizador
    componente
    descripcion
    metodo_revision
    marca
    modelo
    extractivo
    rango_medida
    unidad
    incertidumbre_equipo
    funcu_calibracion
    señal_salida
    control_emisiones
    tag_senyal_de_control
    correccion_humedad
    correccion_oxigeno
    limite_alarma_dcs
    valor_limite_emision
    limite_legal_diario
    fecha_instalacion
    fecha_ult_revision
    estado
    observaciones

    PRIMARY KEY(id_analizadores)
) ENGINE = INNODB CHARSET = utf8 COLLATE = utf8_general_ci;



