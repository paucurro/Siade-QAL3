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
$row_verificaciones_cero = null;
$id_analizadores = null;
$nuevo = true;

if (isset($_REQUEST['id'])) {
    $id_analizadores = $_REQUEST['id'];
}
if (isset($_REQUEST['componente'])) {
    $componente = $_REQUEST['componente'];
}

if (isset($_REQUEST['id_verificaciones_cero'])) {
    $nuevo = false;
    $id_verificaciones_cero = $_REQUEST['id_verificaciones_cero'];

    $row_verificaciones_cero = setSqlDB("SELECT verificaciones_cero.*, 
       verificaciones_cero.fk_analizadores,
       verificaciones_cero.componente,
       qal1.id_qal1,
       qal1.fecha_qal1, 
       qal1.certificado,
       qal1.ssam_cero,
       qal2.id_qal2,
       qal2.fecha_qal2,
       qal2.rango_calibrado,
       qal2.intervalo_confianza,
       qal2.funcion_calibrado
    FROM verificaciones_cero 
       LEFT JOIN qal1 ON qal1.id_qal1 = verificaciones_cero.fk_qal1
       LEFT JOIN qal2 ON qal2.id_qal2 = verificaciones_cero.fk_qal2 
    WHERE id_verificaciones_cero = " . $id_verificaciones_cero . " LIMIT 1 ");
    $id_analizadores = $row_verificaciones_cero['fk_analizadores'];
    $componente = $row_verificaciones_cero['componente'];
} else {

    $row_qal1 = setSqlDB("SELECT * from qal1 WHERE fk_analizadores = '" . $id_analizadores . "' AND componente_qal1 = '" . $componente . "' ORDER BY fecha_qal1 DESC LIMIT 1");
    $row_qal2 = setSqlDB("SELECT * from qal2 WHERE fk_analizadores = '" . $id_analizadores . "' AND componente_qal2 = '" . $componente . "' ORDER BY fecha_qal2 DESC LIMIT 1");
    if (isset($row_qal1['fecha_qal1'])) {
        $row_verificaciones_cero = $row_qal1;
        if (isset($row_qal2['fecha_qal2'])) {
            $row_verificaciones_cero = array_merge($row_verificaciones_cero, $row_qal2);
        }
    } else if (isset($row_qal2['fecha_qal2'])) {
        $row_verificaciones_cero = $row_qal2;
    }
    $row_verificaciones_cero['fecha'] = date("Y-m-d");
}
//$componente = $_REQUEST['componente'];


$campo_valor = (in_array($componente, ['H2O', 'O2', 'CO2'])) ? 'porcentaje' : 'mg_nm3';
$unidad = (in_array($componente, ['H2O', 'O2', 'CO2'])) ? '%' : 'mg_nm3';

if ($id_analizadores != null) {
    $row_analizadores = setSqlDB("SELECT * FROM analizadores WHERE id_analizadores = " . $id_analizadores . " LIMIT 1");
    $nombre_analizador = $row_analizadores['tag'];
} else {
    $mensaje_error = 'No existe el analizador indicado ' . $id_analizadores . ' con el componente ' . $componente . ' no se puede continuar';
    $nombre_analizador = $row_verificaciones_cero['tag'];
}

$array_botellas = getAllDataDB("SELECT * FROM botellas " .
    "LEFT JOIN botellas_componentes ON botellas_componentes.fk_botellas = botellas.id_botellas " .
    "WHERE botellas_componentes.componente = 'N'" .
    "ORDER BY estado ASC; ");

$select_botellas = '<option value="">Seleccione una botella</option>';
if (count($array_botellas) > 0) {
    foreach ($array_botellas as $botella) {

        $valor = isset($botella[$campo_valor]) ? $botella[$campo_valor] : '';

        $select_botellas .= '<option value="'
            . $botella['id_botellas'] . '" '
            . ((isset($row_verificaciones_cero['fk_botellas'])) && ($row_verificaciones_cero['fk_botellas'] == $botella['id_botellas']) ? 'selected' : '')
            . ' data-valor="' . $valor . '" data-unidad="' . $unidad . '">'
            . $botella['n_botella'] . ' | '
            . $botella['n_certificado'] . ' | '
            . $botella['descripcion']
            . ' [' . strtolower($botella['estado']) . ']</option>';
    }
}

$array_usuarios = getAllDataDB("SELECT * FROM explotacion.usuarios; ");

$select_usuarios = '<option value="">Seleccione un usuario</option>';
if (count($array_usuarios) > 0) {
    // Si no hay usuario de la ficha, cogemos el actual logeado
    $usuario_selector = (isset($row_verificaciones_cero['fk_usuarios'])) ? $row_verificaciones_cero['fk_usuarios'] : $_SESSION['datos_usuario_qal3']->row_usuarios['id'];
    foreach ($array_usuarios as $usuario) {
        $select_usuarios .= '<option value="' . $usuario['id'] . '" ' . (($usuario_selector == $usuario['id']) ? 'selected' : '') . '>' . ($usuario['Nombre'] . ' ' . $usuario['Apellido1']) . '</option>';
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
                <li>Verificaciones Cero</li>
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
                        <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false"
                             data-widget-custombutton="false" data-widget-colorbutton="false"
                             data-widget-togglebutton="false" data-widget-deletebutton="false"
                             data-widget-fullscreenbutton="false" data-widget-sortable="false">

                            <header>
                                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                                <h2>Formulario <?php echo ($nuevo == true) ? "INSERTAR NUEVO" : "EDITAR" ?>
                                    Verificaciones Cero Analizador <?php echo $nombre_analizador; ?> de
                                    componente <?php echo $componente; ?></h2>
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

                                        <div class="row">
                                            <div class="clearfix padding-10 bordered" style="margin: 0 20px 20px 20px;">
                                                <div class="col-xs-2"><h3>QAL 1</h3></div>
                                                <?php if (isset($row_verificaciones_cero['fecha_qal1'])) { ?>
                                                    <div class="col-xs-5">
                                                        <fieldset>
                                                            <section>
                                                                <label>Fecha</label>
                                                                <input type="date" class="form-control" value="<?php echo pon_dato_row('fecha_qal1', $row_verificaciones_cero); ?>" readonly/>
                                                            </section>
                                                            <section>
                                                                <label>Certificado</label>
                                                                <input type="text" class="form-control" value="<?php echo pon_dato_row('certificado', $row_verificaciones_cero); ?>" readonly/>
                                                            </section>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-xs-5">
                                                        <fieldset>
                                                            <section>
                                                                <label>Ssam Cero</label>
                                                                <input type="text" id="ssam_cero" class="form-control" value="<?php echo pon_dato_row('ssam_cero', $row_verificaciones_cero); ?>" readonly/>
                                                            </section>
                                                        </fieldset>
                                                    </div>
                                                <?php } else {
                                                    echo "Falta la ficha QAL1, <a href='edit_qal1.php?id=" . $id_analizadores . "&componente=" . $componente . "&nuevo=1'>puede crearla aquí</a>";
                                                } ?>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="clearfix padding-10 bordered" style="margin: 0 20px 20px 20px;">
                                                <div class="col-xs-2"><h3>QAL 2</h3></div>
                                                <?php if (isset($row_verificaciones_cero['fecha_qal2'])) { ?>
                                                    <div class="col-xs-5">
                                                        <fieldset>
                                                            <section class="margin-bottom-5">
                                                                <label>Fecha</label>
                                                                <input type="date" class="form-control" value="<?php echo pon_dato_row('fecha_qal2', $row_verificaciones_cero); ?>" readonly/>
                                                            </section>
                                                            <section>
                                                                <label>Intervalo Confianza</label>
                                                                <input type="text" class="form-control" value="<?php echo pon_dato_row('intervalo_confianza', $row_verificaciones_cero); ?>" readonly/>
                                                            </section>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-xs-5">
                                                        <fieldset>
                                                            <section class="margin-bottom-5">
                                                                <label>Funcion Calibrado</label>
                                                                <input type="text" class="form-control" value="<?php echo pon_dato_row('funcion_calibrado', $row_verificaciones_cero); ?>" readonly/>
                                                            </section>
                                                            <section>
                                                                <label>Rango Calibrado</label>
                                                                <input type="text" class="form-control" value="<?php echo pon_dato_row('rango_calibrado', $row_verificaciones_cero); ?>" readonly/>
                                                            </section>
                                                        </fieldset>
                                                    </div>
                                                <?php } else {
                                                    echo "Falta la ficha QAL2, <a href='edit_qal2.php?id=" . $id_analizadores . "&componente=" . $componente . "&nuevo=1'>puede crearla aquí</a>";
                                                } ?>
                                            </div>
                                        </div>

                                        <!--action="PutAnalizadorverificaciones_cero.php" -->
                                        <form id="form_botellas" enctype="multipart/form-data">

                                            <fieldset>
                                                <section class="col-xs-6 state-error">
                                                    <label for="fecha">Fecha</label>
                                                    <input type="date" class="form-control" id="fecha" placeholder="Fecha" value="<?php echo pon_dato_row('fecha', $row_verificaciones_cero); ?>">
                                                </section>
                                                <section class="col-xs-6">
                                                    <label for="fk_botellas">Botella</label>
                                                    <select class="form-control" id="fk_botellas" required>
                                                        <?php echo $select_botellas; ?>
                                                    </select>
                                                </section>

                                                <section class="col-xs-12">
                                                    <label for="fk_usuarios">Tecnico mantenimiento</label>
                                                    <select class="form-control" id="fk_usuarios" name="fk_usuarios" required>
                                                        <?php echo $select_usuarios; ?>
                                                    </select>
                                                </section>

                                                <section class="col-xs-12 no-padding">
                                                    <div class="">
                                                        <div class="col-xs-6">
                                                            <label for="valor_patron_referencia">Valor patron
                                                                referencia</label>
                                                            <input type="number" step=".01" pattern="^\d*(\.\d{0,2})?$"
                                                                   class="form-control" id="valor_patron_referencia"
                                                                   placeholder="Valor patron referencia"
                                                                   value="<?php echo pon_dato_row('valor_patron_referencia', $row_verificaciones_cero); ?>"
                                                                   required>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <label for="valor_lectura_real">Valor lectura real</label>
                                                            <input type="number" step=".01" pattern="^\d*(\.\d{0,2})?$"
                                                                   class="form-control" id="valor_lectura_real"
                                                                   placeholder="Valor lectura real"
                                                                   value="<?php echo pon_dato_row('valor_lectura_real', $row_verificaciones_cero); ?>"
                                                                   required>
                                                        </div>
                                                        <div class="col-xs-3">
                                                            <label for="valor_calibrado">Valor calibrado</label>
                                                            <input type="number" step=".01" pattern="^\d*(\.\d{0,2})?$"
                                                                   class="form-control" id="valor_calibrado"
                                                                   placeholder="Valor calibrado"
                                                                   value="<?php echo pon_dato_row('valor_calibrado', $row_verificaciones_cero); ?>"
                                                                   rel="popover"
                                                                   data-placement="top"
                                                                   data-original-title="<strong>AJUSTAR CALIBRADO</strong>"
                                                                   data-content="Debe realizar el ajuste de calibrado.">
                                                        </div>
                                                    </div>
                                                </section>

                                                <section class="col-xs-6">
                                                    <label for="unidad_medida">Unidad medida</label>
                                                    <input type="text" class="form-control" id="unidad_medida"
                                                           placeholder="Unidad medida"
                                                           value="<?php echo pon_dato_row('unidad_medida', $row_verificaciones_cero); ?>"
                                                           required>
                                                </section>
                                                <section class="col-xs-6">
                                                    <label for="ajuste_analizador">Ajuste analizador</label>
                                                    <select class="form-control" id="ajuste_analizador">
                                                        <option value="NO" <?php echo((pon_dato_row('ajuste_analizador', $row_verificaciones_cero) == 'NO') ? 'selected="selected"' : ''); ?>>
                                                            No
                                                        </option>
                                                        <option value="SI" <?php echo((pon_dato_row('ajuste_analizador', $row_verificaciones_cero) == 'SI') ? 'selected="selected"' : ''); ?>>
                                                            Si
                                                        </option>
                                                    </select>
                                                </section>
                                            </fieldset>

                                            <fieldset class="padding-top-15">
                                                <div class="clearfix col-xs-6"
                                                     style="background-color: #ffbdbd !important; padding: 10px;">
                                                    <section class="col-xs-6">
                                                        <label for="limite_alarma_superior">Límite Alarma
                                                            Superior</label>
                                                        <input readonly id="limite_alarma_superior" class="form-control"
                                                               value="0"/>
                                                    </section>
                                                    <section class="col-xs-6">
                                                        <label for="limite_actuacion_superior">Límite Actuación
                                                            Superior</label>
                                                        <input readonly id="limite_actuacion_superior"
                                                               class="form-control" value="0"/>
                                                    </section>
                                                    <section class="col-xs-6 margin-top-5">
                                                        <label for="limite_alarma_inferior">Límite Alarma
                                                            Inferior</label>
                                                        <input readonly id="limite_alarma_inferior" class="form-control"
                                                               value="0"/>
                                                    </section>
                                                    <section class="col-xs-6 margin-top-5">
                                                        <label for="limite_actuacion_inferior">Límite Actuación
                                                            Inferior</label>
                                                        <input readonly id="limite_actuacion_inferior"
                                                               class="form-control" value="0"/>
                                                    </section>
                                                </div>
                                                <div class="col-xs-6 padding-10 padding-top-0">
                                                    <div style="border: 1px solid #CCCCCC; padding: 4px; padding-top: 20px; background-color: #EEEEEE; font-size: 16px; min-height: 136px;">
                                                        <ul>
                                                            <li>Se deberá actuar sobre el SAM, cuando se cumpla alguna
                                                                de las siguientes condiciones:
                                                            </li>
                                                            <ul>
                                                                <li id="op_mensaje_1" class="op_mensajes">Un valor
                                                                    supere un límite de actuación.
                                                                </li>
                                                                <li id="op_mensaje_2" class="op_mensajes">Tres puntos
                                                                    consecutivos superen el nivel de alarma.
                                                                </li>
                                                                <li id="op_mensaje_3" class="op_mensajes">Seis puntos
                                                                    consecutivos estén aumentando o disminuyendo
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <input type="hidden" id="id_verificaciones_cero" name="id_verificaciones_cero" value="<?php echo pon_dato_row('id_verificaciones_cero', $row_verificaciones_cero); ?>"/>
                                            <input type="hidden" id="fk_analizadores" name="fk_analizadores" value="<?php echo $id_analizadores; ?>"/>
                                            <input type="hidden" id="componente" name="componente" value="<?php echo $componente; ?>"/>
                                            <input type="hidden" id="fk_qal1" value="<?php echo pon_dato_row('id_qal1', $row_verificaciones_cero); ?>" data-message="El QAL1 no está definido, no se puede guardar." required/>
                                            <input type="hidden" id="fk_qal2" value="<?php echo pon_dato_row('id_qal2', $row_verificaciones_cero); ?>" data-message="El QAL2 no está definido, no se puede guardar." required/>

                                            <div class="form-actions clearfix">
                                                <a href="verificaciones_cero.php?id=<?php echo $id_analizadores; ?>&componente=<?php echo $componente; ?>"
                                                   class="btn btn-default pull-left" id="volver_lista"><i class="fa fa-arrow-left"></i> Volver lista Verificaciones Cero</a>
                                                <div class="pull-right">
                                                    <div class="btn btn-primary" id="guardar_verificaciones_cero">
                                                        <i class="fa fa-save"></i> Guardar Verificaciones Cero
                                                    </div>
                                                    <div class="margin-right-20 pull-left" id="show_message"></div>
                                                </div>
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

    <script type="text/javascript" lang="javascript">

        $(document).ready(function () {

            var elem_valor_calibrado = $('#valor_calibrado');
            elem_valor_calibrado.popover({
                trigger: "manual",
                html: true
            });
            var tres_valores_consecutivos = false;

            pageSetUp();

            var elem_valor_patron_referencia = $('#valor_patron_referencia');
            var elem_fecha = $('#fecha');

            setInterval(function () {
                if ($('.op_mensajes.bold').length == 0) elem_valor_calibrado.popover('hide');
                pide_datos_anteriores();
            }, 3000);

            /*elem_fecha.on('change', function () {
                pide_datos_anteriores();
            }).focus();

            $('#valor_lectura_real').change(function () {
                pide_datos_anteriores();
            });*/

            function pide_datos_anteriores() {
                var id_analizadores = '<?php echo $id_analizadores; ?>';
                var componente = '<?php echo $componente; ?>';
                var fecha = elem_fecha.val();

                var limite_actuacion_superior = $('#limite_actuacion_superior').val() * 1;
                var limite_actuacion_inferior = $('#limite_actuacion_inferior').val() * 1;

                var valor_lectura_real = $('#valor_lectura_real').val() * 1;

                if ((valor_lectura_real >= limite_actuacion_superior) || (valor_lectura_real <= limite_actuacion_inferior)) {
                    elem_valor_calibrado.popover('show');
                    $('#op_mensaje_1').addClass('bold');
                } else {
                    $('#op_mensaje_1').removeClass('bold');
                }

                if (fecha) {
                    var data = {
                        select: '*',
                        tabla: 'verificaciones_cero',
                        where: [
                            {condicion: 'AND', campo: 'fecha', comparacion: '<', valor: fecha},
                            {condicion: 'AND', campo: 'fk_analizadores', comparacion: '=', valor: id_analizadores},
                            {condicion: 'AND', campo: 'componente', comparacion: '=', valor: componente},
                            {
                                condicion: 'AND',
                                campo: 'id_verificaciones_cero',
                                comparacion: '!=',
                                valor: $('#id_verificaciones_cero').val()
                            },
                        ],
                        limit: 5,
                        order: [
                            {campo: 'fecha', orden: 'DESC'}
                        ]
                    };

                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: 'consultas/SqlSentence.php',
                        data: data,
                        success: function (r) {
                            if (r['resp'] == "OK") {

                                var cantidad_desc = 0, cantidad_asc = 0, cantidad_alarma = 0;
                                var limite_alarma_superior = $('#limite_alarma_superior').val() * 1;
                                var limite_alarma_inferior = $('#limite_alarma_inferior').val() * 1;

                                if (r['data']) {
                                    var val_ant = $('#valor_lectura_real').val() * 1;
                                    if (val_ant) {
                                        // Calculamos el valor actual
                                        if (((val_ant >= limite_alarma_superior) || (val_ant <= limite_alarma_inferior))) {
                                            cantidad_alarma++;
                                        }

                                        $.each(r['data'], function (ind, row) {
                                            // Calculo de valores descendentes
                                            if (val_ant < row.valor_lectura_real) cantidad_desc++;
                                            if (val_ant > row.valor_lectura_real) cantidad_asc++;
                                            val_ant = row.valor_lectura_real * 1;
                                            // Calculo de tres últimos de superar ALARMA
                                            if (ind < 2) {
                                                if (((val_ant >= row.limite_alarma_superior) || (val_ant <= row.limite_alarma_inferior))) {
                                                    cantidad_alarma++;
                                                } else {
                                                    cantidad_alarma = 0;
                                                }
                                            }
                                        })
                                    }
                                }
                                if ((cantidad_desc >= 5) || (cantidad_asc >= 5)) {
                                    elem_valor_calibrado.popover('show');
                                    $('#op_mensaje_3').addClass('bold');
                                } else {
                                    $('#op_mensaje_3').removeClass('bold');
                                }
                                if (cantidad_alarma >= 3) {
                                    elem_valor_calibrado.popover('show');
                                    $('#op_mensaje_2').addClass('bold');
                                } else {
                                    $('#op_mensaje_2').removeClass('bold');
                                }
                            } else {
                                pon_mensaje("Error al guardar", r['data'], "#990000", "fa-warning");
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            pon_mensaje("Error al guardar", jqXHR.responseText, "#990000", "fa-warning");
                        }
                    });
                }

            }

            //pide_datos_anteriores();

            $('#fk_botellas').change(function () {
                var valor = $(this).find('option:selected').attr('data-valor');
                var unidad = $(this).find('option:selected').attr('data-unidad');
                elem_valor_patron_referencia.val((valor * 1));
                $('#unidad_medida').val(unidad);
                calcula_limites();
            });

            $('#guardar_verificaciones_cero').click(function () {

                var data = {},
                    id = null,
                    rel_componente,
                    required, type_hidden,
                    salida = false,
                    contador = 0;

                $('#form_botellas input, #form_botellas select, #form_botellas textarea').each(function (ind, elemento) {

                    id = $(elemento).attr('id');
                    if (id != undefined) {
                        if (id.indexOf('componente_') > -1) {
                            if ($(elemento).is(':checked')) {
                                rel_componente = $(elemento).attr('rel');
                                if (data['componentes'] == undefined) {
                                    data['componentes'] = [];
                                }
                                data['componentes'].push(rel_componente);
                            }
                        } else {
                            required = $(elemento).prop('required');
                            type_hidden = ($(elemento).prop('type') == 'hidden');
                            if ($(elemento).is("input")) {
                                data[id] = $(elemento).val();
                            } else if ($(elemento).is("select")) {
                                data[id] = $(elemento).val();
                            } else if ($(elemento).is("textarea")) {
                                data[id] = $(elemento).val();
                            }
                            if ((required == true) && (data[id] == '')) {
                                salida = true;
                                if (type_hidden) {
                                    pon_mensaje("Error al guardar", $(elemento).attr('data-message'), "#990000", "fa-warning");
                                } else {
                                    $(elemento).parent('section,div').addClass('has-error');
                                }
                            } else {
                                $(elemento).parent('section,div').removeClass('has-error');
                            }
                        }
                        contador++;
                    }
                });

                if ((contador > 0) && (salida != true)) {
                    data['hay_alarma_valor'] = $('.op_mensajes.bold').length;
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: 'consultas/PutEasCero.php',
                        data: data,
                        success: function (r) {
                            if (r['resp'] == "OK") {
                                pon_mensaje("Guardado correctamente", "Verificaciones Cero guardado", "#10b718", "fa-save");
                                <?php if (isset($id_analizadores)) echo 'window.location = "verificaciones_cero.php?id=' . $id_analizadores . '&componente=' . $componente . '";'; ?>
                            } else {
                                pon_mensaje("Error al guardar", r['data'], "#990000", "fa-warning");
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            pon_mensaje("Error al guardar", jqXHR.responseText, "#990000", "fa-warning");
                        }
                    });
                } else if ((salida == true)) {
                    $('#show_message').html('<i class="fa fa-warning text-danger"></i> Hay errores en el formulario.');
                }

            });

            function calcula_limites() {
                var valor_patron = elem_valor_patron_referencia.val();
                var ssam_cero = $('#ssam_cero').val();

                valor_patron = valor_patron.replace(",", ".") * 1;
                ssam_cero = ssam_cero.replace(",", ".") * 1;

                $('#limite_alarma_superior').val(redondea_2dec(valor_patron + 2 * ssam_cero));
                $('#limite_actuacion_superior').val(redondea_2dec(valor_patron + 3 * ssam_cero));
                $('#limite_alarma_inferior').val(redondea_2dec(valor_patron - 2 * ssam_cero));
                $('#limite_actuacion_inferior').val(redondea_2dec(valor_patron - 3 * ssam_cero));

            }

            <?php
            if (isset($_REQUEST['id_verificaciones_cero'])) {
                echo "calcula_limites();";
            }
            ?>

            elem_valor_patron_referencia.change(function () {
                calcula_limites();
            });

        })

    </script>


<?php require_once('ls_footer2.php'); ?>