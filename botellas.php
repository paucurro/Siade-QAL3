<?php require_once('_config.php'); ?>
<?php require_once('ls_check_session.php'); ?>
<?php require_once('Connections/qal3_db.php'); ?>
<?php require_once('libreria.php'); ?>
<?php require_once('ls_cabecera.php'); ?>

<?php

mysqli_select_db($qal3_db, $database_qal3_db);

$array_botellas = getAllDataDB(
    "SELECT " .
    "        botellas.*," .
    "        (SELECT IFNULL(GROUP_CONCAT(CONCAT(' ', botellas_componentes.componente, '<span style=\'font-size: 0.8em\'> (',botellas_componentes.porcentaje,'%)</span>')),NULL)" .
    "        FROM botellas_componentes" .
    "        WHERE botellas_componentes.fk_botellas = botellas.id_botellas AND componente IN ('H2O','O2','CO2')) AS porcentaje," .
    "        (SELECT IFNULL(GROUP_CONCAT(CONCAT(' ', botellas_componentes.componente, '<span style=\'font-size: 0.8em\'> (',botellas_componentes.mg_nm3,'mg)</span>')),NULL)" .
    "        FROM botellas_componentes" .
    "        WHERE botellas_componentes.fk_botellas = botellas.id_botellas AND componente NOT IN ('H2O','O2','CO2')) AS mg," .
    "        DATE_FORMAT(fecha_caducidad, '%d/%m/%Y') AS fecha_caducidad," .
    "        DATE_FORMAT(fecha_ultima_revision,'%d/%m/%Y') AS fecha_ultima_revision" .
    " FROM botellas " .
    " LEFT JOIN botellas_componentes ON botellas.id_botellas = botellas_componentes.fk_botellas " .
    " LEFT JOIN tipos_botellas_componentes ON tipos_botellas_componentes.fk_tipos_botellas = botellas.fk_tipos_botellas " .
    " GROUP BY id_botellas");

$tbody_botellas = '<thead>';
$tbody_botellas .= '  <tr>';
$tbody_botellas .= '    <th></th>';
$tbody_botellas .= '    <th>Descripción</th>';
$tbody_botellas .= '    <th>Nº Botella</th>';
$tbody_botellas .= '    <th>Nº Certificado</th>';
$tbody_botellas .= '    <th>Nº P.R.</th>';
$tbody_botellas .= '    <th>Foto</th>';
$tbody_botellas .= '    <th>F.Caducidad</th>';
$tbody_botellas .= '    <th>F.Última<br>Revisión</th>';
$tbody_botellas .= '    <th>Presión última<br>Revisión</th>';
$tbody_botellas .= '    <th>Estado</th>';
$tbody_botellas .= '    <th>Componentes</th>';
$tbody_botellas .= '  </tr>';
$tbody_botellas .= '</thead>';
$tbody_botellas .= '<tbody>';

if (count($array_botellas) > 0) {
    foreach ($array_botellas AS $row_botella) {
        $tbody_botellas .= '<tr>';
        $tbody_botellas .= '  <td class="td_nowrap">';
        $tbody_botellas .= '    <div class="btn-group td_nowrap">';
        $tbody_botellas .= '        <button class="btn btn-primary btn-xs margin-right-3 editar_botella" rel-id="' . $row_botella['id_botellas'] . '"><i class="fa fa-edit"></i></button>';
        $tbody_botellas .= '        <button class="btn btn-danger btn-xs margin-right-3 borrar_botella" rel-id="' . $row_botella['id_botellas'] . '"><i class="fa fa-times"></i></button>';
        $tbody_botellas .= '    </div>';
        $tbody_botellas .= '  </td>';
        $tbody_botellas .= '  <td>' . $row_botella['descripcion'] . '</td>';
        $tbody_botellas .= '  <td style="white-space: nowrap;">' . $row_botella['n_botella'] . '</td>';
        $tbody_botellas .= '  <td style="white-space: nowrap;">' . $row_botella['n_certificado'] . '</td>';
        $tbody_botellas .= '  <td style="white-space: nowrap;">' . $row_botella['n_pr'] . '</td>';
        $tbody_botellas .= '  <td style="white-space: nowrap;">' . (($row_botella['foto_nombre'] != null) ? '<a href="./' . $row_botella['foto_nombre'] . '" target="_blank" class="">SI <i class="fa fa-eye"></i></a>' : 'NO') . '</td>';
        $tbody_botellas .= '  <td style="white-space: nowrap;">' . $row_botella['fecha_caducidad'] . '</td>';
        $tbody_botellas .= '  <td style="white-space: nowrap;">' . $row_botella['fecha_ultima_revision'] . '</td>';
        $tbody_botellas .= '  <td style="white-space: nowrap;">' . $row_botella['presion_ultima_revision'] . '</td>';
        $tbody_botellas .= '  <td style="white-space: nowrap;">' . $row_botella['estado'] . '</td>';
        $tbody_botellas .= '  <td>' . (($row_botella['porcentaje']) ? $row_botella['porcentaje'] . ',' : '') . ' ' . $row_botella['mg'] . '</td>';
        $tbody_botellas .= '</tr>';
    }
} else {
    /*$tbody_botellas .= '<tr>';
    $tbody_botellas .= '    <td></td>';
    $tbody_botellas .= '    <td></td>';
    $tbody_botellas .= '    <td></td>';
    $tbody_botellas .= '    <td></td>';
    $tbody_botellas .= '    <td></td>';
    $tbody_botellas .= '    <td></td>';
    $tbody_botellas .= '    <td></td>';
    $tbody_botellas .= '    <td></td>';
    $tbody_botellas .= '    <td></td>';
    $tbody_botellas .= '    <td></td>';
    $tbody_botellas .= '    <td></td>';
    $tbody_botellas .= '</tr>';           */
}
$tbody_botellas .= '</tbody>';

