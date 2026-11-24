<?php require_once('_config.php'); ?>
<?php require_once('ls_check_session.php'); ?>
<?php require_once('Connections/qal3_db.php'); ?>
<?php require_once('../Connections/registros.php'); ?>
<?php require_once('libreria.php'); ?>
<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Recogemos los parametros.
$id_analizadores = $_REQUEST['equipo'] ?? null;
$componente = $_REQUEST['componente'] ?? null;
$fecha_desde = $_REQUEST['fecha_desde'] ?? null;
$fecha_hasta = $_REQUEST['fecha_hasta'] ?? null;

$global_data = [];

$array_fields = [
    'J07' => [
        'sprintf' => '%s0 %s1',
        'data' => [
            ['db_table' => 'analizadores', 'field' => 'linea'],
            ['db_table' => 'analizadores', 'field' => 'tipo_analizador']
        ]
    ],
    'B10' => ['db_table' => 'analizadores', 'field' => 'tag'],
    'D10' => ['db_table' => 'analizadores', 'field' => 'descripcion'],
    'J10' => ['text' => $componente],
    'B12' => ['db_table' => 'analizadores', 'field' => 'marca'],
    'D12' => ['db_table' => 'analizadores', 'field' => 'modelo'],
    'G12' => ['text' => ''], // SE ELIMINA Referencia fabricante -> falta dato en DB
    'J12' => ['db_table' => 'analizadores', 'field' => 'numero_serie'], // Numero de serie -> falta dato en DB
    'B15' => ['db_table' => 'analizadores_componentes', 'field' => 'rango_medida'], // Límites Operación
    'D15' => ['db_table' => 'analizadores_componentes', 'field' => 'valor_limite_emision'], // Valor Límite Emisión (VLE)
    'G15' => ['db_table' => 'analizadores_componentes', 'field' => 'incertidumbre_equipo'], // Incertidumbre requerida
    'J15' => ['db_table' => 'analizadores_componentes', 'field' => 'limite_legal_diario'], // Límite Semihorario 97%
    'B17' => ['db_table' => 'qal2', 'field' => 'fecha_qal2'], //Fecha QAL2
    'D17' => ['db_table' => 'qal2', 'field' => 'rango_calibrado'], // Rango Calibrado QAL2
    'G17' => ['db_table' => 'analizadores_componentes', 'field' => 'func_calibracion'], // Función de Calibración QAL2
    'J17' => ['db_table' => 'qal1', 'field' => 'certificado'], // Certificado QAL1
    'B19' => ['db_table' => 'analizadores', 'field' => 'frecuencia_verificacion'], // Frecuencia Verificación
    'D19' => ['db_table' => 'qal2', 'field' => 'intervalo_confianza'], // Intervalo de confianza (95%)
    'G19' => ['db_table' => 'qal1', 'field' => 'ssam_cero', 'comapunto' => true], // SSAM de Cero
    'J19' => ['db_table' => 'qal1', 'field' => 'ssam_rango', 'comapunto' => true], // SSAM de Rango
    'B22' => ['db_table' => 'verificaciones_cero', 'field' => 'limite_alarma_superior'], //
    'D22' => ['db_table' => 'verificaciones_cero', 'field' => 'limite_actuacion_superior'], //
    'G22' => ['db_table' => 'verificaciones_cero', 'field' => 'limite_alarma_inferior'], //
    'J22' => ['db_table' => 'verificaciones_cero', 'field' => 'limite_actuacion_inferior'], //
    'B25' => ['text' => ''], // Equipo de CERO
    'D25' => ['text' => 'Aire cero (Instrumento)'], // Referencia Equipo Patrón CERO
    'G25' => ['db_table' => 'verificaciones_cero', 'field' => 'descripcion'], // Descripción Equipo Patrón CERO
    'J25' => ['text' => '0,0'], // Valor Patrón CERO
    'C28' => ['lista' => 'registros_verificaciones_cero'], //
    // Verificaciones de Rango
    'B65' => ['db_table' => 'verificaciones_rango', 'field' => 'limite_alarma_superior'], //
    'D65' => ['db_table' => 'verificaciones_rango', 'field' => 'limite_actuacion_superior'], //
    'G65' => ['db_table' => 'verificaciones_rango', 'field' => 'limite_alarma_inferior'], //
    'J65' => ['db_table' => 'verificaciones_rango', 'field' => 'limite_actuacion_inferior'], //
    'B68' => ['text' => ''], // Equipo
    'D68' => [
        'sprintf' => 'Nº Botella: %s0 / Certificado: %s1',
        'data' => [
            ['db_table' => 'verificaciones_rango', 'field' => 'referencia'],
            ['db_table' => 'verificaciones_rango', 'field' => 'n_certificado']
        ],
    ], // Referencia Equipo Patrón
    'G68' => ['db_table' => 'verificaciones_rango', 'field' => 'descripcion'], // Descripción Equipo Patrón
    'J68' => ['db_table' => 'verificaciones_rango', 'field' => 'valor_patron_referencia'], // Valor Patrón
    'C71' => ['lista' => 'registros_verificaciones_rango'], //
];

