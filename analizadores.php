<?php require_once('_config.php'); ?>
<?php require_once('ls_check_session.php'); ?>
<?php require_once('Connections/qal3_db.php'); ?>
<?php require_once('libreria.php'); ?>
<?php require_once('ls_cabecera.php'); ?>

<?php

$sel_id = '-1';

if (isset($_REQUEST['sel'])) {
    $sel_id = $_REQUEST['sel'];
}

mysqli_select_db($qal3_db, $database_qal3_db);

$query_string = $_SERVER['QUERY_STRING'];

$filtro = ' WHERE 1 ';
$array_tipos = [];
if (isset($_REQUEST['tipos'])) {
    $array_tipos = explode(',', $_REQUEST['tipos']);
    $filtro .= 'AND tipo_analizador IN ("' . implode('","', $array_tipos) . '") ';
}

$array_lineas = [];
if (isset($_REQUEST['lineas'])) {
    $array_lineas = explode(',', $_REQUEST['lineas']);
    $filtro .= 'AND linea IN ("' . implode('","', $array_lineas) . '") ';
}

$array_analizadores = getAllDataDB('SELECT id_analizadores,tag,linea,tipo_analizador,descripcion,marca,modelo,extractivo,senyal_salida,control_emisiones,tag_senyal_de_control,correccion_humedad,correccion_oxigeno,fecha_instalacion,fecha_ult_revision,estado,observaciones, NULL AS componentes FROM analizadores ' . $filtro . ' ORDER BY tag ASC');

$array_all_analizadores = getAllDataDB('SELECT DISTINCT linea, tipo_analizador FROM analizadores ORDER BY tag ASC');

$array_gases_analizadores = getAllDataDB('SELECT fk_analizadores, GROUP_CONCAT(componente) as componentes FROM analizadores_componentes GROUP BY fk_analizadores;');

function busca_analizador($id_analizador)
{
    global $array_analizadores;

    foreach ($array_analizadores as $pos => $analizador) {
        if ($analizador['id_analizadores'] == $id_analizador) {
            return $pos;
        }
    }
    return false;
}

foreach ($array_gases_analizadores as $row_gases) {
    $pos = busca_analizador($row_gases['fk_analizadores']);
    if ($pos !== false) {
        $array_analizadores[$pos]['componentes'] = $row_gases['componentes'];
    }
}

//$arr_cols_hide = [1, 8, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24];
$row_analizadores = [];
$arr_formatos = [
    '' => ['Control', '', null],
    'id_analizadores' => ['ID', '', false],
    'tag' => ['Tag', '', true],
    'linea' => ['Línea', '', true],
    'tipo_analizador' => ['Tipo', '', true],
    'descripcion' => ['Descripción', '', true],
    'marca' => ['Marca', '', true],
    'modelo' => ['Modelo', '', true],
    'extractivo' => ['Extractivo', 'right|', false],
    'senyal_salida' => ['Señal<br>salida', 'right|', false],
    'control_emisiones' => ['Control<br>emisiones', '', false],
    'tag_senyal_de_control' => ['Tag señal<br>control', '', false],
    'correccion_humedad' => ['Corrección<br>humedad', 'right|', false],
    'correccion_oxigeno' => ['Corrección<br>Oxígeno', 'right|', false],
    'fecha_instalacion' => ['Fecha<br>instalacion', 'right|@date', false],
    'fecha_ult_revision' => ['Fecha<br>U.Revisión', 'right|@date', false],
    'estado' => ['Estado', '', false],
    'observaciones' => ['Observaciones', '', false],
    'componentes' => ['Componentes', 'left|array', true],
];

