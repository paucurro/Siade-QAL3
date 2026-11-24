<?php require_once('../Connections/qal3_db.php'); ?>
<?php

if (isset($_REQUEST['fk']) && isset($_REQUEST['file'])) {

    mysqli_select_db($qal3_db, $database_qal3_db);

    $datos = $_REQUEST;
    $respuesta = null;
    if (count($datos) > 0) {

        if (strpos($datos['file'], '/') > 0) {
            $vars = explode("/", $datos['file']);
        } else {
            $vars = explode("\\", $datos['file']);
        }

        if ($vars[0] == 'files_upload') {
            if ($vars[1] == ('analizador_' . $datos['fk'])) {
                $file = '../' . $datos['file'];
                if (file_exists($file)) {
                    unlink($file);
                    $respuesta = ['resp' => 'OK', 'data' => 'OK. Archivo ' . $datos['file'] . ' borrado'];
                } else {
                    $file = str_replace($vars[1], "analizador_temp", $file);
                    if (file_exists($file)) {
                        unlink($file);
                        $respuesta = ['resp' => 'OK', 'data' => 'OK. Archivo ' . $datos['file'] . ' borrado'];
                    } else {
                        $respuesta = ['resp' => 'OK', 'data' => 'El fichero no existe en la carpeta, se eliminará del registro.'];
                    }
                }
            }
        }

        if ($respuesta == null) {
            $respuesta = ['resp' => 'ERROR', 'data' => 'Faltan parámetros'];
        }

    } else {
        $respuesta = ['resp' => 'ERROR', 'data' => 'Faltan parámetros'];
    }


} else {
    $respuesta = ['resp' => 'ERROR', 'data' => 'Faltan parámetros'];
}

echo json_encode($respuesta);