$array_fields_cero = [
    '2' => ['only_field' => 'fecha'], // Fecha Verificación
    '3' => ['only_field' => 'NombreCompleto'], // Técnico Mantenimiento
    '4' => ['only_field' => 'n_botella'], // Referencia Equipo Patrón
    '5' => ['only_field' => 'valor_patron_referencia'], // Valor Patrón Referencia
    '6' => ['only_field' => 'valor_lectura_real'], // Valor Lectura Real
    '7' => ['formula' => '=+G%s-F%s'], // Error Medida
    '8' => ['only_field' => 'unidad_medida'], // Unidad Medida
    // '9' => Calculada
    '10' => ['only_field' => 'ajuste_analizador'], // Ajuste Analizador
];

$array_fields_rango = [
    '2' => ['only_field' => 'fecha'], // Fecha Verificación
    '3' => ['only_field' => 'NombreCompleto'], // Técnico Mantenimiento
    '4' => ['only_field' => 'n_botella'], // Referencia Equipo Patrón
    '5' => ['only_field' => 'valor_patron_referencia'], // Valor Patrón Referencia
    '6' => ['only_field' => 'valor_lectura_real'], // Valor Lectura Real
    '7' => ['formula' => '=+G%s-F%s'], // Error Medida
    '8' => ['only_field' => 'unidad_medida'], // Unidad Medida
    // '9' => Calculada
    '10' => ['only_field' => 'ajuste_analizador'], // Ajuste Analizador
];


function put_data_db($componente, $row, $col, $valor)
{
    global $inputFileName;

    if (strpos($valor, "%s") > -1) {
        $valor = str_replace("%s", $row, $valor);
    }

    $es_numerico = is_numeric($valor) ? 1 : 0;
    $sql = 'INSERT INTO excel_a_generar (`nombre_documento`,`pestanya`,`row_pos`,`col_pos`,`valor`,`es_numerico`) VALUES ("' . $inputFileName . '","' . $componente . '",' . $row . ',' . $col . ',"' . $valor . '",' . $es_numerico . ');';
    putDataDb($sql);
}

