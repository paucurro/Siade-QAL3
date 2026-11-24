<?php

if (!isset($_SESSION)) {
    session_start();
}

//inicio la sesi&oacute;n
if (!isset($_SESSION['config'])) {
    $_SESSION['config'] = new config;
    $_SESSION['config']->opm = 0;
    $_SESSION['config']->solapa = 0;
    $_SESSION['config']->desde_fecha = '';
    $_SESSION['config']->hasta_fecha = '';
    $_SESSION['config']->busca_turno = -1;
    $_SESSION['config']->busca_lineas = -1;
    $_SESSION['config']->pagina = 0;
}

if (isset($_GET['opm']))
    $_SESSION['config']->opm = $_GET['opm'];
else
    if (isset($opcion_directa))
        $_SESSION['config']->opm = $opcion_directa;


if (isset($_REQUEST['desde_fecha'])) $_SESSION['config']->desde_fecha = $_REQUEST['desde_fecha'];
if (isset($_REQUEST['hasta_fecha'])) $_SESSION['config']->hasta_fecha = $_REQUEST['hasta_fecha'];
if (isset($_REQUEST['busca_turno'])) $_SESSION['config']->busca_turno = $_REQUEST['busca_turno'];
if (isset($_REQUEST['busca_lineas'])) $_SESSION['config']->busca_lineas = $_REQUEST['busca_lineas'];
if (isset($_REQUEST['pagina'])) $_SESSION['config']->pagina = $_REQUEST['pagina'];

if (!isset($_SESSION['SoloContenido'])) {
    $_SESSION['SoloContenido'] = false;
}

if (isset($_REQUEST['SoloContenido'])) {
    if ($_REQUEST['SoloContenido'] == 'Cierto1') {
        $_SESSION['SoloContenido'] = true;
    } else {
        $_SESSION['SoloContenido'] = false;
    }
}

//inicio la sesi&oacute;n
if (!isset($_SESSION['filtro'])) {
    $_SESSION['filtro'] = new filtro;
    $_SESSION['filtro']->id_variable = [];
    $_SESSION['filtro']->linea = [];
    $_SESSION['filtro']->desde_fecha = NULL;
    $_SESSION['filtro']->hasta_fecha = NULL;
}

$crea_nuevo = false;

if (!isset($_SESSION['selector_dcs'])) {
    $_SESSION['selector_dcs'] = new selector_dcs;
    $_SESSION['selector_dcs']->desde_fecha = NULL;
    $_SESSION['selector_dcs']->fecha = NULL;
    $_SESSION['selector_dcs']->desde_hora = NULL;
    $_SESSION['selector_dcs']->hora = NULL;
    $_SESSION['selector_dcs']->hasta_fecha = NULL;
    $_SESSION['selector_dcs']->hasta_hora = NULL;
    $_SESSION['selector_dcs']->select_media = 0;
    $_SESSION['selector_dcs']->campo_val_sel = '';
    $_SESSION['selector_dcs']->consulta_dcs = NULL;
    $_SESSION['selector_dcs']->linea_sesion = NULL;
}

if ((stripos($_SERVER['PHP_SELF'], "dcs_select.php")) || (stripos($_SERVER['PHP_SELF'], "dcs_lista.php"))) {
    if (!isset($_SESSION['selector_dcs']->linea_sesion) || ($_SESSION['selector_dcs']->linea_sesion != "12")) {
        $crea_nuevo = true;
        $_SESSION['selector_dcs']->linea_sesion = "12";
    }
}

if ((stripos($_SERVER['PHP_SELF'], "dcs_select_l34.php")) || (stripos($_SERVER['PHP_SELF'], "dcs_lista34.php"))) {
    if (!isset($_SESSION['selector_dcs']->linea_sesion) || ($_SESSION['selector_dcs']->linea_sesion != "34")) {
        $crea_nuevo = true;
        $_SESSION['selector_dcs']->linea_sesion = "34";
    }
}

//inicio la sesi&oacute;n
if ((!isset($_SESSION['filtro_dcs'])) || ($crea_nuevo == true)) {
    $_SESSION['filtro_dcs'] = [];
}

if ((!isset($_SESSION['formula_dcs'])) || ($crea_nuevo == true)) {
    $_SESSION['formula_dcs'] = [];
}