function pinta_td($campo, $valor, $fk_botella, $hide = false)
{
    global $arr_formatos, $query_string;

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
                case 'array':
                    $tmp_valor = '';
                    if (($valor != '') && ($valor != null)) {
                        $vals_array = explode(",", $valor);
                        foreach ($vals_array as $componente) {
                            //$tmp_valor .= '<a href="#" rel-comp="' . $componente . '" style="text-decoration: underline;">' . $componente . '</a> ';
                            $tmp_valor .= '<div class="btn-group margin-right-3 margin-bottom-3">
											<button class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">' . $componente . ' <span class="caret"></span></button>
											<ul class="dropdown-menu dropdown-menu-right">
												<li><a href="qal1_lista.php?id=' . $fk_botella . '&componente=' . $componente . '&' . $query_string . '"><i class="fa fa-gear"></i> QAL1 </a></li>
												<li><a href="qal2_lista.php?id=' . $fk_botella . '&componente=' . $componente . '&' . $query_string . '"><i class="fa fa-gears"></i> QAL2 </a></li>
												<li><a href="verificaciones_cero.php?id=' . $fk_botella . '&componente=' . $componente . '&' . $query_string . '"><i class="fa fa-flask"></i> Ver.Cero </a></li>
												<li><a href="verificaciones_rango.php?id=' . $fk_botella . '&componente=' . $componente . '&' . $query_string . '"><i class="fa fa-flask "></i> Ver.Rango </a></li>
												<li><a href="informes.php?id=' . $fk_botella . '&componente=' . $componente . '&' . $query_string . '"><i class="fa fa-print"></i> Imprimir </a></li>
											</ul>
										</div>';
                            // $tmp_valor .= '<a href="#" rel-comp="' . $componente . '" style="text-decoration: underline;">' . $componente . '</a> ';
                        }
                    }
                    $valor = $tmp_valor;
                    break;
                default:
                    $valor = $valor . $valores[1];
                    break;
            }
        } else {
            $add_style .= 'white-space: nowrap; ';
        }
    }
    if ($hide == true) {
        $add_style .= 'display: none; ';
    }
    if ($valores[0] != '') $add_style .= 'text-align: ' . $valores[0] . ';';

    return '  <td rel-campo="' . $campo . '" style="' . $add_style . '" ' . $date_format . '>' . $valor . '</td>';
}

$arr_cols_hide = [];
$index = 0;
$tbody_analizadores = '<thead>';
$tbody_analizadores .= '  <tr>';
foreach ($arr_formatos as $contador => $row) {
    $tbody_analizadores .= '    <th style="white-space: nowrap; text-align: left; max-width: 100px;">' . $row[0] . '</th>';
    if (isset($row[2]) && ($row[2] == false)) {
        $arr_cols_hide[] = $index;
    }
    $index++;
}
$tbody_analizadores .= '  </tr>';
$tbody_analizadores .= '</thead>';
$tbody_analizadores .= '<tbody>';

if (count($array_analizadores) > 0) {
    foreach ($array_analizadores as $pos => $row_analizador) {
        $tbody_analizadores .= '<tr class="' . (($row_analizador['id_analizadores'] == $sel_id) ? 'selected' : '') . '" >';
        $tbody_analizadores .= '<td class="td_nowrap">';
        $tbody_analizadores .= '    <div class="btn-group td_nowrap">';
        $tbody_analizadores .= '        <button class="btn btn-primary btn-xs margin-right-3 editar_analizador" rel-id="' . $row_analizador['id_analizadores'] . '" title="Modificar analizador"><i class="fa fa-edit"></i></button>';
        $tbody_analizadores .= '        <button class="btn btn-danger btn-xs margin-right-3 borrar_analizador" rel-id="' . $row_analizador['id_analizadores'] . '" title="Eliminar analizador"><i class="fa fa-times"></i></button>';
        $tbody_analizadores .= '        <a href="informes_todos.php?id=' . $row_analizador['id_analizadores'] . '" class="btn btn-info btn-xs margin-right-3" target="blank" title="Imprimir todos los componentes"><i class="fa fa-print"></i></a>';
        $tbody_analizadores .= '    </div>';
        $tbody_analizadores .= '</td>';
        $contador = 0;
        foreach ($row_analizador as $field => $valor) {
            $tbody_analizadores .= pinta_td($field, $valor, $row_analizador['id_analizadores']);
            $contador++;
        }
        $tbody_analizadores .= '</tr>';
    }
    $tbody_analizadores .= '</tbody>';
}