function get_valor_array($row_field, $data_origin)
{
    global $global_data, $array_fields_cero, $array_fields_rango;
    $valor = '';

    if (isset($row_field['db_table'])) {
        $db_table = $row_field['db_table'];
        $db_field = $row_field['field'];
        if (isset($data_origin[$db_table])) {
            if (isset($data_origin[$db_table][$db_field])) {
                $valor = $data_origin[$db_table][$db_field];
            }
        }

    } elseif (isset($row_field['formula'])) {
        $db_fields = $row_field['formula'];
        $valor = $db_fields;

    } elseif (isset($row_field['only_field'])) {
        $db_field = $row_field['only_field'];
        if (isset($data_origin[$db_field])) {
            $valor = $data_origin[$db_field];
        }

    } elseif (isset($row_field['text'])) {
        $valor = $row_field['text'];

    } elseif (isset($row_field['sprintf'])) {
        $valor = $row_field['sprintf'];
        foreach ($row_field['data'] as $pos => $row_valores) {
            $valor_temp = get_valor_array($row_valores, $global_data);
            $valor = str_replace('%s' . $pos, $valor_temp, $valor);
        }

    } elseif (isset($row_field['lista'])) {

        $nombre_del_array = $row_field['lista'];
        $componente = $global_data['analizadores_componentes']['componente'];

        foreach ($global_data[$nombre_del_array] as $row_pos => $row_registros) {

            if ($nombre_del_array == 'registros_verificaciones_cero') {
                foreach ($array_fields_cero as $col_row => $row_fields) {
                    $valor_temp = get_valor_array($row_fields, $row_registros);
                    // pîntamos la celda desde la posicion 28 para rellenar los CERO
                    put_data_db($componente, ($row_pos + 28), $col_row + 1, $valor_temp);
                }
            } else {
                foreach ($array_fields_rango as $col_row => $row_fields) {
                    $valor_temp = get_valor_array($row_fields, $row_registros);
                    // pîntamos la celda desde la posicion 71 para rellenar los RANGO
                    put_data_db($componente, ($row_pos + 71), $col_row + 1, $valor_temp);
                }
            }

        }

        $valor = null;
    }

    if (isset($row_field['comapunto'])) {
        if (strpos($valor, ",") > -1) {
            $valor = str_replace(',', '.', $valor);
        }
    }

    return $valor;
}

function execInBackground($cmd)
{
    $descriptorspec = array(
        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
        2 => array("file", "tmp/error-output.txt", "a") // stderr is a file to write to
    );

    //$dir = 'D:\Siade2017\Botellas\qal3\templatexls\\';

    if (substr(php_uname(), 0, 7) == "Windows") {
        $process = proc_open($cmd, $descriptorspec, $pipes);
        if (is_resource($process)) {
            // $pipes now looks like this:
            // 0 => writeable handle connected to child stdin
            // 1 => readable handle connected to child stdout
            // Any error output will be appended to /tmp/error-output.txt

            fwrite($pipes[0], '<?php print_r($_ENV); ?>');
            fclose($pipes[0]);

            $return_value = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            // It is important that you close any pipes before calling
            // proc_close in order to avoid a deadlock
            $return_value = proc_close($process);

            return "command returned " . (($return_value == false) ? 'ERROR stream_get_contents' : $return_value) . "<br>";
        } else {
            return "No se puede ejecutar. <br>";
        }
    } else {
        return exec($cmd . " > /dev/null &");
    }
}

