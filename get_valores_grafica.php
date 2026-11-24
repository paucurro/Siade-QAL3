<?php require_once '_config.php'; ?>
<?php require_once 'Connections/qal3_db.php'; ?>
<?php

if ((isset($_REQUEST['variables'])) && (isset($_REQUEST['user']))) {

    $variables = $_REQUEST['variables'];
    $user = $_REQUEST['user'];

    $filtro_fecha = '';
    if (isset($_REQUEST['start'])) {
        $desde = date("Y-m-d 00:00:00", $_REQUEST['start']);
        $hasta = date("Y-m-d 23:59:00", $_REQUEST['end']);
        $filtro_fecha = " AND momento_valor BETWEEN '" . $desde . "' AND '" . $hasta . "'";
    } else {
        $desde = date("Y-m-d 00:00:00", time() - (7 * 86400));
        $hasta = date("Y-m-d H:i:s", time());
    }

    mysqli_select_db($qal3_db, $database_qal3_db);
    $query_tabla = "SELECT *, UNIX_TIMESTAMP(momento_valor) as momento FROM usuarios_" . $user . "_valores " .
        "LEFT JOIN puntos_valores ON puntos_valores.id_puntos_valores = usuarios_" . $user . "_valores.fk_puntos_valores " .
        "WHERE fk_puntos_valores IN (" . $variables . ")" . $filtro_fecha .
        "ORDER BY fk_puntos_valores, momento_valor ASC";
    $tabla = mysqli_query($qal3_db, $query_tabla);

    if ($tabla != false) {
        $arr_objetos = mysqli_fetch_all($tabla, MYSQLI_ASSOC);
        mysqli_free_result($tabla);

        $arr_datos = [];
        foreach ($arr_objetos AS $indice => $row) {
            if (!isset($arr_datos[$row['fk_puntos_valores']])) {
                $arr_datos[$row['fk_puntos_valores']] = ["name" => "", "data" => []];
                $arr_datos[$row['fk_puntos_valores']]['name'] = $row['nombre_objeto'];
            }
            $arr_datos[$row['fk_puntos_valores']]['data'][] = [intval($row['momento']) * 1000, floatval($row['valor'])];
        }


        $query_tabla = "TRUNCATE TABLE usuarios_" . $user . "_seleccionados; ";
        $Result1 = mysqli_query($qal3_db, $query_tabla);

        $puntos = explode(",", $variables);
        $query_tabla = "INSERT INTO usuarios_2_seleccionados (fk_puntos_valores, nombre_grupo) VALUES ";
        foreach ($puntos as $ind => $punto) {
            if ($ind > 0) $query_tabla .= ',';
            $query_tabla .= "(" . $punto . ", 'grupo 2') ";
        }
        $Result2 = mysqli_query($qal3_db, $query_tabla);

        //$respuesta = ['resp' => 'OK', 'data' => $arr_datos];
        echo json_encode($arr_datos);
        exit;

    } else {
        $respuesta = ['resp' => 'ERROR SQL', 'data' => mysqli_error($qal3_db) . " GET VALUE "];
    }

} else {
    $respuesta = ['resp' => 'ERROR', 'data' => 'Faltan parametros'];
}

echo json_encode($respuesta);

