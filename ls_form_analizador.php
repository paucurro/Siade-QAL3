<fieldset id="form_botellas">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
            <div class="row">
                <section class="col-xs-6">
                    <label for="tag">Tag</label>
                    <input type="text" class="form-control" id="tag" placeholder="Tag" value="<?php echo pon_dato_row('tag', $row_analizadores); ?>" required>
                </section>
                <section class="col-xs-6">
                    <label for="linea">Línea</label>
                    <select class="form-control" id="linea" required>
                        <option value="" <?php echo pon_selected('', 'linea', $row_analizadores); ?>>Línea</option>
                        <option value="L1" <?php echo pon_selected('L1', 'linea', $row_analizadores); ?>>L1</option>
                        <option value="L2" <?php echo pon_selected('L2', 'linea', $row_analizadores); ?>>L2</option>
                        <option value="L3" <?php echo pon_selected('L3', 'linea', $row_analizadores); ?>>L3</option>
                        <option value="L4" <?php echo pon_selected('L4', 'linea', $row_analizadores); ?>>L4</option>
                    </select><i></i>
                </section>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <section class="col-xs-12">
                    <label for="tipo_analizador">Tipo de analizador</label>
                    <select class="form-control" id="tipo_analizador">
                        <option value="" <?php echo pon_selected("", 'tipo_analizador', $row_analizadores); ?>>Seleccione una opción</option>
                        <option value="PRINCIPAL" <?php echo pon_selected("PRINCIPAL", 'tipo_analizador', $row_analizadores); ?>>Principal</option>
                        <option value="REDUNDANTE" <?php echo pon_selected("REDUNDANTE", 'tipo_analizador', $row_analizadores); ?>>Redundante</option>
                        <option value="SALIDA CALDERA" <?php echo pon_selected("SALIDA CALDERA", 'tipo_analizador', $row_analizadores); ?>>Salida caldera</option>
                        <option value="SALIDA FILTRO MANGAS" <?php echo pon_selected("SALIDA FILTRO MANGAS", 'tipo_analizador', $row_analizadores); ?>>Salida filtro mangas</option>
                    </select><i></i>
                </section>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <section class="col-xs-6">
                    <label for="descripcion">Descripción</label>
                    <input type="text" class="form-control" id="descripcion" placeholder="Descripción" value="<?php echo pon_dato_row('descripcion', $row_analizadores); ?>" required>
                </section>

                <section class="col-xs-6">
                    <label for="estado">Estado</label>
                    <select class="form-control" id="estado">
                        <option value="OPERATIVO" <?php echo pon_selected('OPERATIVO', 'estado', $row_analizadores); ?>>OPERATIVO</option>
                        <option value="MANTENIMIENTO" <?php echo pon_selected('MANTENIMIENTO', 'estado', $row_analizadores); ?>>MANTENIMIENTO</option>
                        <option value="AVERIADO" <?php echo pon_selected('AVERIADO', 'estado', $row_analizadores); ?>>AVERIADO</option>
                        <option value="INVENTARIO" <?php echo pon_selected('INVENTARIO', 'estado', $row_analizadores); ?>>INVENTARIO</option>
                        <option value="RETIRADO" <?php echo pon_selected('RETIRADO', 'estado', $row_analizadores); ?>>RETIRADO</option>
                    </select> <i></i>
                </section>

            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <section class="col-xs-6">
                    <label for="marca">Marca</label>
                    <input type="text" class="form-control" id="marca" placeholder="Marca" value="<?php echo pon_dato_row('marca', $row_analizadores); ?>">
                </section>
                <section class="col-xs-6">
                    <label for="modelo">Modelo</label>
                    <input type="text" class="form-control" id="modelo" placeholder="Modelo" value="<?php echo pon_dato_row('modelo', $row_analizadores); ?>">
                </section>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <section class="col-xs-6">
                    <label for="extractivo">Extractivo</label>
                    <select class="form-control" id="extractivo">
                        <option value="" <?php echo pon_selected("", 'extractivo', $row_analizadores); ?>>Seleccione una opción</option>
                        <option value="No" <?php echo pon_selected("No", 'extractivo', $row_analizadores); ?>>No</option>
                        <option value="Si" <?php echo pon_selected("Si", 'extractivo', $row_analizadores); ?>>Si</option>
                    </select><i></i>
                </section>
                <section class="col-xs-6">
                    <label for="senyal_salida">Señal salida</label>
                    <input type="text" class="form-control" id="senyal_salida" placeholder="Senyal salida" value="<?php echo pon_dato_row('senyal_salida', $row_analizadores); ?>">
                </section>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <section class="col-xs-6">
                    <label for="numero_serie">Número de serie</label>
                    <input type="text" class="form-control" id="numero_serie" value="<?php echo pon_dato_row('numero_serie', $row_analizadores); ?>">
                </section>
                <section class="col-xs-6">
                    <label for="frecuencia_verificacion">Frecuencia de verificación</label>
                    <input type="text" class="form-control" id="frecuencia_verificacion" value="<?php echo pon_dato_row('frecuencia_verificacion', $row_analizadores); ?>">
                </section>
            </div>
        </div>

        <!-- <div class="form-group">
            <div class="row">
                <section class="col-xs-6">
                    <label for="intervalo_confianza">Intervalo de confianza</label>
                    <input type="text" class="form-control" id="intervalo_confianza" value="< ?php echo pon_dato_row('intervalo_confianza', $row_analizadores); ?>">
                </section>

            </div>
        </div> -->

    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="form-group">
            <div class="row">
                <section class="col-xs-6">
                    <label for="control_emisiones">Control emisiones</label>
                    <select class="form-control" id="control_emisiones">
                        <option value="" <?php echo pon_selected("", 'control_emisiones', $row_analizadores); ?>>Seleccione una opción</option>
                        <option value="No" <?php echo pon_selected("No", 'control_emisiones', $row_analizadores); ?>>No</option>
                        <option value="Si" <?php echo pon_selected("Si", 'control_emisiones', $row_analizadores); ?>>Si</option>
                    </select><i></i>
                </section>
                <section class="col-xs-6">
                    <label for="tag_senyal_de_control">Tag señal control</label>
                    <select class="form-control" id="tag_senyal_de_control">
                        <option value="" <?php echo pon_selected("", 'tag_senyal_de_control', $row_analizadores); ?>>Seleccione una opción</option>
                        <option value="No" <?php echo pon_selected("No", 'tag_senyal_de_control', $row_analizadores); ?>>No</option>
                        <option value="Si" <?php echo pon_selected("Si", 'tag_senyal_de_control', $row_analizadores); ?>>Si</option>
                    </select><i></i>
                </section>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <section class="col-xs-6">
                    <label for="correccion_humedad">Corrección humedad</label>
                    <select class="form-control" id="correccion_humedad">
                        <option value="" <?php echo pon_selected("", 'correccion_humedad', $row_analizadores); ?>>Seleccione una opción</option>
                        <option value="No" <?php echo pon_selected("No", 'correccion_humedad', $row_analizadores); ?>>No</option>
                        <option value="Si" <?php echo pon_selected("Si", 'correccion_humedad', $row_analizadores); ?>>Si</option>
                    </select><i></i>
                </section>
                <section class="col-xs-6">
                    <label for="correccion_oxigeno">Corrección oxígeno</label>
                    <select class="form-control" id="correccion_oxigeno">
                        <option value="" <?php echo pon_selected("", 'correccion_oxigeno', $row_analizadores); ?>>Seleccione una opción</option>
                        <option value="No" <?php echo pon_selected("No", 'correccion_oxigeno', $row_analizadores); ?>>No</option>
                        <option value="Si" <?php echo pon_selected("Si", 'correccion_oxigeno', $row_analizadores); ?>>Si</option>
                    </select><i></i>
                </section>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <section class="col-xs-6">
                    <label for="fecha_instalacion">Fecha instalación</label>
                    <input type="date" class="form-control" id="fecha_instalacion" placeholder="Fecha" value="<?php echo pon_fecha_datetime(pon_dato_row('fecha_instalacion', $row_analizadores)); ?>" required>
                </section>
                <section class="col-xs-6">
                    <label>Fecha ult. revisión</label>
                    <input type="date" class="form-control" id="fecha_ult_revision" placeholder="Fecha" value="<?php echo pon_fecha_datetime(pon_dato_row('fecha_ult_revision', $row_analizadores)); ?>" required>
                </section>
            </div>
        </div>

        <?php /*<div class="form-group">
                                                    <div class="row">
                                                        <section class="col-xs-6">
                                                            <label>Fecha última revisión</label>
                                                            <input type="date" class="form-control" id="fecha_ultima_revision" placeholder="Fecha" value="<?php echo pon_dato_row('n_analizador', $row_analizadores); ?>">
                                                        </section>
                                                        <section class="col-xs-6">
                                                            <label>Presión última revisión</label>
                                                            <input type="text" class="form-control" id="presion_ultima_revision" placeholder="Presión" value="<?php echo pon_dato_row('descripcion', $row_analizadores); ?>">
                                                        </section>
                                                    </div>
                                                </div> */ ?>

    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="form-group">
            <div class="row">
                <section class="col-xs-12">
                    <label>Observaciones</label>
                    <textarea id="observaciones" class="form-control" placeholder="Observaciones" rows="8"><?php echo pon_dato_row('observaciones', $row_analizadores); ?></textarea>
                </section>
            </div>
        </div>

    </div>

    <div class="col-xs-12">
        <section>
            <div class="row" style="margin-top: 2px;">
                <div class="col-xs-12">
                    <label>Componentes</label>
                </div>
                <div class="col-xs-12">
                    <table style="width: 100%;">
                        <tr>
                            <?php
                            $unidades = count($arr_componentes) / 2;
                            $ancho = 100 / $unidades;
                            $contador = 0;
                            foreach ($arr_componentes AS $count => $componente) {
                                if (($contador / $unidades) == round($contador / $unidades)) echo "</tr><tr>";
                                echo '<td style="width: ' . $ancho . '%; white-space: nowrap; padding-bottom: 6px;">';
                                echo '    <label class="checkbox-inline">';
                                echo '        <input type="checkbox" id="componente_' . $componente . '" rel="' . $componente . '" class="checkbox check_componente" name="remember" value="' . $componente . '" ' . pon_set_campo(busca_componente($componente), 'componente', 'checked') . '>';
                                echo '        <span>' . $componente . '</span>';
                                echo '    </label>';
                                echo '</td>';
                                $contador++;
                            }
                            ?>
                        </tr>
                    </table>
                </div>
            </div>
        </section>

        <section class="margin-top-20">
            <table class="table table-condensed table-bordered" id="tabla_componentes">
                <thead>
                <tr>
                    <td>Componente</td>
                    <td>Func.Calibración</td>
                    <td>Lím.alarmas DCS</td>
                    <td>Lím.emisión</td>
                    <td>Lím.legal diario</td>
                    <td>Rango medida</td>
                    <td>Incertidumbre</td>
                    <td>Unidad</td>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </section>
    </div>

    <input type="hidden" id="id_analizadores" value="<?php echo pon_dato_row('id_analizadores', $row_analizadores); ?>">

</fieldset>
