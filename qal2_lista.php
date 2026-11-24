<?php require_once('_config.php'); ?>
<?php require_once('ls_check_session.php'); ?>
<?php require_once('Connections/qal3_db.php'); ?>
<?php require_once('libreria.php'); ?>
<?php require_once('ls_cabecera.php'); ?>

<?php

$array_qal2 = [];

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

    $array_qal2 = getAllDataDB("SELECT qal2.*, analizadores.tag as txt_analizador FROM qal2 LEFT JOIN analizadores ON analizadores.id_analizadores = qal2.fk_analizadores WHERE fk_analizadores = " . $id_analizador . " AND componente_qal2 = '" . $componente . "' ORDER BY fecha_qal2 DESC");

    if (count($array_qal2) == 0) {
        $row_analizador = setSqlDB("SELECT * FROM analizadores WHERE id_analizadores = " . $id_analizador . " LIMIT 1");
        $txt_analizador = $row_analizador['tag'];
        $mensaje_error = 'No hay QAL2 para el analizador indicado ' . $row_analizador['descripcion'] . ' con el componente ' . $componente . '.';
    } else {
        $txt_analizador = $array_qal2[0]['txt_analizador'];
    }
}

function get_icono_file($extension)
{
    switch (strtolower($extension)) {
        case 'gif':
            $icono = 'fa-file-image-o';
            break;
        case 'jpeg':
            $icono = 'fa-file-image-o';
            break;
        case 'jpg':
            $icono = 'fa-file-image-o';
            break;
        case 'png':
            $icono = 'fa-file-image-o';
            break;
        case 'pdf':
            $icono = 'fa-file-pdf-o';
            break;
        case 'doc':
            $icono = 'fa-file-word-o';
            break;
        case 'docx':
            $icono = 'fa-file-word-o';
            break;
        case 'xls':
            $icono = 'fa-file-excel-o';
            break;
        case 'xlsx':
            $icono = 'fa-file-excel-o';
            break;
        case 'odt':
            $icono = 'fa-file-excel-o';
            break;
        case 'ods':
            $icono = 'fa-file-word-o';
            break;
        default:
            $icono = ' fa-file-o';
            break;
    }
    return $icono;
}

function pinta_td($campo, $valor)
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
                    $valor = date("d/m/Y", strtotime($valor));
                    $date_format = ($campo == 'fecha') ? "data-sort='YYYYMMDD'" : "";
                    break;
                case '@filename':
                    if ($valor != '') {
                        $ficheros = (strpos($valor, ",") !== false) ? explode(",", $valor) : [$valor];
                        $valor = '';
                        foreach ($ficheros as $fichero) {
                            $extension = pathinfo($fichero, PATHINFO_EXTENSION);
                            $txt_icono = get_icono_file($extension);
                            $valor .= ' <span class="ver_documento btn btn-xs btn-primary" rel-documento="' . $fichero . '" title="' . $fichero . '"><i class="fa ' . $txt_icono . '"></i></span>';
                        }
                    }
                    break;
                default:
                    $valor = $valor . $valores[1];
                    $add_style .= 'white-space: nowrap; ';
                    break;
            }
        }
    }
    if ($valores[0] != '') $add_style .= 'text-align: ' . $valores[0] . ';';

    return '  <td rel-campo="' . $campo . '" style="' . $add_style . '" ' . $date_format . '>' . $valor . '</td>';
}

$arr_formatos = [
    '' => ['', ''],
    'id_qal2' => ['Id', '', 'hide'],
    'fecha_qal2' => ['Fecha documento', 'left|@date', 'show'],
    'fecha_qal2_mod' => ['Fecha subida', 'left|@date', 'show'],
    'documento_qal2' => ['Documentos', 'left|@filename', 'show'],
    'fecha_documento_qal2' => ['Año doc.', '', 'show'],
    'intervalo_confianza' => ['Intervalo Confianza', '', 'show'],
    'funcion_calibrado' => ['Funcion Calibrado', 'right|', 'show'],
    'rango_calibrado' => ['Rango Calibrado', 'right|', 'show'],
    'serie' => ['Serie', 'right|', 'show'],
    'frecuencia' => ['Frecuencia', 'right|', 'show'],
];
$arr_cols_hide = [];

$tbody_qal2 = '<thead>';
$tbody_qal2 .= '  <tr>';
$int_count = 0;
foreach ($arr_formatos as $contador => $row) {
    if (isset($row[2]) && ($row[2] == 'hide')) {
        $arr_cols_hide[] = $int_count;
    }
    $tbody_qal2 .= '    <th style="white-space: nowrap; text-align: center; max-width: 100px;">' . $row[0] . '</th>';
    $int_count++;
}
$tbody_qal2 .= '  </tr>';
$tbody_qal2 .= '</thead>';


