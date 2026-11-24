<?php require_once('../Connections/qal3_db.php'); ?>
<?php require_once('../libreria.php'); ?>
<?php

if (isset($_REQUEST['putData'])) {

    mysqli_select_db($qal3_db, $database_qal3_db);

    $datos = json_decode($_REQUEST['putData']);
    $variables = '';
    $variables_valores = '';
    $valores = '';
    $variables_componentes = [];
    $valores_componentes = [];
    $pos_variables_componentes = 0;

    if (isset($datos->fk_tipos_botellas) > 0) {

        $array_campos = [
            'id_botellas',
            'fk_tipos_botellas',
            'n_botella',
            'n_certificado',
            'n_pr',
            'foto_nombre',
            'foto',
            'descripcion',
            'unidad',
            'fecha_caducidad',
            'fecha_ultima_revision',
            'presion_ultima_revision',
            'estado',
            'informacion',
            'observaciones',
            'componentes'
        ];

        $modificando = false;
        $id_botellas = '';
        foreach ($datos AS $ind => $dato) {
            if (in_array($ind, $array_campos)) {
                if ($ind == 'id_botellas') {
                    $id_botellas = $dato;
                    $modificando = ($id_botellas != '');
                } else {
                    if ($ind == 'componentes') {
                        //if (($ind == 'porcentaje') || ($ind == 'mg_nm3') || ($ind == 'ppm') || ($ind == 'incertidumbre')) {
                        $variables_componentes = "`porcentaje`,`mg_nm3`,`ppm`,`incertidumbre`";
                        foreach ($dato AS $com_ind => $array_valores) {
                            $valores_componentes[$com_ind] = '';
                            foreach ($array_valores AS $campo => $valor) {
                                $valores_componentes[$com_ind] .= ($valores_componentes[$com_ind] !== '') ? ',' : '';
                                $valores_componentes[$com_ind] .= ($valor === '') ? 'NULL' : stripslashes($valor);
                            }
                        }
                    } elseif ($ind == 'foto') {
                        // No hay que hacer nada
                    } else {
                        if ($variables != '') {
                            $variables .= ',';
                            $valores .= ',';
                            $variables_valores .= ',';
                        }
                        if (($ind == 'foto_nombre') && ($dato != '')) {
                            //$dato = 'files_upload/botellas/' . $dato;
                        }
                        $variables .= "`" . $ind . "`";
                        $valores .= "'" . (($dato) ? stripslashes($dato) : '') . "'";
                        $variables_valores .= "`" . $ind . "` = '" . stripslashes($dato) . "'";
                    }
                }
            }
        }

        if ($variables != '') {
            if ($modificando == false) {
                $updateSQL = "INSERT INTO botellas (" . $variables . ") VALUES (" . $valores . ") ";
            } else {
                $updateSQL = "UPDATE botellas SET " . $variables_valores . " WHERE id_botellas = '" . $id_botellas . "' ";
            }
            $Result = mysqli_query($qal3_db, $updateSQL) or die(mysqli_error($qal3_db) . ' (' . $updateSQL . ')');

            if ($Result) {
                if ($modificando == false) {
                    $id_insertado = mysqli_insert_id($qal3_db);
                } else {
                    $id_insertado = $id_botellas;
                }

                $respuesta_componentes = [];

                $deleteSQL = "DELETE FROM botellas_componentes WHERE fk_botellas = '" . $id_insertado . "' ";
                $Result = mysqli_query($qal3_db, $deleteSQL) or die(mysqli_error($qal3_db) . ' [' . $deleteSQL . ']');
                // Guardamos los componentes
                foreach ($valores_componentes as $ind_componente => $valores_campo) {
                    $updateSQL = "INSERT INTO botellas_componentes (fk_botellas, componente, " . $variables_componentes . ") VALUES (" . $id_insertado . ", '" . $ind_componente . "'," . $valores_campo . ") ";
                    $Result = mysqli_query($qal3_db, $updateSQL) or die(mysqli_error($qal3_db) . ' [' . $updateSQL . ']');

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