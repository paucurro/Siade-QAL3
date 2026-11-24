<?php require_once('_config.php'); ?>
<?php require_once('ls_check_session.php'); ?>
<?php require_once('Connections/qal3_db.php'); ?>
<?php require_once('libreria.php'); ?>
<?php require_once('ls_cabecera.php'); ?>

<?php

mysqli_select_db($qal3_db, $database_qal3_db);

$row_analizadores = null;
$row_componentes_analizadores = null;
$editando = false;
$array_componentes_formulario = [];

$vuelta_query = '';
if (isset($_REQUEST['tipos'])) {
    $vuelta_query .= 'tipos=' . $_REQUEST['tipos'];
}
if (isset($_REQUEST['lineas'])) {
    $vuelta_query .= (($vuelta_query != '') ? '&' : '') . 'lineas=' . $_REQUEST['lineas'];
}

if (isset($_REQUEST['id'])) {
    $id_analizadores = $_REQUEST['id'];
    $row_analizadores = setSqlDB("SELECT * from analizadores WHERE id_analizadores = " . $id_analizadores . " LIMIT 1");

    $array_componentes_formulario = getAllDataDB("SELECT * FROM analizadores_componentes WHERE fk_analizadores = " . $id_analizadores . " ");

    $editando = true;
}

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
                <li><?php echo ($editando == false) ? "Alta" : "Modificación"; ?></li>
            </ol>

        </div>
        <!-- END RIBBON -->

        <!-- MAIN CONTENT -->
        <div id="content">


            <!-- widget grid -->
            <section id="widget-grid" class="margin-top-20">

                <!-- START ROW -->
                <div class="">

                    <!-- NEW COL START -->
                    <article class="">

                        <!-- Widget ID (each widget will need unique ID)-->
                        <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false"
                             data-widget-fullscreenbutton="false" data-widget-sortable="false">

                            <header>
                                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                                <h2>Insertar analizadores</h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <!-- widget edit box -->
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->

                                </div>
                                <!-- end widget edit box -->

                                <!-- widget content -->
                                <div class="widget-body">

                                    <form id="ins_analizador" class="" novalidate="novalidate">

                                        <?php require_once('ls_form_analizador.php'); ?>

                                        <div class="form-actions clearfix">
                                            <div class=" pull-left">
                                                <a href="analizadores.php<?php echo (($vuelta_query != '') ? '?' . $vuelta_query : ''); ?>" class="btn btn-default" id="volver_lista"><i class="fa fa-arrow-left"></i> Volver lista Analizadores</a>
                                            </div>
                                            <div class="pull-right">
                                                <div class="btn btn-primary" id="guardar_analizador">
                                                    <i class="fa fa-save"></i> Guardar analizador
                                                </div>
                                                <div class="margin-right-20 pull-left" id="show_message"></div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                            <!-- end widget content -->

                        </div>
                        <!-- end widget div -->

                    </article>
                    <!-- END COL -->
                </div>
                <!-- end widget -->
            </section>
        </div>

        <!-- END ROW -->

        </section>
        <!-- end widget grid -->


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

            var arr_componentes = [];
            <?php echo 'arr_componentes = ' . str_replace("},{", "},\n{", json_encode($arr_componentes, JSON_NUMERIC_CHECK)) . ';' . PHP_EOL; ?>

            var rows_componentes = [];
            <?php echo 'rows_componentes = ' . str_replace("},{", "},\n{", json_encode($array_componentes_formulario, JSON_NUMERIC_CHECK)) . ';' . PHP_EOL; ?>

            $('#guardar_analizador').click(function () {

                var data = {},
                    id = null,
                    componente,
                    elem_row,
                    temp_data_row,
                    required,
                    salida = false,
                    contador = 0;

                $('#ins_analizador input, #ins_analizador select, #ins_analizador textarea').each(function (ind, elemento) {

                    id = $(elemento).attr('id');
                    if (id != undefined) {
                        if (id.indexOf('componente_') > -1) {
                            if ($(elemento).is(':checked')) {
                                // Aqui debemos coger todos los campos del componente

                                componente = $(elemento).attr('rel');
                                if (data['componentes'] == undefined) {
                                    data['componentes'] = [];
                                }
                                temp_data_row = {};
                                elem_row = $('#row_' + componente);
                                if (elem_row != undefined) {
                                    temp_data_row['componente'] = componente;
                                    temp_data_row['func_calibracion'] = elem_row.find('.func_calibracion').val();
                                    temp_data_row['limite_alarma_dcs'] = elem_row.find('.limite_alarma_dcs').val();
                                    temp_data_row['valor_limite_emision'] = elem_row.find('.valor_limite_emision').val();
                                    temp_data_row['limite_legal_diario'] = elem_row.find('.limite_legal_diario').val();
                                    temp_data_row['rango_medida'] = elem_row.find('.rango_medida').val();
                                    temp_data_row['incertidumbre_equipo'] = elem_row.find('.incertidumbre_equipo').val();
                                    temp_data_row['unidad'] = elem_row.find('.unidad').val();
                                    data['componentes'].push(temp_data_row);
                                }
                            }
                        } else {
                            required = $(elemento).prop('required');
                            if ($(elemento).is("input")) {
                                data[id] = $(elemento).val();
                            } else if ($(elemento).is("select")) {
                                data[id] = $(elemento).val();
                            } else if ($(elemento).is("textarea")) {
                                data[id] = $(elemento).val();
                            }
                            if ((required == true) && (data[id] == '')) {
                                salida = true;
                                $(elemento).parent('section').addClass('has-error');
                            } else {
                                $(elemento).parent('section').removeClass('has-error');
                            }
                        }
                        contador++;
                    }
                });

                if ((contador > 0) && (salida != true)) {
                    $('#show_message').html('');
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: 'consultas/PutDataAnalizadores.php',
                        data: data,
                        success: function (r) {
                            if (r['resp'] == "OK") {
                                pon_mensaje("Guardado correctamente", "Analizador guardado", "#10b718", "fa-save");
                            } else if (r['resp'] != "OK") {
                                pon_mensaje("Error al guardar", r['data'], "#990000", "fa-warning");
                            }

                            window.location = "analizadores.php<?php echo (($vuelta_query != '') ? '?' . $vuelta_query : ''); ?>";
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            pon_mensaje("Error al guardar", jqXHR.responseText, "#990000", "fa-warning");
                        }
                    });
                } else if ((salida == true)) {
                    $('#show_message').html('<i class="fa fa-warning text-danger"></i> Hay errores en el formulario.');
                }

            });

            function busca_componente(componente) {
                var devuelto = null;
                $.each(rows_componentes, function (ind, row) {
                    if (row['componente'] == componente) {
                        devuelto = row;
                    }
                });
                return devuelto;
            }

            function pon_dato_row($campo, $array) {
                var $valor_campo = '';
                if ($array !== null) {
                    if ($array[$campo] != undefined) {
                        $valor_campo = $array[$campo];
                    }
                }
                return $valor_campo;
            }

            function busca_check(componente) {
                return $('#componente_' + componente).is(':checked');
            }

            function pinta_componentes() {
                var tabla_html = '', $row_componente;
                $.each(arr_componentes, function (ind, $componente) {
                    if (busca_check($componente)) {
                        $row_componente = busca_componente($componente);
                        tabla_html += '<tr id="row_' + $componente + '" data-componente="' + $componente + '">';
                        tabla_html += '    <td style="vertical-align: middle;">' + $componente + '</td>';
                        tabla_html += '    <td style="vertical-align: middle;"><input type="text" class="form-control func_calibracion" placeholder="Func. Calibración" value="' + pon_dato_row('func_calibracion', $row_componente) + '"></td>';
                        tabla_html += '    <td style="vertical-align: middle;"><input type="text" class="form-control limite_alarma_dcs" placeholder="Límite alarma DCS" value="' + pon_dato_row('limite_alarma_dcs', $row_componente) + '"></td>';
                        tabla_html += '    <td style="vertical-align: middle;"><input type="text" class="form-control valor_limite_emision" placeholder="Valor límite emisión" value="' + pon_dato_row('valor_limite_emision', $row_componente) + '"></td>';
                        tabla_html += '    <td style="vertical-align: middle;"><input type="text" class="form-control limite_legal_diario" placeholder="Límite legal diario" value="' + pon_dato_row('limite_legal_diario', $row_componente) + '"></td>';
                        tabla_html += '    <td style="vertical-align: middle;"><input type="text" class="form-control rango_medida" placeholder="Rango medida" value="' + pon_dato_row('rango_medida', $row_componente) + '"></td>';
                        tabla_html += '    <td style="vertical-align: middle;" class="smart-form"><label class="input"> <i class="icon-append fa fa-percent"></i><input type="number" class="incertidumbre_equipo" placeholder="Incertidumbre" value="' + pon_dato_row('incertidumbre_equipo', $row_componente) + '"></label></td>';
                        tabla_html += '    <td style="vertical-align: middle;"><input type="text" class="form-control unidad" placeholder="Unidad" value="' + pon_dato_row('unidad', $row_componente) + '"></td>';
                        tabla_html += '</tr>';
                    }
                });
                $('#tabla_componentes').find('tbody').html(tabla_html);
            }

            $('.check_componente').click(function () {
                pinta_componentes();
            });

            pinta_componentes();
        })

    </script>


<?php require_once('ls_footer2.php'); ?>