$tabla_botellas = '<table id="tabla_botellas" class="table table-striped table-bordered table-hover" width="100%">';
$tabla_botellas .= $tbody_botellas;
$tabla_botellas .= '</table>';

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
                            <!-- widget options:
                                usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

                                data-widget-colorbutton="false"
                                data-widget-editbutton="false"
                                data-widget-togglebutton="false"
                                data-widget-deletebutton="false"
                                data-widget-fullscreenbutton="false"
                                data-widget-custombutton="false"
                                data-widget-collapsed="true"
                                data-widget-sortable="false"

                            -->

                            <header>
                                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                                <h2>Lista de botellas</h2>
                            </header>

                            <!-- widget div-->
                            <div>

                                <!-- widget content -->
                                <div class="widget-body" id="zona_tabla_botellas">

                                    <?php echo $tabla_botellas; ?>

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

<?php

$arr_objetos = null;

?>

    <!-- PAGE RELATED PLUGIN(S)
    <script src="..."></script>-->

    <script type="text/javascript">

        $(document).ready(function () {

            pageSetUp();

            var responsiveHelper_datatable_col_reorder = undefined;

            var breakpointDefinition = {
                tablet: 1024,
                phone: 480
            };


            $('.ver_imagen').click(function () {
                $('#foto_view').attr('src', $(this).attr('image'));
                $('#myModal').modal('show');
            });

            $('#tabla_botellas').DataTable({
                //scrollY:        600,
                //scroller:       true,
                pageLength:     16,
                stateSave: true,
                sDom: "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>" +
                    "t" +
                    "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
                autoWidth: true,
                oLanguage: {
                    "sUrl": "js/plugin/datatables/Spanish.json"
                },
                preDrawCallback: function () {
                    // Initialize the responsive datatables helper once.
                    if (!responsiveHelper_datatable_col_reorder) {
                        responsiveHelper_datatable_col_reorder = new ResponsiveDatatablesHelper($('#tabla_botellas'), breakpointDefinition);
                    }
                    var elem_add_botellas = $('#add_botellas'),
                        elem_ColVis = $('.ColVis');

                    if (elem_add_botellas.length == 0) {
                        elem_ColVis.prepend('<a type="button" href="botellas_ins.php?nuevo=1" class="btn btn-circle btn-success pull-left margin-right-5" id="add_botellas" title="Añadir botellas"><i class="fa fa-plus"></i></a>');
                    }
                },
                rowCallback: function (nRow) {
                    responsiveHelper_datatable_col_reorder.createExpandIcon(nRow);
                },
                "drawCallback": function (oSettings) {
                    responsiveHelper_datatable_col_reorder.respond();
                }
            });


            // Apply the filter
            $("#tabla_botellas thead th input[type=text]").on('keyup change', function () {

                otable
                    .column($(this).parent().index() + ':visible')
                    .search(this.value)
                    .draw();

            });
            /* END COLUMN FILTER */

            $(document)
                .on('click', '.editar_botella', function () {
                    var id_botella = $(this).attr('rel-id');
                    window.location = 'botellas_ins.php?id=' + id_botella;
                })
                .on('click', '.borrar_botella', function () {
                    if (confirm('Seguro que quiere eliminar esta botella ?')) {
                        var id_botella = $(this).attr('rel-id');
                        $.ajax({
                            type: "POST",
                            dataType: "JSON",
                            url: 'consultas/Delbotella.php',
                            data: 'id=' + id_botella,
                            success: function (r) {
                                pon_mensaje("Eliminada correctamente", "Botella eliminada", "#10b718", "fa-save");
                                window.location = 'botellas.php';
                            }
                        });
                    }

                });


        })

    </script>


<?php require_once('ls_footer2.php'); ?>