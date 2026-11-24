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
$row_qal1 = null;
$nuevo = true;

$vuelta_query = '';
if (isset($_REQUEST['tipos'])) {
    $vuelta_query .= 'tipos=' . $_REQUEST['tipos'];
}
if (isset($_REQUEST['lineas'])) {
    $vuelta_query .= (($vuelta_query != '') ? '&' : '') . 'lineas=' . $_REQUEST['lineas'];
}

if (isset($_REQUEST['id_qal1'])) {
    $nuevo = false;
    $id_qal1 = $_REQUEST['id_qal1'];

    $array_qal1 = getAllDataDB("SELECT * FROM qal1 LEFT JOIN analizadores ON analizadores.id_analizadores = qal1.fk_analizadores WHERE id_qal1 = " . $id_qal1 . " LIMIT 1 ");
    if (count($array_qal1) > 0) {
        $row_qal1 = $array_qal1[0];
    }
    if (isset($row_qal1['id_analizadores'])) $id_analizadores = $row_qal1['id_analizadores'];
    if (isset($row_qal1['componente_qal1'])) {
        $componente = $row_qal1['componente_qal1'];
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
    $nombre_analizador = $row_analizadores['tag'];
} else {
    $mensaje_error = 'No existe el analizador indicado ' . $id_analizadores . ' con el componente ' . $componente . ' no se puede continuar';
    if (isset($row_qal1['tag'])) {
        $nombre_analizador = $row_qal1['tag'];
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
                <li>Analizadores</li>
                <li><?php echo $nombre_analizador; ?></li>
                <li><?php echo 'componente: ' . $componente; ?></li>
                <li>QAL 1</li>
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
                                <h2>Formulario <?php echo ($nuevo == true) ? "INSERTAR NUEVO" : "EDITAR" ?> QAL1 analizador <?php echo $nombre_analizador; ?> de componente <?php echo $componente; ?></h2>
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

                                    <?php if ($row_analizadores != false) { ?>
                                        <!--action="PutAnalizadorQal1.php" -->
                                        <form class="form_analizadores" novalidate="novalidate" enctype="multipart/form-data">

                                            <fieldset id="form_qal1">
                                                <section class="col-xs-6">
                                                    <label for="certificado">Certificado</label>
                                                    <input type="text" class="form-control" id="certificado" placeholder="Certificado" value="<?php echo pon_dato_row('certificado', $row_qal1); ?>">
                                                </section>
                                                <section class="col-xs-6 no-padding">
                                                    <label for="fecha_qal1">Fecha documento</label>
                                                    <input type="date" class="form-control" id="fecha_qal1" placeholder="Fecha" value="<?php echo pon_dato_row('fecha_qal1', $row_qal1); ?>">
                                                </section>

                                                <section class="col-xs-6">
                                                    <label for="fecha_qal1_mod">Fecha subida</label>
                                                    <input type="date" class="form-control" id="fecha_qal1_mod" placeholder="Fecha" value="<?php echo pon_dato_row('fecha_qal1_mod', $row_qal1); ?>">
                                                </section>

                                                <section class="col-xs-6 no-padding" style="min-height: 60px;">
                                                    &nbsp;
                                                </section>

                                                <section class="col-xs-6">
                                                    <label for="ssam_cero">Ssam de Cero</label>
                                                    <input type="text" class="form-control" id="ssam_cero" placeholder="Ssam de Cero" value="<?php echo pon_dato_row('ssam_cero', $row_qal1); ?>">
                                                </section>

                                                <section class="col-xs-6 no-padding">
                                                    <label for="ssam_rango">Ssam de rango</label>
                                                    <input type="text" class="form-control" id="ssam_rango" placeholder="Ssam de Rango" value="<?php echo pon_dato_row('ssam_rango', $row_qal1); ?>">
                                                </section>

                                                <section class="col-xs-12 margin-top-10" style="padding-right: 0 !important;">
                                                    <section class="col-xs-6 no-padding" style="padding-right: 13px !important;">
                                                        <span class="btn btn-success fileinput-button">
                                                            <i class="glyphicon glyphicon-plus"></i>
                                                            <span>Seleccione fichero ...</span>
                                                            <input id="fileupload" type="file" name="files[]" multiple accept="application/pdf">
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
                                                            $docs = pon_dato_row('documento_qal1', $row_qal1);
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

                                                <input type="hidden" id="id_qal1" value="<?php echo pon_dato_row('id_qal1', $row_qal1); ?>"/>
                                                <input type="hidden" id="fk_analizadores" value="<?php echo $id_analizadores; ?>"/>
                                                <input type="hidden" id="documento_qal1" value="<?php echo pon_dato_row('documento_qal1', $row_qal1); ?>"/>
                                                <input type="hidden" id="componente_qal1" value="<?php echo $componente; ?>"/>
                                            </fieldset>

                                            <div class="form-actions">
                                                <a href="qal1_lista.php?id=<?php echo $id_analizadores; ?>&componente=<?php echo $componente; ?><?php echo(($vuelta_query != '') ? '&' . $vuelta_query : ''); ?>" class="btn btn-default pull-left" id="volver_lista"><i class="fa fa-arrow-left"></i> Volver lista QAL 1</a>
                                                <div class="btn btn-primary" id="guardar_qal1"><i class="fa fa-save"></i> Guardar QAL 1</div>
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
                elem_documento = $('#documento_qal1'),
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
                field_id_padre: 'fk_analizadores',
                url: url,
                dataType: 'json',
                done: function (e, data) {
                    var id_analizador = $('#fk_analizadores').val();
                    if (id_analizador == '') {
                        id_analizador = 'temp';
                    }
                    // Cogemos los documentos que ahora hay y los guardamos
                    $.each(data.result.files, function (index, file) {
                        doc = 'files_upload/analizador_' + id_analizador + '/' + file.name;
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


            $('#guardar_qal1').click(function () {

                var data = {},
                    id = null,
                    rel_componente,
                    contador = 0;

                $('#form_qal1 input, #form_qal1 select, #form_qal1 textarea').each(function (ind, elemento) {

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
                        url: 'consultas/PutQal1.php',
                        data: data,
                        success: function (r) {
                            if (r['resp'] == "OK") {
                                pon_mensaje("Guardado correctamente", "QAL 1 guardado", "#10b718", "fa-save");
                                <?php if (isset($id_analizadores)) echo 'window.location = "qal1_lista.php?id=' . $id_analizadores . '&componente=' . $componente . (($vuelta_query != '') ? '&' . $vuelta_query : '')  . '";'; ?>
                            } else {
                                pon_mensaje("Error al guardar", r['data'], "#990000", "fa-warning");
                            }

                        }
                    });
                }

            });

        })

    </script>


<?php require_once('ls_footer2.php'); ?>