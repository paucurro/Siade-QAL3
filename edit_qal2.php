<?php require_once('_config.php'); ?>
<?php require_once('ls_check_session.php'); ?>
<?php require_once('Connections/qal3_db.php'); ?>
<?php require_once('libreria.php'); ?>
<?php require_once('ls_cabecera.php'); ?>

<?php

mysqli_select_db($qal3_db, $database_qal3_db);

$row_analizadores = null;
$row_componentes_analizadores = null;
$id_analizadores = '';
$mensaje_error = '';
$componente = '??';
$nombre_analizador = '??';
$row_qal2 = null;
$nuevo = true;

$vuelta_query = '';
if (isset($_REQUEST['tipos'])) {
    $vuelta_query .= 'tipos=' . $_REQUEST['tipos'];
}
if (isset($_REQUEST['lineas'])) {
    $vuelta_query .= (($vuelta_query != '') ? '&' : '') . 'lineas=' . $_REQUEST['lineas'];
}

if (isset($_REQUEST['id_qal2'])) {
    $nuevo = false;
    $id_qal2 = $_REQUEST['id_qal2'];

    $array_qal2 = getAllDataDB("SELECT * FROM qal2 LEFT JOIN analizadores ON analizadores.id_analizadores = qal2.fk_analizadores WHERE id_qal2 = " . $id_qal2 . " LIMIT 1 ");
    if (count($array_qal2) > 0) {
        $row_qal2 = $array_qal2[0];
    }
    if (isset($row_qal2['id_analizadores'])) $id_analizadores = $row_qal2['id_analizadores'];
    if (isset($row_qal2['componente_qal2'])) {
        $componente = $row_qal2['componente_qal2'];
    } elseif (isset($_REQUEST['componente'])) {
        $componente = $_REQUEST['componente'];
    }
}

if (isset($_REQUEST['id'])) {
    $id_analizadores = $_REQUEST['id'];
}

