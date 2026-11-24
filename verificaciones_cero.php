<?php require_once('_config.php'); ?>
<?php require_once('ls_check_session.php'); ?>
<?php require_once('Connections/qal3_db.php'); ?>
<?php require_once('libreria.php'); ?>
<?php require_once('ls_cabecera.php'); ?>

<?php

$array_verificaciones_cero = [];

$sel_id = '-1';

if (isset($_REQUEST['sel'])) {
    $sel_id = $_REQUEST['sel'];
}

$vuelta_query = '';
if (isset($_REQUEST['tipos'])) {
    $vuelta_query .= 'tipos=' . $_REQUEST['tipos'];
}
if (isset($_REQUEST['lineas'])) {
    $vuelta_query .= (($vuelta_query != '') ? '&' : '') . 'lineas=' . $_REQUEST['lineas'];
}

if (isset($_REQUEST['id'])) {
    $id_analizador = $_REQUEST['id'];
    $componente = $_REQUEST['componente'];

    $array_verificaciones_cero = getAllDataDB("SELECT
            verificaciones_cero.*, 
            (verificaciones_cero.valor_patron_referencia - verificaciones_cero.valor_lectura_real) AS error_medida,
            analizadores.tag AS txt_analizador,
            usuarios.id,
            ROUND(verificaciones_cero.valor_patron_referencia-verificaciones_cero.valor_lectura_real, 2) AS error_medida, 
            analizadores.tag AS txt_analizador, usuarios.id, 
            CONCAT(botellas.n_botella,' / ',botellas.n_certificado,' / ',botellas.n_pr) AS txt_botella, 
            CONCAT(usuarios.Nombre,' ',usuarios.Apellido1) AS nombre_usuarios,
            (IF((verificaciones_cero.valor_patron_referencia-verificaciones_cero.valor_lectura_real)=0, 'No Deriva', IF((verificaciones_cero.valor_patron_referencia-verificaciones_cero.valor_lectura_real)>0,'Positiva', 'Negativa'))) AS tipo_deriva,
            (IF(((limite_alarma_superior IS NULL) AND (limite_actuacion_superior IS NULL) AND (limite_alarma_inferior IS NULL) AND (limite_actuacion_inferior IS NULL))=true,'NO', 'SI')) AS limites,
            qal1.fecha_qal1, 
            qal1.certificado,
            qal1.ssam_cero,
            qal2.fecha_qal2
    FROM verificaciones_cero
    LEFT JOIN analizadores ON analizadores.id_analizadores = verificaciones_cero.fk_analizadores
    LEFT JOIN botellas ON botellas.id_botellas = verificaciones_cero.fk_botellas
    LEFT JOIN botellas_componentes ON botellas_componentes.componente = verificaciones_cero.componente AND botellas_componentes.fk_botellas = botellas.id_botellas
    LEFT JOIN explotacion.usuarios ON usuarios.id = verificaciones_cero.fk_usuarios
    LEFT JOIN qal1 ON qal1.id_qal1 = verificaciones_cero.fk_qal1 
    LEFT JOIN qal2 ON qal2.id_qal2 = verificaciones_cero.fk_qal2
    WHERE verificaciones_cero.fk_analizadores = " . $id_analizador . " AND verificaciones_cero.componente = '" . $componente . "' 
    ORDER BY verificaciones_cero.fecha DESC");

    if (!isset($array_verificaciones_cero[0]['txt_analizador'])) {
        $row_analizador = setSqlDB("SELECT * FROM analizadores WHERE id_analizadores = " . $id_analizador . " LIMIT 1");
        $txt_analizador = $row_analizador['tag'];
        $mensaje_error = 'No hay verificaciones_cero para el analizador indicado ' . $row_analizador['descripcion'] . ' con el componente ' . $componente . '.';
    } else {
        $txt_analizador = $array_verificaciones_cero[0]['txt_analizador'];
    }


}

function pinta_td($campo, $valor, $sobre_limite = false)
{
    global $arr_formatos;

    $tipo = $arr_formatos[$campo];
    $valores = ['left', ''];
    $add_style = '';
    $date_format = '';
    if ($tipo != '') {
        $valores = explode("|", $tipo[1]);
        if (isset($valores[1])) {
            switch ($valores[1]) {
                case '@date':
                    $valor = ($valor != '') ? date("d/m/Y", strtotime($valor)) : 'NULL';
                    $date_format = ($campo == 'fecha') ? "data-sort='YYYYMMDD'" : "";
                    break;
                case '@bool':
                    //$valor = $valor;
                    break;
                default:
                    $valor = $valor . $valores[1];
                    $add_style .= 'white-space: nowrap; ';
                    break;
            }
        }
    }
    if ($valores[0] != '') $add_style .= 'text-align: ' . $valores[0] . ';';
    if (($sobre_limite == true) && (($campo == 'valor_lectura_real') || ($campo == 'ajuste_analizador') || ($campo == 'valor_calibrado'))) {
        $add_style .= 'background-color: #C4D79B;';
    }

    return '  <td rel-campo="' . $campo . '" style="' . $add_style . '" '.$date_format.'>' . $valor . '</td>';
}

$arr_formatos = [
    '' => ['', ''],
    'id_verificaciones_cero' => ['Id', '', 'hide'],
    'fecha' => ['Fecha', 'left|@date', 'show'],
    'txt_botella' => ['Referencia<br>Equipo patrón', '', 'show'],
    'nombre_usuarios' => ['Técnico<br>Mto.', '', 'show'],
    'valor_patron_referencia' => ['Patron<br>Ref.', 'right|', 'show'],
    'valor_lectura_real' => ['Lectura<br>Real', 'right|', 'show'],
    'error_medida' => ['Error<br>Medida', 'right|', 'show'],
    'valor_calibrado' => ['Valor<br>Calibrado', 'right|', 'show'],
    'unidad_medida' => ['Unidad<br>Medida', 'right|', 'show'],
    'tipo_deriva' => ['Tipo<br>Deriva', 'left|', 'show'],
    'ajuste_analizador' => ['Ajuste<br>Analizador', 'left|@bool', 'show'],
    'limites' => ['Hay límites<br>Ssam Cero', 'left|@bool', 'show'],
    'fecha_qal1' => ['QAL1<br>Fecha', 'left|@date', 'show'],
    'ssam_cero' => ['QAL1<br>Ssam Cero', 'right|', 'show'],
    'fecha_qal2' => ['QAL2<br>Fecha', 'left|@date', 'show']
];
$arr_cols_hide = [];

$tbody_verificaciones_cero = '<thead>';
$tbody_verificaciones_cero .= '  <tr>';
$int_count = 0;
foreach ($arr_formatos as $contador => $row) {
    if (isset($row[2]) && ($row[2] == 'hide')) {
        $arr_cols_hide[] = $int_count;
    }
    $tbody_verificaciones_cero .= '    <th style="white-space: nowrap; text-align: center; max-width: 100px;">' . $row[0] . '</th>';
    $int_count++;
}
$tbody_verificaciones_cero .= '  </tr>';
$tbody_verificaciones_cero .= '</thead>';

// data: [[Date.UTC(2019,7,1), 4.3], [Date.UTC(2019,7,2), 5.2], [Date.UTC(2019,7,3), 5.7], [Date.UTC(2019,7,5), 6.9], [Date.UTC(2019,7,6), 9.7], [Date.UTC(2019,7,9), 1.1], [Date.UTC(2019,7,12), 1.3], [Date.UTC(2019,7,28), 1.5]]
$array_chart = '';
$min_date = 'Date.UTC(9999,11,31)';
$max_date = 'Date.UTC(1990,0,1)';
$min_escala = 0;
$max_escala = 0;
$limites = ['', '', '', ''];
$calculo = [];

if (isset($array_verificaciones_cero) && count($array_verificaciones_cero) > 0) {
    $tbody_verificaciones_cero .= '<tbody>';
    foreach ($array_verificaciones_cero as $pos => $row_verificaciones_cero) {

        /* Cálculo de LIMITES */
        $sobre_limite = ($row_verificaciones_cero['hay_alarma_valor'] === "1");

        $tbody_verificaciones_cero .= '<tr class="' . (($row_verificaciones_cero['id_verificaciones_cero'] == $sel_id) ? 'selected' : '') . '" >';
        $tbody_verificaciones_cero .= '<td>';
        $tbody_verificaciones_cero .= '    <div class="btn-group">';
        $tbody_verificaciones_cero .= '    <button class="editar_verificaciones_cero btn btn-xs btn-success margin-bottom-3 margin-right-3" rel-id="' . $row_verificaciones_cero['id_verificaciones_cero'] . '"><i class="fa fa-edit"></i></button>';
        $tbody_verificaciones_cero .= '    <button class="borrar_verificaciones_cero btn btn-xs btn-danger" rel-id="' . $row_verificaciones_cero['id_verificaciones_cero'] . '"><i class="fa fa-times"></i></button>';
        $tbody_verificaciones_cero .= '    </div>';
        $tbody_verificaciones_cero .= '</td>';
        foreach ($arr_formatos as $contador => $row) {
            if ($contador != '') {
                $field = $contador;
                $valor = $row_verificaciones_cero[$contador];
                $tbody_verificaciones_cero .= pinta_td($field, $valor, $sobre_limite);
            }
        }
        $tbody_verificaciones_cero .= '</tr>';

        // Valores de CHART
        if ($array_chart != '') {
            $array_chart .= ',';
            $limites[0] .= ',';
            $limites[1] .= ',';
            $limites[2] .= ',';
            $limites[3] .= ',';
        }
        $fecha = 'Date.UTC(' . str_replace("-", ",", $row_verificaciones_cero['fecha']) . ')';
        $array_chart .= '[' . $fecha . ', ' . $row_verificaciones_cero['valor_lectura_real'] . ']';
        if ($fecha < $min_date)
            $min_date = $fecha;
        if ($fecha > $max_date)
            $max_date = $fecha;

        if (isset($row_verificaciones_cero['ssam_cero'])) {
            $row_verificaciones_cero['ssam_cero'] = convierte_numero($row_verificaciones_cero['ssam_cero'], true);
            $calculo[0] = 0 - 2 * $row_verificaciones_cero['ssam_cero'];
            $calculo[1] = 0 + 2 * $row_verificaciones_cero['ssam_cero'];
            $calculo[2] = 0 - 3 * $row_verificaciones_cero['ssam_cero'];
            $calculo[3] = 0 + 3 * $row_verificaciones_cero['ssam_cero'];

            $limites[0] .= '[' . $fecha . ', ' . $calculo[0] . ']';
            $limites[1] .= '[' . $fecha . ', ' . $calculo[1] . ']';
            $limites[2] .= '[' . $fecha . ', ' . $calculo[2] . ']';
            $limites[3] .= '[' . $fecha . ', ' . $calculo[3] . ']';

            if ($min_escala > ($calculo[2] - 1)) $min_escala = $calculo[2] - 1;
            if ($max_escala < ($calculo[3] + 1)) $max_escala = $calculo[3] + 1;
        }

    }
    $tbody_verificaciones_cero .= '</tbody>';
} else {
    $tbody_verificaciones_cero .= '';
}

$array_chart = '[' . $array_chart . ']';
$limites[0] = '[' . $limites[0] . ']';
$limites[1] = '[' . $limites[1] . ']';
$limites[2] = '[' . $limites[2] . ']';
$limites[3] = '[' . $limites[3] . ']';

$tabla_verificaciones_cero = '<table id="tabla_verificaciones_cero" class="table table-striped table-bordered table-hover" width="100%">';
$tabla_verificaciones_cero .= $tbody_verificaciones_cero;
$tabla_verificaciones_cero .= '</table>';

?>

<!-- MAIN PANEL -->
<div id="main" role="main">

    <!-- RIBBON -->
    <div id="ribbon">

            <span class="ribbon-button-alignment">
                <!-- <span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh" rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings."
                      data-html="true">
                    <i class="fa fa-refresh"></i>
                </span> -->
            </span>

        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <li>Analizadores</li>
            <li><?php echo $txt_analizador; ?></li>
            <li><?php echo 'componente: ' . $componente; ?></li>
            <li class="text-warning">Verificaciones Cero</li>
        </ol>

    </div>
    <!-- END RIBBON -->

    <!-- MAIN CONTENT -->
    <div id="content">

        <!-- widget grid -->
        <section id="widget-grid" class="margin-top-20">

            <!-- row -->
            <div class="">

                <!-- NEW WIDGET START -->
                <article class="">

                    <!-- Widget ID (each widget will need unique ID)-->
                    <div class="jarviswidget well" id="wid-id-0a">

                        <header>
                            <h2>Lista de Verificaciones de Cero</h2>
                        </header>

                        <!-- widget div-->
                        <div class="row">

                            <div class="row">
                                <ul id="widget-tab-1" class="nav nav-tabs">
                                    <li style="padding-right: 30px; padding-left: 10px;">
                                        <button onclick="window.location='analizadores.php<?php echo (($vuelta_query != '') ? '?' . $vuelta_query : ''); ?>'" class="btn btn-default" id="volver_lista"><i class="fa fa-arrow-left"></i> Volver lista analizadores</button>
                                    </li>
                                    <li class="active"><a data-toggle="tab" href="#hr1"> <i class="fa fa-lg fa-table"></i> <span class="hidden-mobile hidden-tablet"> Lista de datos </span> </a></li>
                                    <li><a data-toggle="tab" href="#hr2"> <i class="fa fa-lg fa-line-chart"></i> <span class="hidden-mobile hidden-tablet"> Gráfica </span></a></li>
                                    <li style="padding-left: 30px; padding-right: 10px; float: right !important;">
                                        <div class="btn-group">
                                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Ir a opción... <i class="caret"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a href="qal1_lista.php?id=<?php echo $id_analizador; ?>&componente=<?php echo $componente; ?><?php echo(($vuelta_query != '') ? '&' . $vuelta_query : ''); ?>">QAL1</a>
                                                </li>
                                                <li>
                                                    <a href="qal2_lista.php?id=<?php echo $id_analizador; ?>&componente=<?php echo $componente; ?><?php echo(($vuelta_query != '') ? '&' . $vuelta_query : ''); ?>">QAL2</a>
                                                </li>
                                                <li>
                                                    <a href="verificaciones_cero.php?id=<?php echo $id_analizador; ?>&componente=<?php echo $componente; ?><?php echo(($vuelta_query != '') ? '&' . $vuelta_query : ''); ?>">Verificaciones Cero</a>
                                                </li>
                                                <li>
                                                    <a href="verificaciones_rango.php?id=<?php echo $id_analizador; ?>&componente=<?php echo $componente; ?><?php echo(($vuelta_query != '') ? '&' . $vuelta_query : ''); ?>">Verificaciones Rango</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <!-- widget content -->
                            <div class="widget-body no-padding">

                                <div class="tab-content padding-10">
                                    <div class="tab-pane fade in active" id="hr1">
                                        <div id="zona_tabla_verificaciones_cero margin-top-10" style="margin-top: 10px;">
                                            <?php echo $tabla_verificaciones_cero; ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane margin-top-10" id="hr2">
                                        <div id="container"></div>
                                    </div>
                                </div>

                            </div>

                            <!-- end widget content -->

                        </div>
                        <!-- end widget div -->

                    </div>

                </article>
            </div>
        </section>

    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<?php require_once('ls_footer.php'); ?>

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
<script src="js/highcharts/highcharts.min.js<?php echo $version_app_link; ?>"></script>
<script src="js/highcharts/modules/series-label.js"></script>
<script src="js/highcharts/modules/exporting.js"></script>
<script src="js/highcharts/modules/export-data.js"></script>
<script src="js/highcharts/localization.es.min.js"></script>

<!-- PAGE RELATED PLUGIN(S)
<script src="..."></script>-->

<script type="text/javascript">

    $(document).ready(function () {

        pageSetUp();

        var responsiveHelper_datatable_col_reorder = undefined;
        var array_verificaciones_cero = <?php echo json_encode($array_verificaciones_cero); ?>;

        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };


        var miDataTable = $('#tabla_verificaciones_cero').DataTable({
            scrollY: $('#left-panel').height() - 320,
            scrollX: true,
            stateSave: true,
            sDom: "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
                "t" +
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            oLanguage: {
                "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
            },
            order: [[2, "desc"]],
            autoWidth: false,
            preDrawCallback: function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_datatable_col_reorder) {
                    responsiveHelper_datatable_col_reorder = new ResponsiveDatatablesHelper($('#tabla_verificaciones_cero'), breakpointDefinition);
                }
                var elem_add_verificaciones_cero = $('#add_verificaciones_cero'),
                    elem_ColVis = $('.ColVis');

                if (elem_add_verificaciones_cero.length == 0) {
                    elem_ColVis.prepend('<a type="button" href="edit_verificaciones_cero.php?id=<?php echo $id_analizador; ?>&componente=<?php echo $componente; ?>&nuevo=1" class="btn btn-circle btn-success pull-left margin-right-5" id="add_verificaciones_cero" title="Añadir verificaciones_cero"><i class="fa fa-plus"></i></a>');
                }
            },
            rowCallback: function (nRow) {
                responsiveHelper_datatable_col_reorder.createExpandIcon(nRow);
            },
            drawCallback: function (oSettings) {
                responsiveHelper_datatable_col_reorder.respond();
            },
            columnDefs: [
                {targets: [<?php echo implode(",", $arr_cols_hide); ?>], visible: false}
            ]
        });

        // Apply the filter
        $("#tabla_verificaciones_cero thead th input[type=text]").on('keyup change', function () {
            otable
                .column($(this).parent().index() + ':visible')
                .search(this.value)
                .draw();

        });

        /* END COLUMN FILTER */

        $('#tabla_verificaciones_cero tbody').on('click', 'tr', function () {
            miDataTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        });

        $(document)
            .on('click', '.editar_verificaciones_cero', function () {
                var id_verificaciones_cero = $(this).attr('rel-id');
                window.location = 'edit_verificaciones_cero.php?id_verificaciones_cero=' + id_verificaciones_cero;
            })
            .on('click', '.borrar_verificaciones_cero', function () {
                var id_verificaciones_cero = $(this).attr('rel-id');

                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: 'consultas/PutEasCero.php',
                    data: 'id_verificaciones_cero=' + id_verificaciones_cero + '&del=1',
                    success: function (r) {

                    }
                });
            });

        // Highchart
        var myDateFormat = '%e/%m';

        Highcharts.chart('container', {

            title: {
                text: 'Grafico de control SHEWHART para control deriva y precision de cero.'
            },
            chart: {
                width: 1100,
                height: 600
            },
            xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: {
                    millisecond: myDateFormat,
                    second: myDateFormat,
                    minute: myDateFormat,
                    hour: myDateFormat,
                    day: myDateFormat,
                    week: myDateFormat,
                    month: myDateFormat,
                    year: myDateFormat
                }
            },
            yAxis: {
                title: {
                    text: 'Valor'
                },
                max: <?php echo $max_escala; ?>,
                min: <?php echo $min_escala; ?>
                /* plotLines: [{
                    label: {text: 'Límite alarma (+)'},
                    color: 'yellow', // Color value
                    dashStyle: 'solid', // Style of the plot line. Default to solid
                    value: 2.5, // Value of where the line will appear
                    width: 2 // Width of the line
                },{
                    label: {text: 'Límite alarma (-)'},
                    color: 'yellow', // Color value
                    dashStyle: 'solid', // Style of the plot line. Default to solid
                    value: -2.5, // Value of where the line will appear
                    width: 2 // Width of the line
                },{
                    label: {text: 'Límite actuacion (+)'},
                    color: 'red', // Color value
                    dashStyle: 'solid', // Style of the plot line. Default to solid
                    value: 3.5, // Value of where the line will appear
                    width: 2 // Width of the line
                },{
                    label: {text: 'Límite actuacion (-)'},
                    color: 'red', // Color value
                    dashStyle: 'solid', // Style of the plot line. Default to solid
                    value: -3.5, // Value of where the line will appear
                    width: 2 // Width of the line
                }] */
            },
            tooltip: {
                xDateFormat: '%d-%m-%Y',
            },
            series: [{
                type: 'line',
                name: 'Lectura real',
                data: <?php echo $array_chart; ?>
            }, {
                marker: {enabled: false},
                type: 'line',
                name: 'Límite alarma (-)',
                color: '#ffa600', // Color value
                data: <?php echo $limites[0]; ?>,
                width: 2 // Width of the line
            }, {
                marker: {enabled: false},
                type: 'line',
                name: 'Límite alarma (+)',
                color: '#ffa600', // Color value
                data: <?php echo $limites[1]; ?>,
                width: 2 // Width of the line
            }, {
                marker: {enabled: false},
                type: 'line',
                name: 'Límite actuacion (-)',
                color: 'red', // Color value
                data: <?php echo $limites[2]; ?>,
                width: 2 // Width of the line
            }, {
                marker: {enabled: false},
                type: 'line',
                name: 'Límite actuacion (+)',
                color: 'red', // Color value
                data: <?php echo $limites[3]; ?>,
                width: 2 // Width of the line
            }],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

        });

    })

</script>


<?php require_once('ls_footer2.php'); ?>

