<?php require_once('../Connections/qal3_db.php'); ?>
<?php require_once('../libreria.php'); ?>
<?php

if (isset($_REQUEST['fk_analizadores']) || isset($_REQUEST['id_verificaciones_cero'])) {

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
        'id_verificaciones_cero',
        'fecha',
        'fk_analizadores',
        'fk_botellas',
        'componente',
        'fk_qal1',
        'fk_qal2',
        'fk_usuarios',
        'valor_patron_referencia',
        'valor_lectura_real',
        'valor_calibrado',
        'unidad_medida',
        'ajuste_analizador',
        'limite_alarma_superior',
        'limite_actuacion_superior',
        'limite_alarma_inferior',
        'limite_actuacion_inferior',
        'hay_alarma_valor',
    ];

    $tipos_campos = [
        'id_verificaciones_cero' => '',
        'fecha' => '',
        'fk_analizadores' => '',
        'fk_botellas' => '',
        'componente' => '',
        'fk_qal1' => '',
        'fk_qal2' => '',
        'fk_usuarios' => '',
        'valor_patron_referencia' => 'float',
        'valor_lectura_real' => 'float',
        'valor_calibrado' => 'float',
        'unidad_medida' => '',
        'ajuste_analizador' => '',
        'limite_alarma_superior' => 'float',
        'limite_actuacion_superior' => 'float',
        'limite_alarma_inferior' => 'float',
        'limite_actuacion_inferior' => 'float',
        'hay_alarma_valor' => 'bool',
    ];


    if (count($datos) > 0) {

        if (isset($datos['del']) && ($datos['del'] == 1)) {

            $row_verificaciones_cero = setSqlDB("SELECT * from verificaciones_cero WHERE id_verificaciones_cero = " . $datos['id_verificaciones_cero'] . " LIMIT 1");
            if ($row_verificaciones_cero !== false) {

                $updateSQL = "DELETE FROM verificaciones_cero WHERE id_verificaciones_cero = '" . $datos['id_verificaciones_cero'] . "' ";
                $Result = mysqli_query($qal3_db, $updateSQL);

                if (!$Result) {
                    $respuesta = ['resp' => 'ERROR', 'data' => 'Error al eliminar EAS Cero: ' . mysqli_error($qal3_db)];
                } else {
                    $respuesta = ['resp' => 'OK', 'data' => 'OK. Eliminado correctamente.'];
                }
            }

        } else {

            $modificando = false;
            $id_verificaciones_cero = '';
            foreach ($datos AS $ind => $dato) {
                if (in_array($ind, $array_campos)) {
                    if ($ind == 'id_verificaciones_cero') {
                        $id_verificaciones_cero = $dato;
                        $modificando = ($id_verificaciones_cero != '');
                    } else {
                        if ($variables != '') {
                            $variables .= ',';
                            $valores .= ',';
                            $variables_valores .= ',';
                        }
                        $variables .= "`" . $ind . "`";
                        if ($tipos_campos[$ind] == 'float') {
                            $valor = convierte_numero($dato, true);
                            $valores .= $valor;
                            $variables_valores .= "`" . $ind . "` = '" . $valor . "'";
                        } elseif ($tipos_campos[$ind] == 'bool') {
                            $valor = convierte_numero($dato, true);
                            $valores .= $valor;
                            $variables_valores .= "`" . $ind . "` = " . $valor;
                        } else {
                            $valores .= "'" . stripslashes($dato) . "'";
                            $variables_valores .= "`" . $ind . "` = '" . stripslashes($dato) . "'";
                        }
                    }
                }
            }

            if ($variables != '') {
                if ($modificando == false) {
                    $updateSQL = "INSERT INTO verificaciones_cero (" . $variables . ") VALUES (" . $valores . ") ";
                } else {
                    $updateSQL = "UPDATE verificaciones_cero SET " . $variables_valores . " WHERE id_verificaciones_cero = '" . $id_verificaciones_cero . "' ";
                }
                $Result = mysqli_query($qal3_db, $updateSQL) or die(mysqli_error($qal3_db));

                if (!$Result) {
                    $respuesta = ['resp' => 'ERROR', 'data' => 'Error al guardar EAS Cero: ' . mysqli_error($qal3_db)];
                } else {
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