<?php require_once('_config.php'); ?>
<?php require_once('ls_check_session.php'); ?>
<?php require_once('Connections/qal3_db.php'); ?>
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
    'G12' => ['text' => ''], // Referencia fabricante -> falta dato en DB
    'J12' => ['text' => ''], // Numero de referencia -> falta dato en DB
    'B15' => ['db_table' => 'analizadores_componentes', 'field' => 'rango_medida'], // Límites Operación
    'D15' => ['db_table' => 'analizadores_componentes', 'field' => 'valor_limite_emision'], // Valor Límite Emisión (VLE)
    'G15' => ['db_table' => 'analizadores_componentes', 'field' => 'incertidumbre_equipo'], // Incertidumbre requerida
    'J15' => ['db_table' => 'analizadores_componentes', 'field' => 'limite_legal_diario'], // Límite Semihorario 97%
    'B17' => ['db_table' => 'qal2', 'field' => 'fecha_documento_qal2'], //Fecha QAL2
    'D17' => ['db_table' => 'qal2', 'field' => 'rango_calibrado'], // Rango Calibrado QAL2
    'G17' => ['db_table' => 'analizadores_componentes', 'field' => 'func_calibracion'], // Función de Calibración QAL2
    'J17' => ['db_table' => 'qal1', 'field' => 'certificado'], // Certificado QAL1
    'B19' => ['text' => ''], // Frecuencia Verificación
    'D19' => ['text' => ''], // Intervalo de confianza (95%)
    'G19' => ['db_table' => 'qal1', 'field' => 'ssam_cero'], // SSAM de Cero
    'J19' => ['db_table' => 'qal1', 'field' => 'ssam_rango'], // SSAM de Rango
    'B22' => ['db_table' => 'verificaciones_cero', 'field' => 'limite_alarma_superior'], //
    'D22' => ['db_table' => 'verificaciones_cero', 'field' => 'limite_actuacion_superior'], //
    'G22' => ['db_table' => 'verificaciones_cero', 'field' => 'limite_alarma_inferior'], //
    'J22' => ['db_table' => 'verificaciones_cero', 'field' => 'limite_actuacion_inferior'], //
    'B25' => ['text' => ''], // Equipo de CERO
    'D25' => ['text' => 'Aire cero (Instrumento)'], // Referencia Equipo Patrón CERO
    'G25' => ['text' => ''], // Descripción Equipo Patrón CERO
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
    'C' => ['only_field' => 'fecha'], // Fecha Verificación
    'D' => ['only_field' => 'NombreCompleto'], // Técnico Mantenimiento
    'E' => ['only_field' => 'n_botella'], // Referencia Equipo Patrón
    'F' => ['only_field' => 'valor_patron_referencia'], // Valor Patrón Referencia
    'G' => ['only_field' => 'valor_lectura_real'], // Valor Lectura Real
    'H' => ['only_field' => 'valor_calibrado'], // Error Medida
    'I' => ['only_field' => 'unidad_medida'], // Unidad Medida
    // 'J' => Calculada
    'K' => ['only_field' => 'ajuste_analizador'], // Ajuste Analizador
];

$array_fields_rango = [
    'C' => ['only_field' => 'fecha'], // Fecha Verificación
    'D' => ['only_field' => 'NombreCompleto'], // Técnico Mantenimiento
    'E' => ['only_field' => 'n_botella'], // Referencia Equipo Patrón
    'F' => ['only_field' => 'valor_patron_referencia'], // Valor Patrón Referencia
    'G' => ['only_field' => 'valor_lectura_real'], // Valor Lectura Real
    'H' => ['only_field' => 'valor_calibrado'], // Error Medida
    'I' => ['only_field' => 'unidad_medida'], // Unidad Medida
    // 'J' => Calculada
    'K' => ['only_field' => 'ajuste_analizador'], // Ajuste Analizador
];

function get_valor_array($row_field, $data_origin)
{
    global $global_data, $sheet, $array_fields_cero, $array_fields_rango;
    $valor = '';

    if (isset($row_field['db_table'])) {
        $db_table = $row_field['db_table'];
        $db_field = $row_field['field'];
        if (isset($data_origin[$db_table])) {
            if (isset($data_origin[$db_table][$db_field])) {
                $valor = $data_origin[$db_table][$db_field];
            }
        }

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

        foreach ($global_data[$nombre_del_array] as $row_pos => $row_registros) {

            if ($nombre_del_array == 'registros_verificaciones_cero') {
                foreach ($array_fields_cero as $col_row => $row_fields) {
                    $valor_temp = get_valor_array($row_fields, $row_registros);
                    // pîntamos la celda desde la posicion 28 para rellenar los CERO
                    $sheet->setCellValue($col_row . ($row_pos + 28), $valor_temp);
                }
            } else {
                foreach ($array_fields_rango as $col_row => $row_fields) {
                    $valor_temp = get_valor_array($row_fields, $row_registros);
                    // pîntamos la celda desde la posicion 71 para rellenar los RANGO
                    $sheet->setCellValue($col_row . ($row_pos + 71), $valor_temp);
                }
            }

        }

        $valor = null;
    }

    return $valor;
}