/* if (!isset($_SESSION['escala_dcs'])) {
	$_SESSION['escala_dcs'] = [];
} */

if (!isset($_SESSION['array_datos'])) {
    $_SESSION['array_datos'] = new array_datos;
    $_SESSION['array_datos']->matriz_datos = [];
    $_SESSION['array_datos']->totalRows_lista_datos = 0;
}

if (!isset($_SESSION['ultimo_filtro_sap'])) {
    $_SESSION['ultimo_filtro_sap'] = new datos_sap;
    $_SESSION['ultimo_filtro_sap']->sel_bascula = [];
    $_SESSION['ultimo_filtro_sap']->sel_planta = [];
    $_SESSION['ultimo_filtro_sap']->sel_movimiento = "";
    $_SESSION['ultimo_filtro_sap']->sel_destino = [];
    $_SESSION['ultimo_filtro_sap']->sel_material = [];
    $_SESSION['ultimo_filtro_sap']->sel_procedencia = [];
    $_SESSION['ultimo_filtro_sap']->sel_matricula = [];
    $_SESSION['ultimo_filtro_sap']->sel_cler = [];
}

if (!isset($_SESSION['ultimo_filtro_ptam'])) {
    $_SESSION['ultimo_filtro_ptam'] = new datos_ptam;
    $_SESSION['ultimo_filtro_ptam']->sel_equipo = [];
}

if (isset($_SESSION['ULTIMO_MOVIMIENTO'])) {
    if ((time() - $_SESSION['ULTIMO_MOVIMIENTO']) > 3600) { // 1800 SEGUNDOS sin actividad: logout (30 minutos)
        //session_unset();     // unset $_SESSION variable for the run-time
        //session_destroy();   // destroy session data in storage
        header(sprintf("Location: logout.php"));
        exit;
    }
}
$_SESSION['ULTIMO_MOVIMIENTO'] = time(); // update last activity time stamp

if (!isset($_SESSION['ULTIMA_OPCION'])) {
    $_SESSION['ULTIMA_OPCION'] = '';
    $_SESSION['ULTIMO_RECURSO_COMPROBADO'] = '';
}

$cadenas_medias = ['Auto', 'Segundos', 'Minutos', 'Horas', 'Días', 'Todos'];

//,,,RendimientoBrutoLCV

$datos_dcs_adicionales = ['pesadas_ru_1' => 'Pesadas RU', 'pesadas_ru_2' => 'Pesadas RU', 'pesadas_ru_3' => 'Pesadas RU', 'pesadas_ru_4' => 'Pesadas RU', 'caudal_masico_1' => 'Pesadas RU', 'caudal_masico_2' => 'Pesadas RU', 'caudal_masico_3' => 'Pesadas RU', 'caudal_masico_4' => 'Pesadas RU', 'contadores_gas_410' => 'Contadores', 'contadores_gas_411' => 'Contadores', 'gis_ANILLO_exp' => 'Anillo Exportacion', 'gis_ANILLO_imp' => 'Anillo Importacion', 'gis_APIRE_BRUTA' => 'Apire bruta', 'gis_APIRE_imp' => 'Apire Importacion', 'gis_APIRE_NETA' => 'Apire Neta', 'gis_PIRE_BRUTA' => 'Pire Bruta', 'gis_PIRE_imp' => 'Pire Importacion', 'gis_PIRE_NETA' => 'Pire Neta', 'energia_PCI' => 'Energia - PCI', 'energia_R1' => 'Energia - R1', 'energia_RendimientoBruto' => 'Energia - Rendimiento Bruto', 'energia_LCV' => 'Energia - LCV', 'energia_EficienciaEnergetica' => 'Energia - R_LCV', 'energia_RendimientoBrutoLCV' => 'Energia - RD_LCV'];

