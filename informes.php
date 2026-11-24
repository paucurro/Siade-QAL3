<?php require_once('_config.php'); ?>
<?php require_once('ls_check_session.php'); ?>
<?php require_once('Connections/qal3_db.php'); ?>
<?php require_once('libreria.php'); ?>
<?php require_once('ls_cabecera.php'); ?>

<?php

$equipo = $_REQUEST['id'];
$componente = $_REQUEST['componente'];

$row_analizador = setSqlDB('SELECT * FROM analizadores WHERE id_analizadores = ' . $equipo . ' LIMIT 1');

$vuelta_query = '';
if (isset($_REQUEST['tipos'])) {
    $vuelta_query .= 'tipos=' . $_REQUEST['tipos'];
}
if (isset($_REQUEST['lineas'])) {
    $vuelta_query .= (($vuelta_query != '') ? '&' : '') . 'lineas=' . $_REQUEST['lineas'];
}

?>

    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- RIBBON -->
        <div id="ribbon">

            <!-- <span class="ribbon-button-alignment">
               <span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh" rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings."
                      data-html="true">
                    <i class="fa fa-refresh"></i>
                </span>
            </span> -->

            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li>Analizadores ></li>
                <li>Informes</li>
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
                        <div class="margin-bottom-20">
                            <button onclick="window.location='analizadores.php<?php echo(($vuelta_query != '') ? '?' . $vuelta_query : ''); ?>'" class="btn btn-default" id="volver_lista"><i class="fa fa-arrow-left"></i> Volver lista analizadores</button>
                        </div>


                        <!-- Widget ID (each widget will need unique ID)-->
                        <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false"
                             data-widget-custombutton="false" data-widget-colorbutton="false"
                             data-widget-togglebutton="false" data-widget-deletebutton="false"
                             data-widget-fullscreenbutton="false" data-widget-sortable="false">
                            <header>
                                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                                <h2>Generar Informes de analizador <?php echo '<strong>' . $row_analizador['tag'] . '</strong> componente <strong>' . $componente . '</strong>'; ?></h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <!-- widget content -->
                                <div class="widget-body" id="zona_tabla_botellas">

                                    <div class="row">
                                        <div class="col-xs-4 margin-bottom-10" style="height: 600px; border-right: 1px solid #CCCCCC;">
                                            <div class="col-xs-12 margin-bottom-10">
                                                <label for="fecha_desde">Desde</label>
                                                <input type="date" class="form-control" id="fecha_desde" placeholder="Fecha" value="<?php echo date('Y') . '-01-01'; ?>" required>
                                            </div>
                                            <div class="col-xs-12 margin-bottom-20">
                                                <label for="fecha_hasta">Hasta</label>
                                                <input type="date" class="form-control" id="fecha_hasta" placeholder="Fecha" value="<?php echo date('Y') . '-12-31'; ?>" required>
                                            </div>

                                            <div class="col-xs-12 margin-bottom-10">
                                                <button type="button" class="btn btn-primary" id="ejecuta_informe">Ejecutar</button>
                                            </div>
                                        </div>

                                        <div class="col-xs-8 margin-bottom-10">
                                            <div class="col-xs-6 margin-bottom-10">
                                                <h5><strong>Informes generados</strong></h5>
                                            </div>
                                            <div class="col-xs-6 margin-bottom-10">
                                                <button class="btn btn-primary pull-right" id="refrescar_informes"><i class="fa fa-refresh margin-right-3"></i> Refrescar</button>
                                            </div>
                                            <div class="col-xs-12 margin-bottom-10" id="zona_tabla_files">

                                            </div>
                                        </div>
                                    </div>

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

    <script type="text/javascript">


        $(document).ready(function () {
            pageSetUp();

            $('#ejecuta_informe').click(function () {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: 'crea_informe.php',
                    data: {
                        equipo:<?php echo $equipo; ?>,
                        componente: '<?php echo $componente; ?>',
                        fecha_desde: $('#fecha_desde').val(),
                        fecha_hasta: $('#fecha_hasta').val()
                    },
                    success: function (r) {
                        pon_mensaje("Informe lanzado correctamente.", "Pulse el botón refrescar pasados 90 segundos.", "#11b300", "fa-info");
                        setTimeout(function () {
                            load_files_xls();
                        }, 30000);

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        pon_mensaje("Error al escribir comando", jqXHR.responseText, "#990000", "fa-warning");
                    }
                    //window.open('crea_informe.php?equipo=<?php echo $equipo; ?>&componente=<?php echo $componente; ?>&fecha_desde=' + $('#fecha_desde').val() + '&fecha_hasta='+$('#fecha_hasta').val() , '_blank');
                });
            });

            $('#refrescar_informes').click(function () {
                load_files_xls();
            });

            function load_files_xls() {
                var path = 'templatexls/informes/';
                var elem_zona_tabla_files = $('#zona_tabla_files');

                elem_zona_tabla_files.html('<i class="fa fa-2x fa-refresh fa-spin"></i>');

                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: 'files_informes_xls.php',
                    data: {url: path},
                    success: function (files) {

                        var html = '<table class="table table-condensed" style="width: 100%;" id="tabla_archivos_xls">';

                        html += '<tbody>';
                        $.each(files, function (ind, file) {
                            html += '<tr>';
                            html += '    <td><a href="' + path + file + '" target="_blank">' + file + '</a></td>';
                            html += '    <td style="text-align: right;"> <button class="btn btn-xs btn-danger delete_file" title="Eliminar archivo." data-file="' + path + file + '"><i class="fa fa-trash"></i></button></td>';
                            html += '</tr>';
                        });
                        html += '</tbody>';
                        html += '</table>';

                        elem_zona_tabla_files.html(html);

                        $('.delete_file').click(function () {

                            if (confirm('Seguro que quiere eliminar el archivo?')) {

                                var file = $(this).attr('data-file');
                                $.ajax({
                                    type: "POST",
                                    dataType: "JSON",
                                    url: 'files_informes_xls.php',
                                    data: {del: file},
                                    success: function (resp) {
                                        if (resp['resp'] == 'OK') {
                                            load_files_xls();
                                        } else {
                                            pon_mensaje("Error al eliminar el archivo", jqXHR.responseText, "#990000", "fa-warning");
                                        }
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        pon_mensaje("Error al leer archivos", jqXHR.responseText, "#990000", "fa-warning");
                                    }
                                });

                            }

                        });


                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        pon_mensaje("Error al leer archivos", jqXHR.responseText, "#990000", "fa-warning");
                    }
                });


            }

            load_files_xls();

        })


    </script>


<?php require_once('ls_footer2.php'); ?>