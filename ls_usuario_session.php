<?php

class datos_usuarios
{
    var $row_usuarios;
    var $instalaciones;
    var $instalacion_sel;
    var $array_permisos;
}


//inicio la sesi&oacute;n
class config
{
    var $opm;
    var $solapa;
    var $desde_fecha;
    var $hasta_fecha;
    var $busca_turno;
    var $busca_lineas;
    var $pagina;
}

//inicio la sesi&oacute;n
class filtro
{
    var $id_variable;
    var $linea;
    var $desde_fecha;
    var $hasta_fecha;
}

//inicio la sesi&oacute;n
class filtro_dcs
{
    var $id_variable;
    var $linea;
    var $color_escala;
    var $linea_escala;
    var $desde_escala;
    var $hasta_escala;
    var $unidades_escala;
    var $grupo_de_muestreo;
    var $off_escalas;
    var $agrupar;
    var $valor_media;
    var $unidades_media;
}

class formula_dcs
{
    var $id_variables; // Esto es un array de ID
    var $titulo;
    var $formula;
    var $color_escala;
    var $desde_escala;
    var $hasta_escala;
    var $unidades_escala;
    var $off_escalas;
    var $valor_media;
    var $unidades_media;
}

class selector_dcs
{
    var $fecha;
    var $hora;
    var $desde_fecha;
    var $hasta_fecha;
    var $desde_hora;
    var $hasta_hora;
    var $desde_fecha_mostrada;
    var $hasta_fecha_mostrada;
    var $select_media;
    var $campo_val_sel;
    var $consulta_dcs;
    var $linea_sesion;
}

/* class escala_dcs{
	var $color_escala;
	var $linea_escala;
	var $desde_escala;
	var $hasta_escala;
	var $unidades_escala;
} */

class array_datos
{
    var $matriz_datos;
    var $totalRows_lista_datos;
}

class datos_sap
{
    var $sel_bascula;
    var $sel_planta;
    var $sel_movimiento;
    var $sel_destino;
    var $sel_material;
    var $sel_procedencia;
    var $sel_matricula;
    var $sel_cler;
}

class datos_ptam
{
    var $sel_equipo;
}

