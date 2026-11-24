<?php require_once('../Connections/qal3_db.php'); ?>
<?php require_once('../libreria.php'); ?>
<?php

if (isset($_REQUEST['fk_analizadores']) || isset($_REQUEST['id_qal1'])) {

    mysqli_select_db($qal3_db, $database_qal3_db);

    $datos = $_REQUEST;
    //$datos = json_decode($_REQUEST['putData']);
    $variables = '';
    $variables_valores = '';
    $valores = '';
    $variables_componentes = [];
    $valores_componentes = [];
    $pos_variables_componentes = 0;

    // Definimos los campos para que no
    // se añadan otros que NO estén definidos en la tabla
    $array_campos = [
        'id_qal1',
        'fecha_qal1',
        'fecha_qal1_mod',
        'fk_analizadores',
        'componente_qal1',
        'documento_qal1',
        'certificado',
        'ssam_cero',
        'ssam_rango'
    ];

    if (count($datos) > 0) {

        if (isset($datos['del']) && ($datos['del'] == 1)) {

            $row_qal1 = setSqlDB("SELECT * from qal1 WHERE id_qal1 = " . $datos['id_qal1'] . " LIMIT 1");
            if ($row_qal1 !== false) {
                $resp_file = '';
                if (isset($row_qal1['documento'])) {
                    $fichero = $row_qal1['documento'];
                    if ($fichero != '') {
                        $files = [];
                        if ($fichero != '') {
                            $files = explode(",", $fichero);
                        }
                        if (count($files) > 0) {
                            foreach ($files as $file) {
                                if (file_exists('../files_upload/' . $file)) {
                                    unlink($file);
                                    $resp_file .= 'OK. Archivo ' . $datos['file'] . ' borrado';
                                } else {
                                    $resp_file .= 'El fichero no existe en la carpeta, se eliminará del registro.';
                                }
                            }
                        }
                        // Borramos el directorio
                        unlink('../files_upload/analizador_' . $datos['id_qal1']);
                    }
                }

                $updateSQL = "DELETE FROM qal1 WHERE id_qal1 = '" . $datos['id_qal1'] . "' ";
                $Result = mysqli_query($qal3_db, $updateSQL);

                if (!$Result) {
                    $respuesta = ['resp' => 'ERROR', 'data' => 'Error al eliminar QAL1: ' . mysqli_error($qal3_db), 'files' => $resp_file];
                } else {
                    $respuesta = ['resp' => 'OK', 'data' => 'OK. Eliminado correctamente.', 'files' => $resp_file];
                }
            }

        } else {

            $modificando = false;
            $id_qal1 = '';
            foreach ($datos as $ind => $dato) {
                if (in_array($ind, $array_campos)) {
                    if ($ind == 'id_qal1') {
                        $id_qal1 = $dato;
                        $modificando = ($id_qal1 != '');
                    } else {
                        if ($ind == 'documento_qal1') {
                            $ficheros = explode(",", $dato);
                            foreach ($ficheros as $fichero) {
                                $fichero =   '../' . $fichero;
                                if (!file_exists($fichero)) {
                                    $fichero_temp = str_replace("analizador_" . $datos['fk_analizadores'], "analizador_temp", $fichero);
                                    if (file_exists($fichero_temp)) {
                                        $directorio = "../files_upload/analizador_" . $datos['fk_analizadores'];
                                        if (!is_dir($directorio)) {
                                            mkdir($directorio, 0755, true);
                                        }
                                        $result_file = rename($fichero_temp, $fichero);
                                        if (!$result_file) {
                                            echo json_encode(['resp' => 'ERROR', 'data' => 'Error al mover el archivo ' . $fichero_temp . ' a ' . $fichero . '.']);
                                            exit;
                                        }
                                    } else {
                                        echo json_encode(['resp' => 'ERROR', 'data' => 'Error al buscar el archivo ' . $fichero . '. No se encuentra en el directorio TEMP.']);
                                        exit;
                                    }
                                }
                            }
                        }
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
            }

            if ($variables != '') {
                if ($modificando == false) {
                    $updateSQL = "INSERT INTO qal1 (" . $variables . ") VALUES (" . $valores . ") ";
                } else {
                    $updateSQL = "UPDATE qal1 SET " . $variables_valores . " WHERE id_qal1 = '" . $id_qal1 . "' ";
                }
                $Result = mysqli_query($qal3_db, $updateSQL) or die(mysqli_error($qal3_db));

                if (!$Result) {
                    $respuesta = ['resp' => 'ERROR', 'data' => 'Error al guardar componentes: ' . mysqli_error($qal3_db)];
                } else {
                    if ($modificando == false) {
                        $id_fk_analizadores = $datos['fk_analizadores'];
                        // Vamos a renombrar la carpeta analizador_temp al ID creado
                    }
                    $respuesta = ['resp' => 'OK', 'data' => 'OK. Guardado correctamente.'];
                }

            } else {
                $respuesta = ['resp' => 'ERROR', 'data' => 'Faltan datos de la tabla'];
            }

        }
    }

} else {
    $respuesta = ['resp' => 'ERROR', 'data' => 'Faltan parámetros'];
}

echo json_encode($respuesta);