if ($id_analizadores != '') {
    $row_analizadores = setSqlDB("SELECT * from analizadores WHERE id_analizadores = " . $id_analizadores . " LIMIT 1");
    if (isset($_REQUEST['componente'])) $componente = $_REQUEST['componente'];
    if (isset($row_analizadores['tag'])) {
        $nombre_analizador = $row_analizadores['tag'];
    }
} else {
    $mensaje_error = 'No existe el analizador indicado ' . $id_analizadores . ' con el componente ' . $componente . ' no se puede continuar';
    if (isset($row_qal2['tag'])) {
        $nombre_analizador = $row_qal2['tag'];
    }
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
                <li>Analizadores ></li>
                <li><?php echo $nombre_analizador; ?></li>
                <li><?php echo 'componente: ' . $componente; ?></li>
                <li>QAL2</li>
                <li><?php echo ($nuevo == true) ? "INSERTAR NUEVO" : "EDITAR" ?></li>
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
                                <h2>Formulario <?php echo ($nuevo == true) ? "INSERTAR NUEVO" : "EDITAR" ?> qal2 analizador <?php echo $nombre_analizador; ?> de componente <?php echo $componente; ?></h2>
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
                                    <?php /*
                                            id_qal2
                                            fecha
                                            fk_analizadores
                                            componente
                                            documento
                                            fecha_qal2
                                            intervalo_confianza
                                            funcion_calibrado
                                            rango_calibrado
                                            */ ?>
                                    <?php if ($row_analizadores != false) { ?>
                                        <!--action="PutAnalizadorqal2.php" -->
                                        <form id="form_qal2" novalidate="novalidate" enctype="multipart/form-data">

                                            <fieldset>
                                                <section class="col-xs-6">
                                                    <label for="fecha_qal2">Fecha documento</label>
                                                    <input type="date" class="form-control" id="fecha_qal2" placeholder="Fecha" value="<?php echo pon_dato_row('fecha_qal2', $row_qal2); ?>">
                                                </section>
                                                <section class="col-xs-6 no-padding">
                                                    <label for="fecha_documento_qal2">Fecha subida</label>
                                                    <input type="text" class="form-control" id="fecha_documento_qal2" placeholder="Fecha Doc. QAL2" value="<?php echo pon_dato_row('fecha_documento_qal2', $row_qal2); ?>">
                                                </section>

                                            </fieldset>

                                            <fieldset class="margin-top-5">
                                                <section class="col-xs-6">
                                                    <label for="fecha_qal2_mod">Fecha modificación</label>
                                                    <input type="date" class="form-control" id="fecha_qal2_mod" placeholder="Fecha" value="<?php echo pon_dato_row('fecha_qal2_mod', $row_qal2); ?>">
                                                </section>

                                                <section class="col-xs-6 no-padding" style="min-height: 60px;">
                                                    &nbsp;
                                                </section>


                                            </fieldset>

                                            <fieldset class="margin-top-5">
                                                <section class="col-xs-4 smart-form">
                                                    <label for="intervalo_confianza" class="margin-bottom-5">Intervalo confianza</label>
                                                    <label class="input"> <i class="icon-append fa fa-percent"></i>
                                                        <input type="text" id="intervalo_confianza" placeholder="Intervalo confianza" value="<?php echo pon_dato_row('intervalo_confianza', $row_qal2); ?>">
                                                    </label>
                                                </section>
                                                <section class="col-xs-4 no-padding" style="padding-right: 13px !important;">
                                                    <label for="funcion_calibrado">Función Calibrado</label>
                                                    <input type="text" class="form-control" id="funcion_calibrado" placeholder="Función calibrado" value="<?php echo pon_dato_row('funcion_calibrado', $row_qal2); ?>">
                                                </section>

                                                <section class="col-xs-4 no-padding">
                                                    <label for="rango_calibrado">Rango calibrado</label>
                                                    <input type="text" class="form-control" id="rango_calibrado" placeholder="Rango calibrado" value="<?php echo pon_dato_row('rango_calibrado', $row_qal2); ?>">
                                                </section>

                                                <section class="col-xs-4 margin-top-10" style="padding-right: 13px !important;">
                                                    <label for="serie">Serie</label>
                                                    <input type="text" class="form-control" id="serie" placeholder="Serie" value="<?php echo pon_dato_row('serie', $row_qal2); ?>">
                                                </section>

                                                <section class="col-xs-4 no-padding margin-top-10" style="padding-right: 13px !important;">
                                                    <label for="frecuencia">Frecuencia</label>
                                                    <input type="text" class="form-control" id="frecuencia" placeholder="Frecuencia" value="<?php echo pon_dato_row('frecuencia', $row_qal2); ?>">
                                                </section>

                                                <section class="col-xs-4 no-padding margin-top-10">
                                                </section>

                                                <section class="col-xs-12 no-padding margin-top-10">
                                                </section>

                                                <section class="col-xs-12 margin-top-10" style="padding-right: 0 !important;">
                                                    <section class="col-xs-6 no-padding" style="padding-right: 13px !important;">
                                                        <span class="btn btn-success fileinput-button">
                                                            <i class="glyphicon glyphicon-plus"></i>
                                                            <span>Seleccione fichero ...</span>
                                                            <input id="fileupload" type="file" name="files[]" multiple>
                                                        </span>
                                                        <!-- The global progress bar -->
                                                        <div id="progress" class="progress margin-top-10">
                                                            <div class="progress-bar progress-bar-success"></div>
                                                        </div>
                                                    </section>
                                                    <section class="col-xs-6 no-padding">
                                                        <!-- The container for the uploaded files -->
                                                        <div id="files" class="files" style="border: 1px solid #cccccc !important; min-height: 65px; width: 100%; padding: 4px;">
                                                            <?php
                                                            $docs = pon_dato_row('documento_qal2', $row_qal2);
                                                            if ($docs != '') {
                                                                $documentos = explode(",", $docs);
                                                                if (count($documentos) > 0) {
                                                                    foreach ($documentos as $doc) {
                                                                        echo "<p><button type='button' class='btn btn-xs btn-primary del_file' rel='" . $doc . "'><i class='fa fa-times'></i></button> " . basename($doc) . "</p>";
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </section>
                                                </section>

                                                <input type="hidden" id="id_qal2" name="id_qal2" value="<?php echo pon_dato_row('id_qal2', $row_qal2); ?>"/>
                                                <input type="hidden" id="fk_analizadores" name="fk_analizadores" value="<?php echo $id_analizadores; ?>"/>
                                                <input type="hidden" id="documento_qal2" name="documento_qal2" value="<?php echo pon_dato_row('documento_qal2', $row_qal2); ?>"/>
                                                <input type="hidden" id="componente_qal2" name="componente_qal2" value="<?php echo $componente; ?>"/>
                                            </fieldset>

                                            <div class="form-actions">
                                                <a href="qal2_lista.php?id=<?php echo $id_analizadores; ?>&componente=<?php echo $componente; ?><?php echo(($vuelta_query != '') ? '&' . $vuelta_query : ''); ?>" class="btn btn-default pull-left" id="volver_lista"><i class="fa fa-arrow-left"></i> Volver lista QAL 1</a>
                                                <div class="btn btn-primary" id="guardar_qal2"><i class="fa fa-save"></i> Guardar QAL 2</div>
                                            </div>

                                        </form>

                                    <?php } else {
                                        echo $mensaje_error;
                                    } ?>

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

            'use strict';
            // Change this to the location of your server-side upload handler:
            var url = 'php/',
                elem_documento = $('#documento_qal2'),
                elem_files = $('#files'),
                doc;

            function mete_ficheros_documento() {
                var documentos = '', txt_file;

                elem_files.find('p').each(function (ind, p_files) {
                    if (documentos != '') documentos += ',';
                    txt_file = $(p_files).find('button').attr('rel');
                    if (txt_file != undefined)
                        documentos += txt_file;
                });
                elem_documento.val(documentos);
            }

            $('#fileupload').fileupload({
                url: url,
                dataType: 'json',
                done: function (e, data) {
                    // Cogemos los documentos que ahora hay y los guardamos
                    $.each(data.result.files, function (index, file) {
                        doc = 'files_upload/analizador_' + $('#fk_analizadores').val() + '/' + file.name;
                        $('<p/>').html("<button type='button' class='btn btn-xs btn-primary del_file' rel='" + doc + "'><i class='fa fa-times'></i></button> " + file.name + "").appendTo('#files');
                    });
                    // Colocamos todos los documentos
                    mete_ficheros_documento();
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

            elem_files.on('click', '.del_file', function () {
                var file = $(this).attr('rel'),
                    self = $(this);


                if (file != '') {
                    var data = 'file=' + file + '&fk=' + $('#fk_analizadores').val();
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: 'consultas/DelFile.php',
                        data: data,
                        success: function (r) {
                            if (r['resp'] == "OK") {
                                pon_mensaje((r['data']) ? r['data'] : "Eliminando fichero", "Fichero eliminado", "#10b718", "fa-times");
                                self.parent('p').remove();
                                mete_ficheros_documento();
                            } else {
                                pon_mensaje("Error al guardar", r['data'], "#990000", "fa-warning");
                            }

                        }
                    });
                } else {
                    pon_mensaje("Error al borrar fichero. Está en blanco.", 'Fichero está en blanco', "#990000", "fa-warning");
                }

            });


            $('#guardar_qal2').click(function () {

                var data = {},
                    id = null,
                    rel_componente,
                    contador = 0;

                $('#form_qal2 input, #form_qal2 select, #form_qal2 textarea').each(function (ind, elemento) {

                    id = $(elemento).attr('id');
                    if (id != undefined) {
                        data[id] = $(elemento).val();
                        contador++;
                    }
                });

                if (contador > 0) {
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: 'consultas/Putqal2.php',
                        data: data,
                        success: function (r) {
                            if (r['resp'] == "OK") {
                                pon_mensaje("Guardado correctamente", "QAL2 guardado", "#10b718", "fa-save");
                            } else {
                                pon_mensaje("Error al guardar", r['data'], "#990000", "fa-warning");
                            }

                            <?php if (isset($id_analizadores)) echo 'window.location = "qal2_lista.php?id=' . $id_analizadores . '&componente=' . $componente . (($vuelta_query != '') ? '&' . $vuelta_query : '') . '";'; ?>
                        }
                    });
                }

            });

        })

    </script>


<?php require_once('ls_footer2.php'); ?>