if (($id_analizadores != null) && ($componente != null)) {

    $global_data['analizadores'] = setSqlDB('SELECT * FROM analizadores WHERE id_analizadores = ' . $id_analizadores . ' LIMIT 1');
    $global_data['analizadores_componentes'] = setSqlDB('SELECT * FROM analizadores_componentes WHERE fk_analizadores = ' . $id_analizadores . ' AND componente = "' . $componente . '" LIMIT 1');
    $global_data['qal1'] = setSqlDB('SELECT * FROM qal1 WHERE fk_analizadores = ' . $id_analizadores . ' AND componente_qal1 = "' . $componente . '" LIMIT 1');
    $global_data['qal2'] = setSqlDB('SELECT * FROM qal2 WHERE fk_analizadores = ' . $id_analizadores . ' AND componente_qal2 = "' . $componente . '" LIMIT 1');

    $global_data['verificaciones_cero'] = setSqlDB('SELECT * FROM verificaciones_cero LEFT JOIN botellas ON botellas.id_botellas = verificaciones_cero.fk_botellas WHERE fk_analizadores = ' . $id_analizadores . ' AND componente = "' . $componente . '" AND verificaciones_cero.fecha > "' . $fecha_desde . '" AND verificaciones_cero.fecha < "' . $fecha_hasta . '"  ORDER BY fecha DESC LIMIT 1');
    $global_data['verificaciones_rango'] = setSqlDB('SELECT * FROM verificaciones_rango LEFT JOIN botellas ON botellas.id_botellas = verificaciones_rango.fk_botellas WHERE fk_analizadores = ' . $id_analizadores . ' AND componente = "' . $componente . '" AND verificaciones_rango.fecha > "' . $fecha_desde . '" AND verificaciones_rango.fecha < "' . $fecha_hasta . '"  ORDER BY fecha DESC LIMIT 1');

    $global_data['registros_verificaciones_cero'] = getAllDataDB('SELECT *, CONCAT(usuarios.Nombre, " ", usuarios.Apellido1) AS NombreCompleto, botellas.n_botella FROM verificaciones_cero LEFT JOIN explotacion.usuarios ON usuarios.id = verificaciones_cero.fk_usuarios LEFT JOIN botellas ON botellas.id_botellas = verificaciones_cero.fk_botellas WHERE fk_analizadores = ' . $id_analizadores . ' AND componente = "' . $componente . '" AND verificaciones_cero.fecha > "' . $fecha_desde . '" AND verificaciones_cero.fecha < "' . $fecha_hasta . '" ');
    $global_data['registros_verificaciones_rango'] = getAllDataDB('SELECT *, CONCAT(usuarios.Nombre, " ", usuarios.Apellido1) AS NombreCompleto, botellas.n_botella FROM verificaciones_rango LEFT JOIN explotacion.usuarios ON usuarios.id = verificaciones_rango.fk_usuarios LEFT JOIN botellas ON botellas.id_botellas = verificaciones_rango.fk_botellas WHERE fk_analizadores = ' . $id_analizadores . ' AND componente = "' . $componente . '" AND verificaciones_rango.fecha > "' . $fecha_desde . '" AND verificaciones_rango.fecha < "' . $fecha_hasta . '" ');

    $extension = '.xlsx';

    if (!file_exists('templatexls/informes')) {
        mkdir('templatexls/informes');
    }

    $inputFileName = 'informe_' . $global_data['analizadores']['tag'] . '_' . $global_data['analizadores']['linea'] . '_' . $componente . '__' . $fecha_desde . '__' . $fecha_hasta . $extension;

    putDataDb('DELETE FROM excel_a_generar WHERE nombre_documento = "' . $inputFileName . '"');

    put_data_db($componente, 1, 13, 'Fechas desde: ' . $fecha_desde . ' hasta: ' . $fecha_hasta);

    foreach ($array_fields as $cel_pos => $row_field) {
        $valor = get_valor_array($row_field, $global_data);
        // Hacemos esto porque puede ser tipo LISTA
        if ($valor !== null) {
            $col = ord(substr($cel_pos, 0, 1)) - 64;
            $row = substr($cel_pos, 1, 2);
            put_data_db($componente, $row, $col, $valor);
        }
    }

    //$cmd = 'C:\\\wamp\\\www\\\siade\\\qal3\\\templatexls\\\genera_informes_excel_qal3.exe';
    $cmd = 'C:\\\tirme\\\genera_informes_excel_qal3\\\genera_informes_excel_qal3.exe';

    // $updateSQL = "REPLACE INTO comandos (comando, p1, p2, p3, p4) VALUES ('1', '".$cmd."', '".date("Y-m-d H:i:s")."', '-', '-')";
    $updateSQL = "REPLACE INTO comandos (comando, p1, p2, p3, p4) VALUES ('" . $cmd . "', '" . date("Y-m-d H:i:s") . "', '-', '-', '-')";
    mysqli_select_db($registros, $database_registros);
    $Result1 = mysqli_query($registros, $updateSQL) or die(mysqli_error($registros));

    echo json_encode(['resp' => 'OK', 'data' => 'Finalizado']);
} else {
    echo json_encode(['resp' => 'ERROR', 'data' => 'Faltan parametros EQUIPO y COMPONENTE']);
}

