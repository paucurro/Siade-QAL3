<?php require_once('../Connections/qal3_db.php'); ?>
<?php

if (isset($_REQUEST['id_analizadores'])) {

    mysqli_select_db($qal3_db, $database_qal3_db);

    $datos = $_REQUEST;
    //$datos = json_decode($_REQUEST['putData']);
    $variables = '';
    $variables_valores = '';
    $valores = '';
    $variables_componentes = [];
    $valores_componentes = [];
    $pos_variables_componentes = 0;

    $array_campos = [
        'id_analizadores',
        'tag',
        'linea',
        'tipo_analizador',
        'descripcion',
        'marca',
        'modelo',
        'extractivo',
        'senyal_salida',
        'control_emisiones',
        'tag_senyal_de_control',
        'correccion_humedad',
        'correccion_oxigeno',
        'fecha_instalacion',
        'fecha_ult_revision',
        'estado',
        'numero_serie',
        'frecuencia_verificacion',
        'intervalo_confianza',
        'observaciones',
        'componentes',
    ];

    if (count($datos) > 0) {

        $modificando = false;
        $id_analizadores = '';
        foreach ($datos AS $ind => $dato) {
            if (in_array($ind, $array_campos)) {
                if ($ind == 'id_analizadores') {
                    $id_analizadores = $dato;
                    $modificando = ($id_analizadores != '');
                } else {
                    if ($ind != 'componentes') {
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
                if (isset($datos['componentes'])) {
                    $variables_componentes = [];
                    $valores_campo = [];
                    foreach ($datos['componentes'] as $ind => $row) {
                        $valores_campo[$ind] = [];
                        foreach ($row as $field => $valor) {
                            if (!in_array($field, $variables_componentes)) $variables_componentes[] = $field;
                            $valores_campo[$ind][] = "'" . $valor . "'";
                        }
                        $valores_campo[$ind] = "(" . $id_insertado . "," . implode(",", $valores_campo[$ind]) . ")";
                    }

                    $variables_componentes = "`fk_analizadores`, `" . implode("`,`", $variables_componentes) ."`";
                    $valores_campo = implode(",", $valores_campo);

                    $updateSQL = "REPLACE INTO analizadores_componentes (" . $variables_componentes . ") VALUES " . $valores_campo . "; ";
                    $Result = mysqli_query($qal3_db, $updateSQL) or die(mysqli_error($qal3_db));

                    if (!$Result) {
                        $respuesta_componentes = ['resp' => 'ERROR', 'data' => 'Error DB: ' . mysqli_error($qal3_db)];
                    }

                    if (count($respuesta_componentes) > 0) {
                        $respuesta = ['resp' => 'ERROR', 'data' => 'Error al guardar componentes: ' . mysqli_error($qal3_db), 'resp_componentes' => $respuesta_componentes];
                    } else {
                        $respuesta = ['resp' => 'OK', 'data' => 'OK. Guardado id: ' . $id_insertado, 'resp_componentes' => $respuesta_componentes];
                    }
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