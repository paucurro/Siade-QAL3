<?php require_once('_config.php'); ?>
<?php require_once('ls_check_session.php'); ?>
<?php require_once('libreria.php'); ?>
<?php require_once('ls_cabecera.php'); ?>

<?php

?>

    <!-- MAIN PANEL -->
    <div id="main" role="main" style="overflow: hidden;">

        <!-- RIBBON -->
        <div id="ribbon">

            <!-- breadcrumb -->
            <ol class="breadcrumb">
                <li><?php echo 'Principal'; ?></li>
            </ol>
            <!-- end breadcrumb -->

            <span class="pull-right padding-top-10">
                <div id="time_counter" class="pull-right" style="width: 24px; color: #FFF;"></div>
                <div class="pull-right" style="width: 30px; color: #FFF;">
                    <i id="haz_peticiones" style="display: none;" class="fa fa-refresh fa-spin"></i>
                </div>
            </span>


            <!-- You can also add more buttons to the
            ribbon for further usability

            Example below:

            <span class="ribbon-button-alignment pull-right">
            <span id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa-grid"></i> Change Grid</span>
            <span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
            <span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>
            </span> -->

        </div>
        <!-- END RIBBON -->


        <?php ?>
        <!-- MAIN CONTENT -->
        <div id="content" class="container-fluid" >

            <!-- widget grid -->
            <section id="widget-grid">
                <div class="">
                    <!-- your contents here -->
                    <div class="" style="overflow: auto; width: 100%; padding-top: 40px;" id="contenedor_pantalla"></div>

                </div>

                <!-- end row -->

            </section>
            <!-- end widget grid -->

        </div>
        <!-- END MAIN CONTENT -->
        <!-- Modal -->
        <div class="modal fade" id="ModalParametros" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title">Titulo Modal</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="" id="objeto_editar"/>
                        <input type="hidden" value="" id="modo_lista_puntos"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="aplicar_cambios">Aplicar cambios</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

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



        });

    </script>


<?php require_once('ls_footer2.php'); ?>