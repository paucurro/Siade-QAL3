<?php require_once('../Connections/qal3_db.php'); ?>
<?php

if ((isset($_REQUEST['id'])) && ($_REQUEST['id'] != "")) {

    mysqli_select_db($qal3_db, $database_qal3_db);
    $selectSQL = "DELETE FROM analizadores WHERE id_analizadores='" . $_REQUEST['id'] . "';";
    $Result = mysqli_query($qal3_db, $selectSQL) or die(mysqli_error($qal3_db));

    if ($Result) {
        $deleteSQL = "DELETE FROM analizadores_componentes WHERE fk_analizadores ='" . $_REQUEST['id'] . "';";
        mysqli_select_db($qal3_db, $database_qal3_db);
        $Result1 = mysqli_query($qal3_db, $deleteSQL) or die(mysqli_error($qal3_db));

        if ($Result1)
            $resultado = ["resp" => "OK"];
        else
            $resultado = ["resp" => "ERROR", "data" => "Error: " . $deleteSQL];
    } else {
        $resultado = ["resp" => "ERROR", "data" => "No puede eliminar este analizador."];
    }
} else {
    $resultado = ["resp" => "ERROR", "data" => "Faltan parametros."];
}

echo json_encode($resultado);