if (count($array_qal2) > 0) {
    $tbody_qal2 .= '<tbody>';
    foreach ($array_qal2 as $pos => $row_qal2) {
        $tbody_qal2 .= '<tr class="' . (($row_qal2['id_qal2'] == $sel_id) ? 'selected' : '') . '" >';
        $tbody_qal2 .= '<td>';
        $tbody_qal2 .= '    <button class="editar_qal2 btn btn-xs btn-success" rel-id="' . $row_qal2['id_qal2'] . '"><i class="fa fa-edit"></i></button>';
        $tbody_qal2 .= '    <button class="borrar_qal2 btn btn-xs btn-danger" rel-id="' . $row_qal2['id_qal2'] . '"><i class="fa fa-times"></i></button>';
        $tbody_qal2 .= '</td>';
        foreach ($arr_formatos as $contador => $row) {
            if ($contador != '') {
                $field = $contador;
                $valor = $row_qal2[$contador];
                $tbody_qal2 .= pinta_td($field, $valor);
            }
        }
        $tbody_qal2 .= '</tr>';
    }
    $tbody_qal2 .= '</tbody>';
} else {
    $tbody_qal2 .= '';
}

$tabla_qal2 = '<table id="tabla_qal2" class="table table-striped table-bordered table-hover" width="100%">';
$tabla_qal2 .= $tbody_qal2;
$tabla_qal2 .= '</table>';

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
            <li>QAL2</li>
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
                    <div class="jarviswidget well" id="wid-id-0a" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-sortable="false">

                        <header>
                            <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                            <h2>Lista de QAL2</h2>
                        </header>

                        <!-- widget div-->
                        <div class="row">

                            <div class="row padding-5 text-center">
                                <div class="pull-left">
                                    <a href="analizadores.php<?php echo (($vuelta_query != '') ? '?' . $vuelta_query : ''); ?>" class="btn btn-default pull-left" id="volver_lista"><i class="fa fa-arrow-left"></i> Volver lista analizadores</a>
                                </div>
                                <div style="width: 70%;float: left;"><h4>QAL2</h4></div>
                                <div style="padding-left: 30px; padding-right: 10px; float: right !important;">
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
                                </div>
                            </div>

                            <!-- widget content -->
                            <div class="widget-body" id="zona_tabla_qal2">
                                <?php echo $tabla_qal2; ?>
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

<?php

$arr_objetos = null;

?>

<!-- PAGE RELATED PLUGIN(S)
<script src="..."></script>-->

<script type="text/javascript">

    $(document).ready(function () {

        pageSetUp();

        var responsiveHelper_datatable_col_reorder = undefined;
        var array_qal2 = <?php echo json_encode($array_qal2); ?>;

        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };


        var miDataTable = $('#tabla_qal2').DataTable({
            scrollY: $('#left-panel').height() - 320,
            scrollX: true,
            stateSave: true,
            sDom: "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
                "t" +
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
            autoWidth: true,
            oLanguage: {
                "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
            },
            "order": [[1, "desc"]],
            preDrawCallback: function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_datatable_col_reorder) {
                    responsiveHelper_datatable_col_reorder = new ResponsiveDatatablesHelper($('#tabla_qal2'), breakpointDefinition);
                }
                var elem_add_qal2 = $('#add_qal2'),
                    elem_ColVis = $('.ColVis');

                if (elem_add_qal2.length == 0) {
                    elem_ColVis.prepend('<a type="button" href="edit_qal2.php?id=<?php echo $id_analizador; ?>&componente=<?php echo $componente; ?>&nuevo=1<?php echo(($vuelta_query != '') ? '&' . $vuelta_query : ''); ?>" class="btn btn-circle btn-success pull-left margin-right-5" id="add_qal2" title="Añadir QAL2"><i class="fa fa-plus"></i></a>');
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
        $("#tabla_qal2 thead th input[type=text]").on('keyup change', function () {
            otable
                .column($(this).parent().index() + ':visible')
                .search(this.value)
                .draw();

        });

        /* END COLUMN FILTER */

        $('#tabla_qal2 tbody').on('click', 'tr', function () {
            miDataTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        });

        $(document)
            .on('click', '.editar_qal2', function () {
                var id_qal2 = $(this).attr('rel-id');
                window.location = 'edit_qal2.php?id_qal2=' + id_qal2 + '<?php echo(($vuelta_query != '') ? '&' . $vuelta_query : ''); ?>';
            })
            .on('click', '.borrar_qal2', function () {
                if (confirm('Seguro que quiere eliminar este Qal2 ?')) {
                    var id_qal2 = $(this).attr('rel-id');
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: 'consultas/PutQal2.php',
                        data: 'id_qal2=' + id_qal2 + '&del=1',
                        success: function (r) {
                            pon_mensaje("Eliminado correctamente", "Qal2 eliminado", "#10b718", "fa-save");
                            window.location = 'qal2_lista.php?id=<?php echo $id_analizador; ?>&componente=<?php echo $componente; ?><?php echo(($vuelta_query != '') ? '&' . $vuelta_query : ''); ?>';
                        }
                    });
                }
            });

        $('.ver_documento').click(function (event) {
            var documento = $(this).attr('rel-documento');
            window.open('<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/qal3/' + documento, '_blank');
        });

    })

</script>


<?php require_once('ls_footer2.php'); ?>