$tabla_analizadores = '<table id="tabla_analizadores" class="table table-striped table-bordered table-hover" style="width: 100%; ">';
$tabla_analizadores .= $tbody_analizadores;
$tabla_analizadores .= '</table>';


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
            <li>Lista</li>
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
                            <h2>Lista de analizadores</h2>
                        </header>

                        <!-- widget div-->
                        <div class="row">
                            <!-- widget content -->
                            <div class="widget-body" id="zona_tabla_analizadores">
                                <div class="row margin-bottom-10">
                                    <div class="col-xs-8" id="zona_selectores_filtro"></div>
                                    <div class="col-xs-4">
                                        <a type="button" href="analizadores_ins.php?nuevo=1<?php echo(($query_string != '') ? '&' . $query_string : ''); ?>" class="btn btn-circle btn-success pull-right margin-right-5" id="add_analizador" title="Añadir Analizador"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>

                                <?php echo $tabla_analizadores; ?>

                            </div>
                            <!-- end widget content -->

                        </div>
                        <!-- end widget div -->

                    </div>

                </article>
            </div>
        </section>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Ficha del analizador</h4>
                    </div>
                    <div class="modal-body">
                        <?php require_once('ls_form_analizador.php'); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

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
        var array_analizadores = <?php echo json_encode($array_analizadores); ?>;
        var elem_selector_linea = null;
        var elem_selector_tipo = null;

        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };


        var miDataTable = $('#tabla_analizadores').DataTable({
            // pageLength: 12,
            stateSave: true,
            /*sDom: "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
                "t" +
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",*/
            autoWidth: true,
            preDrawCallback: function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_datatable_col_reorder) {
                    responsiveHelper_datatable_col_reorder = new ResponsiveDatatablesHelper($('#tabla_analizadores'), breakpointDefinition);
                }

                if (!elem_selector_tipo) {
                    var select_linea, select_tipo;
                    <?php
                    $temp_array_lineas = [];
                    $temp_array_tipos = [];
                    $select_linea = '';
                    $select_tipo = '';
                    foreach ($array_all_analizadores as $row_analizador) {
                        if (!in_array($row_analizador['linea'], $temp_array_lineas)) {
                            $temp_array_lineas[] = $row_analizador['linea'];
                            $select_linea .= "'<option value=\"" . $row_analizador['linea'] . "\" " . (in_array($row_analizador['linea'], $array_lineas) ? 'selected=\"selected\"' : '') . ">" . $row_analizador['linea'] . "</option>' + " . PHP_EOL;
                        }
                        if (!in_array($row_analizador['tipo_analizador'], $temp_array_tipos)) {
                            $temp_array_tipos[] = $row_analizador['tipo_analizador'];
                            $select_tipo .= "'<option value=\"" . $row_analizador['tipo_analizador'] . "\" " . (in_array($row_analizador['tipo_analizador'], $array_tipos) ? 'selected=\"selected\"' : '') . ">" . $row_analizador['tipo_analizador'] . "</option>' + " . PHP_EOL;
                        }
                    }

                    echo "select_linea = " . PHP_EOL .
                        "'<div class=\"col-xs-10 col-lg-3\" style=\"\">' + " . PHP_EOL .
                        "'<label style=\"width: 100%;\">' + " . PHP_EOL .
                        "'<select multiple class=\"select2 columna_search_3\" placeholder=\"Línea\" id=\"selector_linea\" style=\"width:100%;\">' + " . PHP_EOL .
                        $select_linea .
                        "'</select>' + " . PHP_EOL .
                        "'</label>' + " . PHP_EOL .
                        "'</div>'; " . PHP_EOL .
                        PHP_EOL .
                        "select_tipo = " . PHP_EOL .
                        "'<div class=\"col-xs-12 col-lg-7\" style=\"\">' + " . PHP_EOL .
                        "'<label style=\"width: 100%;\">' + " . PHP_EOL .
                        "'<select multiple class=\"select2 columna_search_4\" placeholder=\"Tipo\" id=\"selector_tipo\" style=\"width: 100%;\">' + " . PHP_EOL .
                        $select_tipo .
                        "'</select>' + " . PHP_EOL .
                        "'</label>' + " . PHP_EOL .
                        "'</div>';" . PHP_EOL;
                    ?>



                    $('#zona_selectores_filtro').html(
                        '<div class="row">' +
                        select_linea +
                        select_tipo +
                        '<div class="col-xs-2"><button class="btn btn-primary" id="filtra_analizadores" title="Filtrar analizadores"><i class="fa fa-search"></i></button>' +
                        '</div>'
                    );

                    elem_selector_linea = $('#selector_linea');
                    elem_selector_tipo = $('#selector_tipo');

                    elem_selector_linea.select2({
                        placeholder: "Filtro de línea/s",
                        allowClear: true
                    });
                    elem_selector_tipo.select2({
                        placeholder: "Filtro de tipo/s",
                        allowClear: true
                    });

                    $('#filtra_analizadores').click(function () {
                        var tipos = elem_selector_tipo.val();
                        var lineas = elem_selector_linea.val();
                        var str_lineas = (lineas) ? '?lineas=' + lineas.join(',') : '';
                        var str_tipos = ((tipos) ? ((str_lineas == '') ? '?' : '&') + 'tipos=' + tipos.join(',') : '');
                        window.location = 'analizadores.php' + str_lineas + str_tipos;
                    });

                }

                // <select multiple style="width:100%" class="select2">
                //     <option value="AK">Alaska</option>
                //     <option value="HI">Hawaii</option>


            },
            oLanguage: {
                "sUrl": "js/plugin/datatables/Spanish.json"
            },
            rowCallback: function (nRow) {
                responsiveHelper_datatable_col_reorder.createExpandIcon(nRow);
            },
            drawCallback: function (oSettings) {
                responsiveHelper_datatable_col_reorder.respond();
            },
            order: [
                [1, "asc"]
            ],
            columnDefs: [
                {targets: [<?php echo implode(",", $arr_cols_hide); ?>], visible: false},
                {targets: 0, orderable: false}
            ]
        });

        setTimeout(function () {
            $('#tabla_analizadores').show();
        }, 1000);

        // Apply the filter
        $("#tabla_analizadores thead th input[type=text]").on('keyup change', function () {

            otable
                .column($(this).parent().index() + ':visible')
                .search(this.value)
                .draw();

        });

        /* END COLUMN FILTER */

        function rellena_formulario(pos_analizador) {
            var field,
                elem,
                componentes,
                row = array_analizadores[pos_analizador];

            $('input[type=checkbox]').attr('checked', false);

            $.each(row, function (field, data) {
                if (field != '') {
                    elem = $('#' + field);
                    if (field == 'componentes') {
                        if ((data != '') && (data != null)) {
                            componentes = data.split(",");
                            $.each(componentes, function (ind_comp, comp) {
                                elem = $('#componente_' + comp);
                                if (elem.length > 0) elem.prop('checked', true);
                            });
                        }
                    } else {
                        if (elem.length > 0) elem.val(data);
                    }
                }
            })

        }

        $('#tabla_analizadores tbody').on('click', 'tr', function () {
            miDataTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        });

        $(document)
            /*.on('click', '.ver_analizador', function () {
                var pos_analizador = $(this).attr('rel-pos');
                rellena_formulario(pos_analizador);
                $('#form_botellas').find('input,select,textarea').attr('disabled', true);
                $('#myModal').modal('show');
            })*/
            .on('click', '.editar_analizador', function () {
                var id_analizador = $(this).attr('rel-id');
                window.location = 'analizadores_ins.php?id=' + id_analizador + '<?php echo(($query_string != '') ? '&' . $query_string : ''); ?>';
            })
            .on('click', '.borrar_analizador', function () {
                if (confirm('Seguro que quiere eliminar este analizador ?')) {
                    var id_analizador = $(this).attr('rel-id');
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: 'consultas/DelAnalizador.php',
                        data: 'id=' + id_analizador,
                        success: function (r) {
                            pon_mensaje("Eliminado correctamente", "Analizador eliminado", "#10b718", "fa-save");
                            window.location = 'analizadores.php<?php echo(($query_string != '') ? '?' . $query_string : ''); ?>';
                        }
                    });
                }

            });

    })

</script>

<?php require_once('ls_footer2.php'); ?>

