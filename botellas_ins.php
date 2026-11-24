<?php require_once('_config.php'); ?>
<?php require_once('ls_check_session.php'); ?>
<?php require_once('Connections/qal3_db.php'); ?>
<?php require_once('libreria.php'); ?>
<?php require_once('ls_cabecera.php'); ?>

<?php

mysqli_select_db($qal3_db, $database_qal3_db);

$row_botellas = null;
$row_componentes_botellas = null;

$array_tipos_botellas = getAllDataDB("SELECT * FROM tipos_botellas ");

$array_componentes_botellas = [];

if (isset($_REQUEST['id'])) {
    $id_botellas = $_REQUEST['id'];
    $row_botellas = setSqlDB("SELECT * from botellas WHERE id_botellas = " . $id_botellas . " LIMIT 1");

    $array_componentes_botellas = getAllDataDB("SELECT * from botellas_componentes WHERE fk_botellas = " . $id_botellas . " ");
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
                </span>
            </span> -->

                <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li>Botellas ></li>
                <li>Alta</li>
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
                        <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false"
                             data-widget-custombutton="false" data-widget-colorbutton="false"
                             data-widget-togglebutton="false" data-widget-deletebutton="false"
                             data-widget-fullscreenbutton="false" data-widget-sortable="false">

                            <header>
                                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                                <h2>Insertar botellas</h2>
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

                                    <form id="ins_botella" class="" novalidate="novalidate">
                                        <fieldset>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <section>
                                                        <label for="fk_tipos_botellas">Tipo de botella</label>
                                                        <select style="width:100%" class="select2" id="fk_tipos_botellas" required>
                                                            <option value="">Seleccione un Tipo de botella</option>
                                                            <?php
                                                            if (count($array_tipos_botellas) > 0) {
                                                                foreach ($array_tipos_botellas AS $ind => $tipo_botella) {
                                                                    echo '<option value="' . $tipo_botella['id_tipos_botellas'] . '" ' . pon_selected($tipo_botella['id_tipos_botellas'], 'fk_tipos_botellas', $row_botellas) . '>[' . $tipo_botella['id_sap_botella'] . '] - ' . $tipo_botella['descripcion'] . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </section>
                                                </div>
                                                <div class="form-group smart-form">
                                                    <section>
                                                        <label style="margin-bottom: 6px;">Adjunto</label>
                                                        <div class="input input-file">
                                                            <span class="button">
                                                                <input id="foto_search" type="file" name="files[]">Buscar <!--  accept="application/pdf" -->
                                                            </span>
                                                            <input type="text" placeholder="Seleccionar un JPG o PDF" id="foto_nombre" value="<?php echo pon_dato_row('foto_nombre', $row_botellas); ?>" readonly="">
                                                        </div>
                                                        <div class="col col-4 no-margin">
                                                            <a id="ver_foto" href="<?php echo str_replace('%2F', '/', rawurlencode(pon_dato_row('foto_nombre', $row_botellas))); ?>" target="_blank" class="btn btn-primary margin-top-5">
                                                                <i class="fa fa-eye"></i> Ver/Descargar
                                                            </a>
                                                            <span class="btn btn-danger margin-top-5 eliminar_imagen" rel="<?php echo pon_dato_row('foto_nombre', $row_botellas); ?>">
                                                                <i class="fa fa-times"></i> Quitar
                                                            </span>
                                                        </div>
                                                        <div class="col col-8 no-margin">
                                                            <div id="progress" class="progress margin-top-10">
                                                                <div class="progress-bar progress-bar-success"></div>
                                                            </div>
                                                        </div>
                                                    </section>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <section class="col-xs-6">
                                                            <label for="estado">Estado botella</label>
                                                            <select class="form-control" id="estado">
                                                                <option value="PRECINTADA" <?php echo pon_selected('PRECINTADA', 'estado', $row_botellas); ?>>
                                                                    PRECINTADA
                                                                </option>
                                                                <option value="EN USO" <?php echo pon_selected('EN USO', 'estado', $row_botellas); ?>>
                                                                    EN USO
                                                                </option>
                                                                <option value="AGOTADA" <?php echo pon_selected('AGOTADA', 'estado', $row_botellas); ?>>
                                                                    AGOTADA
                                                                </option>
                                                                <option value="CADUCADA" <?php echo pon_selected('CADUCADA', 'estado', $row_botellas); ?>>
                                                                    CADUCADA
                                                                </option>
                                                            </select> <i></i>
                                                        </section>
                                                        <section class="col-xs-6">
                                                            <label for="n_botella">Nº. botella</label>
                                                            <input class="form-control" id="n_botella" placeholder="Número de botella" value="<?php echo pon_dato_row('n_botella', $row_botellas); ?>" required>
                                                        </section>
                                                        <section class="col-xs-6">
                                                            <label for="n_certificado">Nº certificado</label>
                                                            <input class="form-control" id="n_certificado" placeholder="Número de certificado" value="<?php echo pon_dato_row('n_certificado', $row_botellas); ?>" required>
                                                        </section>
                                                        <section class="col-xs-6">
                                                            <label for="n_pr">Nº PR</label>
                                                            <input class="form-control" id="n_pr" placeholder="Número de pr" value="<?php echo pon_dato_row('n_pr', $row_botellas); ?>" required>
                                                        </section>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <section class="col-xs-6">
                                                            <label for="descripcion">Descripción</label>
                                                            <input type="text" class="form-control" id="descripcion" placeholder="Descripción" value="<?php echo pon_dato_row('descripcion', $row_botellas); ?>" required>
                                                        </section>
                                                        <section class="col-xs-6 smart-form">
                                                        </section>
                                                    </div>
                                                    <div class="row">
                                                        <section class="col-xs-6">
                                                            <label for="fecha_caducidad">Fecha caducidad</label>
                                                            <input type="date" class="form-control" id="fecha_caducidad" placeholder="Fecha" value="<?php echo pon_fecha_datetime(pon_dato_row('fecha_caducidad', $row_botellas)); ?>" required>
                                                        </section>
                                                        <section class="col-xs-6">
                                                        </section>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <section>
                                                        <label>Lista de componentes</label>
                                                        <div class="form-group" id="zona_tabla_componentes" style="min-height: 200px; border: 1px solid #cccccc;"></div>
                                                    </section>
                                                </div>

                                                <div class="form-group">
                                                    <section>
                                                        <label>Información</label>
                                                        <textarea id="informacion" class="form-control" placeholder="Información" rows="4"><?php echo pon_dato_row('informacion', $row_botellas); ?></textarea>
                                                    </section>
                                                    <section>
                                                        <label>Observaciones</label>
                                                        <textarea id="observaciones" class="form-control" placeholder="Observaciones" rows="4"><?php echo pon_dato_row('observaciones', $row_botellas); ?></textarea>
                                                    </section>
                                                </div>

                                            </div>

                                        </fieldset>

                                        <div class="form-actions clearfix">
                                            <a href="botellas.php" class="btn btn-default pull-left" id="volver_lista">
                                                <i class="fa fa-arrow-left"></i> Volver lista Botellas
                                            </a>
                                            <div class="pull-right">
                                                <div class="btn btn-primary" id="guardar_botella">
                                                    <i class="fa fa-save"></i>
                                                    Guardar botella
                                                </div>
                                                <div class="margin-right-20 pull-left" id="show_message"></div>
                                            </div>
                                        </div>

                                        <input type="hidden" id="id_botellas" value="<?php echo pon_dato_row('id_botellas', $row_botellas); ?>">
                                        <input type="hidden" id="foto" value="<?php echo pon_dato_row('foto', $row_botellas); ?>">
                                    </form>

                                </div>
                                <!-- end widget content -->

                            </div>
                            <!-- end widget div -->

                        </div>
                        <!-- end widget -->

                    </article>
                    <!-- END COL -->

                </div>

                <!-- END ROW -->

            </section>
            <!-- end widget grid -->

            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <img id="foto_view" src="" class="img-responsive" style=""/>
                                </div>
                            </div>

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

    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="js/upload_file/jquery.ui.widget.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="js/upload_file/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="js/upload_file/jquery.fileupload.js"></script>
    <!-- Bootstrap JS is not required, but included for the responsive demo navigation -->

<?php

$arr_objetos = null;

?>

    <!-- PAGE RELATED PLUGIN(S)
    <script src="..."></script>-->

    <script type="text/javascript">

        $(document).ready(function () {

            pageSetUp();

            var array_componentes_botellas = <?php echo (count($array_componentes_botellas) > 0) ? json_encode($array_componentes_botellas, JSON_FORCE_OBJECT) : '{}'; ?>;
            var datos_componentes = [];

            'use strict';
            // Change this to the location of your server-side upload handler:
            var url = 'php/index.php?op=id_botellas',
                doc;

            $('#foto_search').fileupload({
                url: url,
                dataType: 'json',
                done: function (e, data) {
                    // Cogemos los documentos que ahora hay y los guardamos
                    $.each(data.result.files, function (index, file) {
                        doc = 'files_upload/botellas/' + file.name;
                        $('#foto_nombre').val(doc);
                        $('.eliminar_imagen').attr('rel', doc);
                        $('#ver_foto').attr('href', encodeURI(doc));
                    });
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .progress-bar').css(
                        'width',
                        progress + '%'
                    );
                }
            }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');

            $('.eliminar_imagen').on('click', function () {
                var file = $(this).attr('rel'),
                    self = $(this);


                if (file != '') {
                    var data = 'file=' + file + '&fk=' + $('#id_botellas').val();
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: 'consultas/DelFileBotellas.php',
                        data: data,
                        success: function (r) {
                            if (r['resp'] == "OK") {
                                pon_mensaje((r['data']) ? r['data'] : "Eliminando fichero", "Fichero eliminado", "#10b718", "fa-times");
                                self.parent('p').remove();
                                $('#foto_nombre').val('');
                                $('#ver_foto').attr('href', '#');
                            } else {
                                pon_mensaje("Error al guardar", r['data'], "#990000", "fa-warning");
                            }

                        }
                    });
                } else {
                    pon_mensaje("Error al borrar fichero. Está en blanco.", 'Fichero está en blanco', "#990000", "fa-warning");
                }

            });

            $('#guardar_botella').click(function () {

                var data = {},
                    id = null,
                    file,
                    rel_componente,
                    rel_id,
                    required,
                    salida = false,
                    contador = 0;

                $('#ins_botella input, #ins_botella select, #ins_botella textarea').each(function (ind, elemento) {

                    id = $(elemento).attr('id');
                    if (id != undefined) {
                        rel_id = $(elemento).attr('rel-field');
                        rel_componente = $(elemento).attr('rel_componente');
                        if ((rel_id == 'porcentaje') || (rel_id == 'mg_nm3') || (rel_id == 'ppm') || (rel_id == 'incertidumbre')) {
                            if (data['componentes'] == undefined) {
                                data['componentes'] = {};
                            }
                            if (data['componentes'][rel_componente] == undefined) {
                                data['componentes'][rel_componente] = {};
                            }
                            data['componentes'][rel_componente][rel_id] = $(elemento).val();
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
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: 'consultas/PutDataBotellas.php',
                        data: {putData: JSON.stringify(data)},
                        success: function (r) {
                            if (r['resp'] == "OK") {
                                pon_mensaje("Guardado correctamente", "Botella guardada", "#10b718", "fa-save");

                                window.location = 'botellas.php';
                            } else {
                                pon_mensaje("Error al guardar", r['data'], "#990000", "fa-warning");
                            }
                        }
                    });
                } else if ((salida == true)) {
                    $('#show_message').html('<i class="fa fa-warning text-danger"></i> Hay errores en el formulario.');
                }

            });

            $('#fk_tipos_botellas').on('change', function () {
                coge_tipos_botellas($(this).find('option:selected').val())
            });

            <?php
            if (isset($row_botellas['fk_tipos_botellas'])) {
                echo 'coge_tipos_botellas(' . $row_botellas['fk_tipos_botellas'] . ');';
            }
            echo PHP_EOL;
            ?>

            function busca_componente(componente) {
                var arr_componente = [];
                if ($.isEmptyObject(array_componentes_botellas) === false) {
                    $.each(array_componentes_botellas, function (ind, row) {
                        if (componente === row['componente']) {
                            arr_componente = row;
                            return false;
                        }
                    });
                }
                return arr_componente;
            }

            function pon_dato_row(campo, array) {
                var valor_campo = '';
                if ((array !== null) && (array != undefined)) {
                    if (array[campo] !== undefined) {
                        valor_campo = (array[campo] == null) ? '' : array[campo];
                    }
                }
                return valor_campo;
            }


            function coge_tipos_botellas(id_tipos_botellas) {
                if ((id_tipos_botellas != undefined) && (id_tipos_botellas != '')) {
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: 'consultas/GetComponentesTiposBotellas.php',
                        data: {id: id_tipos_botellas},
                        success: function (r) {
                            if (r['resp'] == "OK") {

                                datos_componentes = r['data'];
                                var tabla_html = '', es_o2 = false;

                                tabla_html += '<table class="table table-condensed table-no-border" style="font-size: 13px;">';
                                tabla_html += '<thead>';
                                tabla_html += '  <tr>';
                                tabla_html += '    <th>Componente</th>';
                                tabla_html += '    <th style="text-align: center;">%Vol.</th>';
                                tabla_html += '    <th style="text-align: center;">Mg/Nm3</th>';
                                tabla_html += '    <th style="text-align: center;">PPM</th>';
                                tabla_html += '    <th>Incertidumbre</th>';
                                tabla_html += '  </tr>';
                                tabla_html += '</thead>';
                                tabla_html += '<tbody>';

                                $.each(datos_componentes, function (ind, row) {
                                    es_o2 = $.inArray(row['componente'], ['H2O', 'O2', 'CO2']);

                                    tabla_html += '<tr class="linea_padre">';
                                    tabla_html += '  <td style="vertical-align: middle;">' + row['componente'] + '  </td>';
                                    tabla_html += '  <td style="text-align: right;">';
                                    tabla_html += '    <input type="text" id="' + row['componente'] + '_1" class="form-control" rel_componente="' + row['componente'] + '" rel-field="porcentaje" ' + ((es_o2 > -1) ? '' : 'disabled="disabled"') + ' value="' + pon_dato_row('porcentaje', busca_componente(row['componente'])) + '">';
                                    tabla_html += '  </td>';
                                    tabla_html += '  <td style="text-align: right;">';
                                    tabla_html += '    <input type="text" id="' + row['componente'] + '_2" class="form-control calcula_operador" rel-operador="' + row['tb_operador'] + '" rel_componente="' + row['componente'] + '" rel-field="mg_nm3" ' + ((es_o2 > -1) ? 'disabled="disabled"' : '') + ' value="' + pon_dato_row('mg_nm3', busca_componente(row['componente'])) + '">';
                                    tabla_html += '  </td>';
                                    tabla_html += '  <td style="text-align: right;">';
                                    tabla_html += '    <input type="text" id="' + row['componente'] + '_3" class="form-control calcula_operador" rel-operador="' + row['tb_operador'] + '" rel_componente="' + row['componente'] + '" rel-field="ppm" ' + ((es_o2 > -1) ? 'disabled="disabled"' : '') + ' value="' + pon_dato_row('ppm', busca_componente(row['componente'])) + '">';
                                    tabla_html += '  </td>';
                                    tabla_html += '  <td style="text-align: right;">';
                                    tabla_html += '    <input type="text" id="' + row['componente'] + '_4" class="form-control" rel_componente="' + row['componente'] + '" rel-field="incertidumbre" value="' + pon_dato_row('incertidumbre', busca_componente(row['componente'])) + '">';
                                    tabla_html += '  </td>';
                                    tabla_html += '</tr>';
                                });

                                tabla_html += '</tbody>';
                                tabla_html += '</table>';

                                $('#zona_tabla_componentes').html(tabla_html);

                            } else {

                            }
                        }
                    });
                }
            }

            var dentro_cambio = false;
            $(document).on('change', '.calcula_operador', function () {
                if (dentro_cambio === false) {
                    dentro_cambio = true;
                    var id = $(this).attr('rel-field'),
                        componente = $(this).attr('rel_componente'),
                        valor = $(this).val(),
                        operador = $(this).attr('rel-operador'),
                        valor_mg3,
                        calculo = 0;

                    if (id === 'mg_nm3') {
                        calculo = valor / operador;
                        $('#' + componente + '_3').val(calculo.toFixed(3));

                        if (componente == 'C3H8') {
                            valor_mg3 = valor * 36 / 44;
                            $('#C_2').val(valor_mg3.toFixed(3));
                        }

                    } else {
                        calculo = valor * operador;
                        $('#' + componente + '_2').val(calculo.toFixed(3));

                        if (componente == 'C3H8') {
                            valor_mg3 = calculo * 36 / 44;
                            $('#C_2').val(valor_mg3.toFixed(3));
                        }
                    }

                    dentro_cambio = false;
                }
            });

        })

    </script>


<?php require_once('ls_footer2.php'); ?>