<?php require_once '_config.php'; ?>
<?php require_once 'Connections/qal3_db.php'; ?>
<?php

if (isset($_REQUEST['pantalla'])) {

    mysqli_select_db($qal3_db, $database_qal3_db);
    $query_tabla = "SELECT * FROM objetos " .
        "LEFT JOIN pantallas_objetos ON pantallas_objetos.fk_objetos = objetos.id_objetos " .
        "LEFT JOIN lista_puntos ON lista_puntos.fk_objetos = objetos.id_objetos " .
        "WHERE pantallas_objetos.fk_pantallas = '" . $_REQUEST['pantalla'] . "'";
    $tabla = mysqli_query($qal3_db, $query_tabla);

    if ($tabla != false) {
        $arr_objetos = mysqli_fetch_all($tabla, MYSQLI_ASSOC);
        mysqli_free_result($tabla);

        $respuesta = ['resp' => 'OK', 'data' => $arr_objetos];

    } else {
        $respuesta = ['resp' => 'ERROR SQL', 'data' => mysqli_error($qal3_db) . " GET VALUE "];
    }

} else {
    $respuesta = ['resp' => 'ERROR', 'data' => 'Faltan parametros'];
}

echo json_encode($respuesta);

