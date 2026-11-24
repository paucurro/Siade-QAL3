<?php require_once('../Connections/qal3_db.php'); ?>
<?php

if (isset($_FILES['upload_doc']) && ($_FILES['upload_doc'] != '')) {

    mysqli_select_db($qal3_db, $database_qal3_db);

    $datos = $_REQUEST;
    $fichero = $_FILES['upload_doc'];

    //$datos = json_decode($_REQUEST['putData']);
    $variables = '';
    $variables_valores = '';
    $valores = '';
    $variables_componentes = [];
    $valores_componentes = [];
    $pos_variables_componentes = 0;

    if (count($datos) > 0) {

        $modificando = false;
        $id_analizadores = '';
        foreach ($datos AS $ind => $dato) {
            if ($ind == 'id_analizadores') {
                $id_analizadores = $dato;
                $modificando = ($id_analizadores != '');
            }
            if ($ind == 'componentes') {
                foreach ($dato as $comp) {
                    $variables_componentes = "`fk_analizadores`,`componente`";
                    $valores_componentes[] = "'" . stripslashes($comp) . "'";
                }
            } else {
                if ($variables != '') {
                    $variables .= ',';
                    $valores .= ',';
                    $variables_valores .= ',';
                }
                $variables .= "`" . $ind . "`";
                $valores .= "'" . stripslashes($dato) . "'";
                $variables_valores .= "`" . $ind . "` = '" . stripslashes($dato) . "'";
            }
        }

        if ($variables != '') {
            if ($modificando == false) {
                $updateSQL = "INSERT INTO analizadores (" . $variables . ") VALUES (" . $valores . ") ";
            } else {
                $updateSQL = "UPDATE analizadores SET " . $variables_valores . " WHERE id_analizadores = '" . $id_analizadores . "' ";
                $id_insertado = $id_analizadores;
            }
            $Result = mysqli_query($qal3_db, $updateSQL) or die(mysqli_error($qal3_db));

            if ($Result) {
                if ($modificando == false) {
                    $id_insertado = mysqli_insert_id($qal3_db);
                }
                $respuesta_componentes = [];

                $updateSQL = "DELETE FROM analizadores_componentes WHERE fk_analizadores = " . $id_insertado . "; ";
                $Result = mysqli_query($qal3_db, $updateSQL) or die(mysqli_error($qal3_db));

                // Guardamos los componentes
                foreach ($valores_componentes as $ind_componente => $valores_campo) {

                    $updateSQL = "INSERT INTO analizadores_componentes (" . $variables_componentes . ") VALUES (" . $id_insertado . "," . $valores_campo . "); ";
                    $Result = mysqli_query($qal3_db, $updateSQL) or die(mysqli_error($qal3_db));

                    if (!$Result) {
                        $respuesta_componentes = ['resp' => 'ERROR', 'data' => 'Error DB: ' . mysqli_error($qal3_db)];
                        continue;
                    }
                }

                if (count($respuesta_componentes) > 0) {
                    $respuesta = ['resp' => 'ERROR', 'data' => 'Error al guardar componentes: ' . mysqli_error($qal3_db), 'resp_componentes' => $respuesta_componentes];
                } else {
                    $respuesta = ['resp' => 'OK', 'data' => 'OK. Guardado id: ' . $id_insertado, 'resp_componentes' => $respuesta_componentes];
                }

            } else {
                $respuesta = ['resp' => 'ERROR', 'data' => 'Error DB: ' . mysqli_error($qal3_db)];
            }

        } else {
            $respuesta = ['resp' => 'ERROR', 'data' => 'Faltan datos de la tabla'];
        }
    }


} else {
    $respuesta = ['resp' => 'ERROR', 'data' => 'Faltan parámetros'];
}

echo json_encode($respuesta);