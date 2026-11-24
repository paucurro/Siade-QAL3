<?php require_once('../Connections/qal3_db.php'); ?>
<?php

if (isset($_REQUEST['id'])) {

    mysqli_select_db($qal3_db, $database_qal3_db);

    $query_componentes = "SELECT *  
        FROM tipos_botellas_componentes 
        WHERE fk_tipos_botellas = " . $_REQUEST['id'] . " ";
    $componentes = mysqli_query($qal3_db, $query_componentes) or die(mysqli_error($qal3_db));
    $row_componentes = mysqli_fetch_all($componentes, MYSQLI_ASSOC);

    if ($row_componentes !== false) {
        $respuesta = ['resp' => 'OK', 'data' => $row_componentes];
    } else {
        $respuesta = ['resp' => 'ERROR', 'data' => 'Faltan datos de la tabla'];
    }

} else {
    $respuesta = ['resp' => 'ERROR', 'data' => 'Faltan parámetros'];
}

echo json_encode($respuesta);