$datos_dcs_adicionales_txt = ['pesadas_ru_1' => 'Pesadas L1', 'pesadas_ru_2' => 'Pesadas L2', 'pesadas_ru_3' => 'Pesadas L3', 'pesadas_ru_4' => 'Pesadas L4', 'caudal_masico_1' => 'Caudal Masico L1', 'caudal_masico_2' => 'Caudal Masico L2', 'caudal_masico_3' => 'Caudal Masico L3', 'caudal_masico_4' => 'Caudal Masico L4', 'contadores_gas_410' => 'Contadores Gas L12', 'contadores_gas_411' => 'Contadores Gas L34', 'gis_ANILLO_exp' => 'Anillo Exportacion', 'gis_ANILLO_imp' => 'Anillo Importacion', 'gis_APIRE_BRUTA' => 'Apire bruta', 'gis_APIRE_imp' => 'Apire Importacion', 'gis_APIRE_NETA' => 'Apire Neta', 'gis_PIRE_BRUTA' => 'Pire Bruta', 'gis_PIRE_imp' => 'Pire Importacion', 'gis_PIRE_NETA' => 'Pire Neta', 'energia_PCI' => 'Energia - PCI', 'energia_R1' => 'Energia - R1', 'energia_RendimientoBruto' => 'Energia - Rendimiento Bruto', 'energia_LCV' => 'Energia - LCV', 'energia_EficienciaEnergetica' => 'Energia - R_LCV', 'energia_RendimientoBrutoLCV' => 'Energia - RD_LCV'];

$datos_dcs_formula_txt = ['A9001' => 'Pesadas L1', 'A9002' => 'Pesadas L2', 'A9003' => 'Pesadas L3', 'A9004' => 'Pesadas L4', 'A9101' => 'Caudal Masico L1', 'A9102' => 'Caudal Masico L2', 'A9103' => 'Caudal Masico L3', 'A9104' => 'Caudal Masico L4', 'A9201' => 'Contadores Gas L12', 'A9202' => 'Contadores Gas L34', 'A9301' => 'Anillo Exportacion', 'A9302' => 'Anillo Importacion', 'A9303' => 'Apire bruta', 'A9304' => 'Apire Importacion', 'A9305' => 'Apire Neta', 'A9306' => 'Pire Bruta', 'A9307' => 'Pire Importacion', 'A9308' => 'Pire Neta', 'A9500' => 'Energia - PCI', 'A9501' => 'Energia - R1', 'A9502' => 'Energia - Rendimiento Bruto', 'A9503' => 'Energia - LCV', 'A9504' => 'Energia - R_LCV', 'A9505' => 'Energia - RD_LCV'];

$datos_dcs_escala = ['pesadas_ru_1' => 'Kilos', 'pesadas_ru_2' => 'Kilos', 'pesadas_ru_3' => 'Kilos', 'pesadas_ru_4' => 'Kilos', 'caudal_masico_1' => 'Kcal/Kg', 'caudal_masico_2' => 'Kcal/Kg', 'caudal_masico_3' => 'Kcal/Kg', 'caudal_masico_4' => 'Kcal/Kg', 'contadores_gas_410' => 'VnH [Nm3]', 'contadores_gas_411' => 'VnH [Nm3]', 'gis_ANILLO_exp' => 'Kw', 'gis_ANILLO_imp' => 'Kw', 'gis_APIRE_BRUTA' => 'Kw', 'gis_APIRE_imp' => 'Kw', 'gis_APIRE_NETA' => 'Kw', 'gis_PIRE_BRUTA' => 'Kw', 'gis_PIRE_imp' => 'Kw', 'gis_PIRE_NETA' => 'Kw', 'energia_PCI' => '', 'energia_R1' => '', 'energia_RendimientoBruto' => '%', 'energia_LCV' => '', 'energia_EficienciaEnergetica' => '', 'energia_RendimientoBrutoLCV' => '%'];

$datos_dcs_gis = ['gis_ANILLO_exp' => 'Anillo Exportacion', 'gis_ANILLO_imp' => 'Anillo Importacion', 'gis_APIRE_BRUTA' => 'Apire bruta', 'gis_APIRE_imp' => 'Apire Importacion', 'gis_APIRE_NETA' => 'Apire Neta', 'gis_PIRE_BRUTA' => 'Pire Bruta', 'gis_PIRE_imp' => 'Pire Importacion', 'gis_PIRE_NETA' => 'Pire Neta'];

$datos_dcs_energia = ['energia_PCI' => 'Energia - PCI', 'energia_R1' => 'Energia - R1', 'energia_RendimientoBruto' => 'Energia - Rendimiento Bruto', 'energia_LCV' => 'Energia - LCV', 'energia_EficienciaEnergetica' => 'Energia - R_LCV', 'energia_RendimientoBrutoLCV' => 'Energia - RD_LCV'];