if (($id_analizadores != null) && ($componente != null)) {

    echo 'Inicio de generación del informe<br>';

    $global_data['analizadores'] = setSqlDB('SELECT * FROM analizadores WHERE id_analizadores = ' . $id_analizadores . ' LIMIT 1');
    $global_data['analizadores_componentes'] = setSqlDB('SELECT * FROM analizadores_componentes WHERE fk_analizadores = ' . $id_analizadores . ' AND componente = "' . $componente . '" LIMIT 1');
    $global_data['qal1'] = setSqlDB('SELECT * FROM qal1 WHERE fk_analizadores = ' . $id_analizadores . ' AND componente_qal1 = "' . $componente . '" LIMIT 1');
    $global_data['qal2'] = setSqlDB('SELECT * FROM qal2 WHERE fk_analizadores = ' . $id_analizadores . ' AND componente_qal2 = "' . $componente . '" LIMIT 1');

    $global_data['verificaciones_cero'] = setSqlDB('SELECT * FROM verificaciones_cero LEFT JOIN botellas ON botellas.id_botellas = verificaciones_cero.fk_botellas WHERE fk_analizadores = ' . $id_analizadores . ' AND componente = "' . $componente . '" AND verificaciones_cero.fecha > "' . $fecha_desde . '" AND verificaciones_cero.fecha < "' . $fecha_hasta . '"  ORDER BY fecha DESC LIMIT 1');
    $global_data['verificaciones_rango'] = setSqlDB('SELECT * FROM verificaciones_rango LEFT JOIN botellas ON botellas.id_botellas = verificaciones_rango.fk_botellas WHERE fk_analizadores = ' . $id_analizadores . ' AND componente = "' . $componente . '" AND verificaciones_rango.fecha > "' . $fecha_desde . '" AND verificaciones_rango.fecha < "' . $fecha_hasta . '"  ORDER BY fecha DESC LIMIT 1');

    $global_data['registros_verificaciones_cero'] = getAllDataDB('SELECT *, CONCAT(usuarios.Nombre, " ", usuarios.Apellido1, " ", usuarios.Apellido2) AS NombreCompleto, botellas.n_botella FROM verificaciones_cero LEFT JOIN explotacion.usuarios ON usuarios.id = verificaciones_cero.fk_usuarios LEFT JOIN botellas ON botellas.id_botellas = verificaciones_cero.fk_botellas WHERE fk_analizadores = ' . $id_analizadores . ' AND componente = "' . $componente . '" AND verificaciones_cero.fecha > "' . $fecha_desde . '" AND verificaciones_cero.fecha < "' . $fecha_hasta . '" ');
    $global_data['registros_verificaciones_rango'] = getAllDataDB('SELECT *, CONCAT(usuarios.Nombre, " ", usuarios.Apellido1, " ", usuarios.Apellido2) AS NombreCompleto, botellas.n_botella FROM verificaciones_rango LEFT JOIN explotacion.usuarios ON usuarios.id = verificaciones_rango.fk_usuarios LEFT JOIN botellas ON botellas.id_botellas = verificaciones_rango.fk_botellas WHERE fk_analizadores = ' . $id_analizadores . ' AND componente = "' . $componente . '" AND verificaciones_rango.fecha > "' . $fecha_desde . '" AND verificaciones_rango.fecha < "' . $fecha_hasta . '" ');

    $tipo = 'Xlsx';
    $extension = '.xlsx';
    $originFileName = 'templatexls/template_calibraciones' . $extension;
    $inputFileName = 'templatexls/informe_' . $componente . '__' . $fecha_desde . '__' . $fecha_hasta . $extension;

    if (!copy($originFileName, $inputFileName)) {
        echo 'No se puede copiar desde ' . $originFileName . ' a ' . $inputFileName;
        exit;
    }

    $inputFile = IOFactory::identify($inputFileName);
    $reader = IOFactory::createReaderForFile($inputFileName);
    $reader->setIncludeCharts(true);

    $spreadsheet = $reader->load($inputFileName);
    $sheet = $spreadsheet->getActiveSheet();

    foreach ($array_fields as $cel_pos => $row_field) {
        $valor = get_valor_array($row_field, $global_data);
        // Hacemos esto porque puede ser tipo LISTA
        if ($valor !== null) {
            $sheet->setCellValue($cel_pos, $valor);
        }
    }

    /*
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $inputFileName . '');
        header("Expires: 0");
        header("cache-control:just-revalidate,post-check:0, pre-check:0");
        header("Pragma: public");
    */

    $chart1 = $sheet->getChartByIndex(1);
    $chart_legend = $chart1->getPlotArea();

    $plotArea = $chart1->getPlotArea()->getPlotGroup();

    $writer = IOFactory::createWriter($spreadsheet, $tipo);
    $writer->setIncludeCharts(true);
    $writer->save($inputFileName);

    echo 'Finalizado<br>';
    echo '<br>';
    echo '<a href="' . $inputFileName . '" target="_self">Descargar informe tipo '.$tipo.'</a>';
    echo '';
    echo '';
} else {
    echo 'Faltan parametros EQUIPO y COMPONENTE';
}

