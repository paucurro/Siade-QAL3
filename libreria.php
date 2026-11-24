<?php
$Codes64 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz+/';
$SecurityString = 'NwL314FrP78/n6UG';

$arr_dias_mes = range(1, 31);
$arr_semana = [0 => 'lunes', 1 => 'martes', 2 => 'miércoles', 3 => 'jueves', 4 => 'viernes', 5 => 'sábado', 6 => 'domingo'];
$arr_meses = [1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril', 5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto', 9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'];
$arr_meses_cortos = [1 => 'ene', 2 => 'feb', 3 => 'mar', 4 => 'abr', 5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'ago', 9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dic'];
$arr_trimestres = [1 => "1er trimestre", 2 => "2o trimestre", 3 => "3er trimestre", 4 => "4o trimestre"];
$arr_cuatrimestres = [1 => "1er quatrimestre", 2 => "2o quatrimestre", 3 => "3er quatrimestre"];


/**
 * @param $sql
 * @return bool|mysqli_result|void
 */
function putDataDb($sql)
{
    //return; 
    global $database_qal3_db, $qal3_db;

    mysqli_select_db($qal3_db, $database_qal3_db);
    $Result1 = mysqli_query($qal3_db, $sql);

    if (!$Result1) {
        echo die(mysqli_error($qal3_db));
        exit;
    }
    return $Result1;
}


/**
 * @param $sql
 * @return array|null|string
 */
function getAllDataDB($sql)
{
    global $qal3_db, $database_qal3_db;

    $db = $qal3_db;
    $database = $database_qal3_db;

    mysqli_select_db($db, $database);
    $data = mysqli_query($db, $sql);

    if ($data == false) {
        $devuelto = mysqli_error($db);
    } else {
        $devuelto = mysqli_fetch_all($data, MYSQLI_ASSOC);
        mysqli_free_result($data);
    }

    return $devuelto;

}

/**
 * @param $sql
 * @return array|null|string
 */
function setSqlDB($sql)
{
    global $qal3_db, $database_qal3_db;

    $db = $qal3_db;
    $database = $database_qal3_db;

    mysqli_select_db($db, $database);
    $data = mysqli_query($db, $sql);

    if ($data == false) {
        $devuelto = mysqli_error($db);
    } else {
        $devuelto = mysqli_fetch_array($data, MYSQLI_ASSOC);
        mysqli_free_result($data);
    }

    return $devuelto;

}


/**
 * @param $nombre_recurso
 * @return bool
 */
function control_recurso($nombre_recurso)
{
    global $contadores_app, $database_contadores_app;

    // Aqui tenemos que comprobar si el usuario tiene acceso al recurso
    $devuelto = false;

    if ($nombre_recurso != "") {
        if ($_SESSION['datos_usuario_qal3']->row_usuarios['usuario_usuarios'] != 'pau_curro') {
            if (is_array($nombre_recurso))
                $valores = $nombre_recurso;
            else
                $valores = array($nombre_recurso);

            if (count($valores) > 0) {
                $filtro = "";
                foreach ($valores as $index => $valor) {
                    if ($index == 0)
                        $filtro .= " AND (recursos.titulo_recursos = '" . $valor . "'";
                    else
                        $filtro .= " OR recursos.titulo_recursos = '" . $valor . "'";
                }
                if ($filtro != "") $filtro .= ")";

                mysqli_select_db($contadores_app, $database_contadores_app);
                $query_datos = "SELECT * FROM explotacion.recursos "
                    . "LEFT JOIN explotacion.usuarios_recursos ON usuarios_recursos.fk_recursos = recursos.id_recursos "
                    . "LEFT JOIN explotacion.usuarios ON usuarios.id = usuarios_recursos.usuarios_id "
                    . "WHERE usuarios.id = '" . $usuario_seleccionado . "' " . $filtro;
                $datos = mysqli_query($contadores_app, $query_datos) or die(mysqli_error($contadores_app));
                $totalRows_datos = mysqli_num_rows($datos);

                if ($totalRows_datos > 0) $devuelto = true;

                mysqli_free_result($datos);
            }
        } else {
            $devuelto = true;
        }
    }
    return $devuelto;
}

function pon_seleccionado($opcion, $texto, $comando = 'active', $parametros = [], $guarda = true)
{
    $resultado = "";
    if (is_array($texto)) {
        foreach ($texto as $valor) {
            if (strpos($opcion, $valor) !== false) {
                $resultado = $comando;
            }
        }
    } else {
        if (strpos($opcion, $texto) !== false) {
            $resultado = $comando;
        }
    }

    $envia = false;
    $cont_envia = 0;
    if (count($parametros) > 0) {
        foreach ($parametros as $ind_param => $param) {
            if ((isset($_REQUEST[$ind_param])) && ($_REQUEST[$ind_param] == $param)) {
                $cont_envia++;
            }
        }
        if ($cont_envia == count($parametros)) {
            $envia = true;
        }
    } else {
        $envia = true;
    }

    $resp = ($envia == true) ? $resultado : '';

    if (($resp != '') && ($guarda == true)) {
        guarda_log($texto);
    }
    return $resp;
}

function MakeRNDString($Chars, $Count)
{
    $Result = '';
    for ($i = 0; $i < ($Count); $i++) {
        $x = strlen($Chars) - rand(strlen($Chars), 0);
        //echo "--> x: ".$x."<br>";
        if (isset($Chars[$x]))
            $Result = $Result . $Chars[$x];
        $Chars = substr($Chars, 0, $x - 1) . substr($Chars, $x, strlen($Chars));
    }
    //echo " FIN -> count: ".$Count."<br>";
    return $Result;
}

function EncodePWDEx($Data)
{
    global $Codes64, $SecurityString;

    $MinV = 0;
    $MaxV = 3;

    if ($MinV > $MaxV) {
        $i = $MinV;
        $MinV = $MaxV;
        $MaxV = $i;
    }
    if ($MinV < 0) {
        $MinV = 0;
    }
    if ($MaxV > 100) {
        $MaxV = 100;
    }
    $Result = '';
    if (strlen($SecurityString) < 16) return "ENC_001";
    for ($i = 0; $i < strlen($SecurityString); $i++) {
        $s1 = substr($SecurityString, $i + 1, strlen($SecurityString));
        if (strpos($s1, $SecurityString[$i]) > 0) {
            return "ENC_002";
        }
        if (strpos($Codes64, $SecurityString[$i]) <= 0) {
            return "ENC_003";
        }
    }
    $s1 = $Codes64;
    $s2 = '';
    for ($i = 0; $i < strlen($SecurityString); $i++) {
        $x = strpos($s1, $SecurityString[$i]);
        //echo " xx--> s1: ".$s1.", x: ".$x."<br>";
        if ($x > 0) $s1 = substr($s1, 0, $x) . substr($s1, $x + 1, strlen($s1) - 1);
    }
    //echo "----> ".$x."<br>";
    $ss = $SecurityString;
    for ($i = 0; $i < strlen($Data); $i++) {
        //echo "Dato1: ".(int)floor((ord($Data[$i]) % 16) + 0)."<br>";
        $s2 = $s2 . $ss[(int)floor((ord($Data[$i]) % 16) + 0)];
        $ss = substr($ss, strlen($ss) - 1, 1) . substr($ss, 0, strlen($ss) - 1);
        //echo "1--> s2: ".$s2.", ss: ".$ss."<br>";
        //echo "Dato2: ".(int)floor((ord($Data[$i]) / 16) + 0)."<br>";
        $s2 = $s2 . $ss[(int)floor((ord($Data[$i]) / 16) + 0)];
        $ss = substr($ss, strlen($ss) - 1, 1) . substr($ss, 0, strlen($ss) - 1);
        //echo "2--> s2: ".$s2.", ss: ".$ss."<br>";
    }
    //echo "Data: ".$Data.", s1: ".$s1.", s2:".$s2."<br>";

    $Result = MakeRNDString($s1, rand($MaxV, $MinV) + 1);
    for ($i = 0; $i < strlen($s2); $i++) $Result = $Result . $s2[$i] . MakeRNDString($s1, rand($MaxV, $MinV));

    return $Result;
}


function DecodePWDEx($Data)
{
    global $Codes64, $SecurityString;

    $Result = '#1';
    if (strlen($SecurityString) < 16) return "001";
    for ($i = 0; $i < (strlen($SecurityString)); $i++) {
        $s1 = substr($SecurityString, $i + 1, strlen($SecurityString));
        if (strpos($s1, $SecurityString[$i]) > -1) return "002";
        if (strpos($Codes64, $SecurityString[$i]) <= 0) return "003";
    };
    $s1 = $Codes64;
    $s2 = '';
    $ss = $SecurityString;
    //echo "--->".$Data.", ".$ss."<br>";
    for ($i = 0; $i < (strlen($Data)); $i++) {
        if (strpos($ss, $Data[$i]) > -1) $s2 = $s2 . $Data[$i];
    }
    $Data = $s2;
    $s2 = '';
    //echo "--->".$Data."<br>";
    if ((strlen($Data) % 2) <> 0) return "004";
    for ($i = 0; $i < ((strlen($Data) / 2)); $i++) {
        $x = strpos($ss, $Data[$i * 2]);
        //echo "0---> ".$ss.", i*2: ".$Data[$i * 2].", x: ".$x."<br>";
        if ($x < 0) return "005";
        $ss = substr($ss, strlen($ss) - 1, 1) . substr($ss, 0, strlen($ss) - 1);
        $x2 = strpos($ss, $Data[($i * 2) + 1]);
        //echo "1--> ".$ss.", i*2+1: ".$Data[($i * 2) + 1].", x2: ".$x2.", (".$s2.")<br>";
        if ($x2 < 0) return "006";
        $x = $x + ($x2 * 16);
        $s2 = $s2 . chr($x);
        $ss = substr($ss, strlen($ss) - 1, 1) . substr($ss, 0, strlen($ss) - 1);
        //echo "2---> x: ".$x.", x2: ".$x2.", (".$s2.")<br>";
    }
    $Result = $s2;
    return $Result;
}

;

$valores_fecha_diario = array('1 mes' => 1, '3 meses' => 2, '6 meses' => 3, 'Todo el a&ntilde;o' => 4);

function RegistraAccesoEnLog($id_usuario, $id_recurso)
{
    //return;
    global $database_qal3_db, $qal3_db;

    $comandSQL = "INSERT INTO log_usuarios (fechahora, fk_usuario, fk_niveles_recursos) VALUES ('" . date("Y-m-d H:i:s") . "', '" . $id_usuario . "','" . $id_recurso . "')";
    mysqli_select_db($qal3_db, $database_qal3_db);
    $Result1 = mysqli_query($qal3_db, $comandSQL);

    if (!$Result1) {
        echo die(mysqli_error($qal3_db));
        exit;
    }

}


function comprueba_opcion($recurso)
{
    /*
        Esto se comprueba si el usuario actual tiene permiso del recurso indicado.
    */
    global $database_qal3_db, $qal3_db, $usuario_seleccionado;

    //$id_usuario = $_SESSION['datos_usuario_qal3']->row_usuarios['id'];

    if (is_array($recurso)) {
        $filtro = "AND nombre_recursos IN ('" . implode("','", $recurso) . "')";
    } else {
        $filtro = "AND nombre_recursos = '" . $recurso . "'";
    }


    mysqli_select_db($qal3_db, $database_qal3_db);
    $query_tb_recursos = "SELECT * FROM explotacion.usuarios_recursos LEFT JOIN explotacion.usuarios ON usuarios.id = usuarios_recursos.usuarios_id LEFT JOIN explotacion.recursos ON recursos.id_recursos = usuarios_recursos.recursos_id WHERE 1 " . $filtro . " ";
    $tb_recursos = mysqli_query($qal3_db, $query_tb_recursos) or die(mysqli_error($qal3_db));
    $row_tb_recursos = mysqli_fetch_assoc($tb_recursos);
    $totalRows_tb_recursos = mysqli_num_rows($tb_recursos);

    $resultado = false;
    if ($totalRows_tb_recursos > 0) {
        do {
            if (($usuario_seleccionado == $row_tb_recursos['id']) or ($_SESSION['datos_usuario_qal3']->row_usuarios['Usuario'] == 'admin')) {
                $resultado = true;
                $_SESSION['ULTIMO_RECURSO_COMPROBADO'] = $row_tb_recursos['id_recursos'];
            }
        } while ($row_tb_recursos = mysqli_fetch_assoc($tb_recursos));
    }

    mysqli_free_result($tb_recursos);

    return $resultado;
}

function guarda_log($pagina)
{
    $devuelto = false;
    if (is_array($pagina)) {
        if (count($pagina) > 0) {
            foreach ($pagina as $recurso) {
                if ($devuelto == false) {
                    $devuelto = (stripos($_SERVER['PHP_SELF'], $recurso) !== false);
                }
            }
        }
    }

    if ($devuelto) {
        if ($_SESSION['ULTIMO_RECURSO_COMPROBADO'] != '') {
            $id_recursos = $_SESSION['ULTIMO_RECURSO_COMPROBADO'];
            if ($_SESSION['ULTIMA_OPCION'] != $id_recursos) {
                RegistraAccesoEnLog($_SESSION['datos_usuario_qal3']->row_usuarios['id'], $id_recursos);
                $_SESSION['ULTIMA_OPCION'] = $id_recursos;
            }
        }
    }

    return true;
}

function pon_imagen($foto_p, $foto_g, $resize, $ancho, $alto, $descripcion, $alineacion, $clase, $vspace, $hspace, $borde_foto, $relacion, $titulo)
{
    if (($foto_p != NULL) && ($foto_p != "")) {
        if ($descripcion == "") $descripcion = "";
        if ($alineacion == "") $alineacion = "left";
        if ($clase == "") $clase = "";
        if ($vspace == "") $vspace = 0;
        if ($hspace == "") $hspace = 0;
        if ($borde_foto == "") $borde_foto = "0";
        if ($relacion == "") $relacion = $foto_p;

        if ((($foto_g != "") && ($foto_g != null)) || ($resize == true)) {
            $size1 = getimagesize($foto_p);
            //echo $size1[1]."-".$size1[0]."<br>";
            if ($resize == true) {
                if (($size1[1] > $alto) || ($size1[0] > $ancho)) {
                    $proporcion = (($size1[0] - $ancho) - ($size1[1] - $alto));
                    //$size1[0] / $size1[1];
                    if ($proporcion < 1) {
                        $size1[0] = $size1[0] * ($alto / $size1[1]);
                        $size1[1] = $alto;
                    } else {
                        $size1[1] = $size1[1] * ($ancho / $size1[0]);
                        $size1[0] = $ancho;
                    }
                }
            }
            $alto = $size1[1];
            $ancho = $size1[0];
            //echo $alto."-".$ancho."<br>";
            $resize = false; ?>

            <li>
                <a href="<?php echo $foto_g; ?>" class="prettyPhoto" rel="<?php echo $relacion; ?>" title="<?php echo $titulo; ?>">
                    <img src="<?php echo $foto_p; ?>" border="<?php echo $borde_foto; ?>" title="<?php echo $descripcion; ?>" align="<?php echo $alineacion; ?>" width="<?php echo $ancho; ?>" height="<?php echo $alto; ?>" class="<?php echo $clase; ?>"
                         vspace="<?php echo $vspace; ?>" hspace="<?php echo $hspace; ?>"/>
                </a>
            </li>
        <?php }
    } else {
        echo '<li><i class="fa fa-circle-o-notch"></i></li>';
    }
}

function pon_imagen_sin($foto_p, $foto_g, $resize, $ancho, $alto, $descripcion, $alineacion, $clase, $vspace, $hspace, $borde_foto, $relacion, $titulo)
{
    if (($foto_p != NULL) && ($foto_p != "")) {
        if ($descripcion == "") $descripcion = "";
        if ($alineacion == "") $alineacion = "left";
        if ($clase == "") $clase = "";
        if ($vspace == "") $vspace = 0;
        if ($hspace == "") $hspace = 0;
        if ($borde_foto == "") $borde_foto = "0";
        if ($relacion == "") $relacion = "mando"; //$foto_p;

        if (file_exists($foto_p)) {
            if ((($foto_g != "") && ($foto_g != null)) || ($resize == true)) {
                $size1 = getimagesize($foto_p);
                if ($resize == true) {
                    if (($size1[1] > $alto) || ($size1[0] > $ancho)) {
                        $proporcion = (($size1[0] - $ancho) - ($size1[1] - $alto));
                        if ($proporcion < 1) {
                            $size1[0] = round($size1[0] * ($alto / $size1[1]));
                            $size1[1] = $alto;
                        } else {
                            $size1[1] = round($size1[1] * ($ancho / $size1[0]));
                            $size1[0] = $ancho;
                        }
                    }
                }
                $alto = $size1[1];
                $ancho = $size1[0];
                $resize = false; ?>
                <a href="<?php echo $foto_g; ?>" class="prettyPhoto" rel="<?php echo $relacion; ?>" title="<?php echo $titulo; ?>">
                    <img src="<?php echo $foto_p; ?>" border="<?php echo $borde_foto; ?>" title="<?php echo $descripcion; ?>" align="<?php echo $alineacion; ?>" width="<?php echo $ancho; ?>" height="<?php echo $alto; ?>" class="<?php echo $clase; ?>"
                         vspace="<?php echo $vspace; ?>" hspace="<?php echo $hspace; ?>"/>
                </a>
            <?php } else {
                $size1 = getimagesize($foto_p);
                $alto = $size1[1];
                $ancho = $size1[0];
                echo '<img src="' . $foto_p . '" width="' . $ancho . '" height="' . $alto . '" align="' . $alineacion . '" border="' . $borde_foto . '" vspace="' . $vspace . '" hspace="' . $hspace . '" class="' . $clase . '">';
            }
        } ?>
    <?php } else {
        if (($ancho > 300) and ($resize == true)) {
            if ($resize == true) { ?>
                <img src="imagenes/nofoto_g.jpg" align="<?php echo $alineacion; ?>" width="<?php echo $ancho; ?>" vspace="<?php echo $vspace; ?>" hspace="<?php echo $hspace; ?>" class="<?php $clase; ?>"/>
            <?php } else { ?>
                <img src="imagenes/nofoto_g.jpg" align="<?php echo $alineacion; ?>" vspace="<?php echo $vspace; ?>" hspace="<?php echo $hspace; ?>" class="<?php $clase; ?>"/>
            <?php } ?>
        <?php } else { ?>
            <img src="imagenes/nofoto.jpg" align="<?php echo $alineacion; ?>" height="<?php echo $alto; ?>" vspace="<?php echo $vspace; ?>" hspace="<?php echo $hspace; ?>" class="<?php $clase; ?>"/>
        <?php }
    }
}

function pon_imagen_link($foto_p, $foto_g, $resize, $ancho, $alto, $descripcion, $alineacion, $clase, $vspace, $hspace, $borde_foto, $relacion, $titulo, $link, $destino)
{
    if (($foto_p != NULL) && ($foto_p != "")) {
        if ($descripcion == "") $descripcion = "Pulse sobre la imagen para verla con detalle";
        if ($alineacion == "") $alineacion = "left";
        if ($clase == "") $clase = "";
        if ($vspace == "") $vspace = 0;
        if ($hspace == "") $hspace = 0;
        if ($borde_foto == "") $borde_foto = "0";
        if ($relacion == "") $relacion = "mando"; //$foto_p;

        if ((($foto_g != "") && ($foto_g != null)) || ($resize == true)) {
            $size1 = getimagesize($foto_p);
            //echo $size1[1]."-".$size1[0]."<br>";
            if ($resize == true) {
                if (($size1[1] > $alto) || ($size1[0] > $ancho)) {
                    $proporcion = (($size1[0] - $ancho) - ($size1[1] - $alto));
                    //$size1[0] / $size1[1];
                    if ($proporcion < 1) {
                        $size1[0] = round($size1[0] * ($alto / $size1[1]));
                        $size1[1] = $alto;
                    } else {
                        $size1[1] = round($size1[1] * ($ancho / $size1[0]));
                        $size1[0] = $ancho;
                    }
                }
            }
            $alto = $size1[1];
            $ancho = $size1[0];
            //echo $alto."-".$ancho."<br>";
            $resize = false; ?>
            <a href="<?php echo $link; ?>" title="<?php echo $titulo; ?>" target="<?php echo $destino; ?>">
                <img src="<?php echo $foto_p; ?>" border="<?php echo $borde_foto; ?>" title="<?php echo $descripcion; ?>" align="<?php echo $alineacion; ?>" width="<?php echo $ancho; ?>" height="<?php echo $alto; ?>" class="<?php echo $clase; ?>"
                     vspace="<?php echo $vspace; ?>" hspace="<?php echo $hspace; ?>"/>
            </a>
        <?php } else {
            echo '<img src="' . $foto_p . '" width="' . $ancho . '" height="' . $alto . '" align="' . $alineacion . '" border="' . $borde_foto . '" vspace="' . $vspace . '" hspace="' . $hspace . '" class="' . $clase . '">';
        } ?>
    <?php } else {
        if (($ancho > 300) and ($resize == true)) { ?>
            <img src="imagenes/nofoto_g.jpg" align="<?php echo $alineacion; ?>" vspace="<?php echo $vspace; ?>" hspace="<?php echo $hspace; ?>" class="<?php $clase; ?>"/>
        <?php } else { ?>
            <img src="imagenes/nofoto.jpg" align="<?php echo $alineacion; ?>" vspace="<?php echo $vspace; ?>" hspace="<?php echo $hspace; ?>" class="<?php $clase; ?>"/>
        <?php }
    }
}

function pon_archivo($archivo, $texto = "")
{
    if ($archivo != NULL) {
        if (file_exists($archivo)) {
            $extension = basename($archivo);
            $extension = explode(".", $extension);
            $imagen = "imagenes/document.gif";
            if ($extension[1] == "pdf") $imagen = "imagenes/pdf_ico.gif";
            if ($extension[1] == "doc") $imagen = "imagenes/word_ico.gif";
            if ($extension[1] == "xls") $imagen = "imagenes/excel.gif";
            if (($extension[1] == "avi") || ($extension[1] == "mov") || ($extension[1] == "mpg") || ($extension[1] == "asx") || ($extension[1] == "wma") || ($extension[1] == "wmv")) $imagen = "imagenes/avi.gif";

            echo '<a href="' . $archivo . '" target="_blank" class="link_pdf">' . $texto . "</a>&nbsp;"; ?>
            <a href="<?php echo $archivo; ?>" target="_blank"><img src=<?php echo $imagen; ?> hspace="4" border="0" align="absmiddle" title="Pulse sobre este icono para abrir o descargar." alt="<?php echo $archivo; ?>"></a>
        <?php } else {
            echo "El archivo no se encuentra en el directorio.";
        }
    }
}

function devuelve_extension(&$dato)
{
    $resultado = explode(".", $dato);
    return $resultado[1];
}

$meses = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
$meses_largo = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

function pon_fecha($fecha, $largo)
{
    global $meses, $meses_largo;
    //echo "<".$fecha."><br>";
    $fecha = $fecha * 86400; // Ojo en Base de datos se guarda corta

    $mes = (int)date("m", $fecha);
    if ($largo == false)
        $texto = date("d", $fecha) . "-" . $meses[$mes] . "-" . date("Y", $fecha);
    else
        $texto = date("d", $fecha) . "-" . $meses_largo[$mes] . "-" . date("Y", $fecha);
    return $texto;
}

;

function convierte_fecha($fecha)
{
    // Debemos convertir la fecha de 18-10-2011 a int
    $devuelto = "";
    if ($fecha != "") {
        if (strpos($fecha, "-") > 0)
            $temp_fecha = explode("-", $fecha);
        else
            $temp_fecha = explode("/", $fecha);
        $devuelto = mktime(12, 0, 0, $temp_fecha[1], $temp_fecha[0], $temp_fecha[2]);
    }
    return $devuelto;
}

function dame_fecha_0($fecha, $con_hora = false)
{
    date_default_timezone_set('Europe/Madrid');
    // Debemos convertir la fecha de AAAA-MM-DD a int
    // Hora,Minuto,Segundo
    $hora = [0, 0, 0];
    if (strpos($fecha, " ") > 0) {
        // Tiene hora, hay que quitarla
        $temp = explode(" ", $fecha);
        $fecha = $temp[0];
        $temp_hora = $temp[1];
        if ($con_hora) {
            $hora = explode(":", $temp_hora);
        }
    }
    if (strpos($fecha, "-") > 0) {
        $temp_fecha = explode("-", $fecha);
    } else {
        // Debemos girar los datos para que MKTime funcione cuando viene DD/MM/YYYY
        $temp_fecha = explode("/", $fecha);
        // 0=YEAR, 1=MONTH, 2=DAY
        $temp_fecha = [$temp_fecha[2], $temp_fecha[1], $temp_fecha[0]];
    }
    return mktime($hora[0], $hora[1], $hora[2], $temp_fecha[1], $temp_fecha[2], $temp_fecha[0]);
}

function dame_fecha_junta($fecha)
{
    // Debemos convertir la fecha de AAAAMMDD a int
    $temp_fecha[2] = substr($fecha, 0, 4);
    $temp_fecha[1] = substr($fecha, 4, 2);
    $temp_fecha[0] = substr($fecha, 6, 2);
    return mktime(0, 0, 0, $temp_fecha[1], $temp_fecha[0], $temp_fecha[2]);
}

function dame_hora_junta($hora)
{
    // Debemos convertir la fecha de hhmmss a string
    // Es posible que la hora venga solo un digito, con lo cual añadimos 0
    //if (strlen($hora) < 6) $hora = '0'+$hora;
    $hora = str_pad(trim($hora), 6, "0", STR_PAD_LEFT);
    $temp_fecha[0] = substr($hora, 0, 2);
    $temp_fecha[1] = substr($hora, 2, 2);
    $temp_fecha[2] = substr($hora, 4, 2);
    return $temp_fecha[0] . ":" . $temp_fecha[1]; //.":".$temp_fecha[2];//."(".strlen($hora).")";
}

function dame_fecha_hora($fecha)
{
    // Debemos convertir la fecha de AAAA-MM-DD a int
    $datos = explode(" ", $fecha);
    $fecha = $datos[0];
    $hora = $datos[1];

    if (strpos($fecha, "-") > 0)
        $temp_fecha = explode("-", $fecha);
    else
        $temp_fecha = explode("/", $fecha);

    $horas = explode(":", $hora);
    $horas_seg = 0;
    if (count($horas) > 2) $horas_seg = $horas[2];
    return mktime($horas[0], $horas[1], $horas_seg, $temp_fecha[1], $temp_fecha[2], $temp_fecha[0]);
}

function dame_fecha_hora_utc($fecha)
{
    // Debemos convertir la fecha de AAAA-MM-DD a int
    date_default_timezone_set('UTC');
    $datos = explode(" ", $fecha);
    $fecha = $datos[0];
    $hora = $datos[1];

    if (strpos($fecha, "-") > 0)
        $temp_fecha = explode("-", $fecha);
    else
        $temp_fecha = explode("/", $fecha);

    $horas = explode(":", $hora);
    return mktime($horas[0], $horas[1], $horas[2], $temp_fecha[1], $temp_fecha[2], $temp_fecha[0]);
}


function convierte_fecha_string($fecha)
{
    if (strpos($fecha, "-") > 0)
        $valores_fecha = explode("-", $fecha);
    else
        $valores_fecha = explode("/", $fecha);

    return $valores_fecha[2] . "/" . $valores_fecha[1] . "/" . $valores_fecha[0];
}

function convierte_fecha_string_guiones($fecha)
{
    if (strpos($fecha, "-") > 0)
        $valores_fecha = explode("-", $fecha);
    else
        $valores_fecha = explode("/", $fecha);

    return $valores_fecha[2] . "-" . $valores_fecha[1] . "-" . $valores_fecha[0];
}

function invierte_fecha_string($fecha)
{
    // Entra dd/mm/AAA
    $valores_fecha = explode("/", $fecha);
    // Devolvemos AAAA-mm-dd
    return date("Y-m-d", mktime(12, 0, 0, $valores_fecha[1], $valores_fecha[0], $valores_fecha[2]));
}

function invierte_fecha_hora_string($fecha, $hora)
{
    // Entra dd/mm/AAA
    $valores_fecha = explode("/", $fecha);
    $valores_hora = explode(":", $hora);
    // Devolvemos AAAA-mm-dd
    return date("Y-m-d H:m:s", mktime($valores_hora[0], $valores_hora[1], 0, $valores_fecha[1], $valores_fecha[0], $valores_fecha[2]));
}

// **********************************************************************************************************************************
// FUNCIONES PARA SUBIR IMAGENES AL SERVIDOR
// **********************************************************************************************************************************

function limpiar_acentos($s)
{
    $s = strtolower($s);

    $vars = array("á", "ŕ", "â", "ă", "Ş");
    $s = str_replace($vars, "a", $s);

    $vars = array("Á", "Ŕ", "Â", "Ă");
    $s = str_replace($vars, "A", $s);

    $vars = array("Í", "Ě", "Î");
    $s = str_replace($vars, "I", $s);

    $vars = array("í", "ě", "î");
    $s = str_replace($vars, "i", $s);

    $vars = array("é", "č", "ę");
    $s = str_replace($vars, "e", $s);

    $vars = array("É", "Č", "Ę");
    $s = str_replace($vars, "E", $s);

    $vars = array("&oacute;", "ň", "ô", "ő", "ş");
    $s = str_replace($vars, "o", $s);

    $vars = array("&oacute;", "Ň", "Ô", "Ő");
    $s = str_replace($vars, "O", $s);

    $vars = array("ú", "ů", "ű");
    $s = str_replace($vars, "u", $s);

    $vars = array("Ú", "Ů", "Ű");
    $s = str_replace($vars, "U", $s);

    $s = str_replace("", "e", $s);
    $s = str_replace("ç", "c", $s);
    $s = str_replace("Ç", "C", $s);
    $s = str_replace("ń", "n", $s);
    $s = str_replace("Ń", "N", $s);
    $s = str_replace(",", "", $s);
    $s = str_replace("ş", "", $s);
    $s = str_replace("Ş", "", $s);
    $s = str_replace(" ", "", $s);
    $s = str_replace("-", "_", $s);

    return $s;
}

function redimensionar_jpeg($img_original, $img_nueva, $ancho, $alto, $tipo, $solo_anchura)
{
    if ($tipo == 0)
        $img = imagecreatefromgif($img_original);
    else if (($tipo == 4) or ($tipo == 5))
        $img = imagecreatefrompng($img_original);
    else if ($tipo > 0)
        $img = imagecreatefromjpeg($img_original);

    $sizes = getimagesize($img_original);
    $alto_or = $sizes[1];
    $ancho_or = $sizes[0];

    /*
    echo "Alto marcado: ".$alto."<br>";
    echo "Ancho marcado: ".$ancho."<br>";
    echo "Alto leido: ".$alto_or."<br>";
    echo "Ancho leido: ".$ancho_or."<br>";
    echo "TIPO: ".$tipo."<br>";
    */

    if (($alto_or > $alto) || ($ancho_or > $ancho)) {
        $proporcion = $ancho_or / $alto_or;
        if ($solo_anchura == true) {
            $al = $alto_or * $ancho / $ancho_or;
            $an = $ancho;
        } else {
            if ($proporcion < 1) {
                $an = $ancho_or * $alto / $alto_or;
                $al = $alto;
            } else {
                $al = $alto_or * $ancho / $ancho_or;
                $an = $ancho;
            }
        }

        /*
        echo "Alto calculado: ".$al."<br>";
        echo "Ancho calculado: ".$an."<br>";
        */

        $thumb = imagecreatetruecolor($an, $al);  // imagecreate / imagecreatetruecolor

        /*
        echo "Creada imagen: ".$thumb."<br>";
        echo "imagesx($img): ".imagesx($img)."<br>";
        echo "imagesy($img): ".imagesy($img)."<br>";
        */
        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $an, $al, $ancho_or, $alto_or);  // imagecopyresized / imagecopyresampled
        imagedestroy($img);

        if ($tipo == 0)
            imagegif($thumb, $img_nueva);
        else if (($tipo == 4) or ($tipo == 5))
            imagepng($thumb, $img_nueva);
        else if ($tipo > 0)
            imagejpeg($thumb, $img_nueva);

        imagedestroy($thumb);
    } else {
        if ($tipo == 0)
            imagegif($img, $img_nueva);
        else if (($tipo == 4) or ($tipo == 5))
            imagepng($img, $img_nueva);
        else if ($tipo > 0)
            imagejpeg($img, $img_nueva);

    }

    return true;
}

function envia_imagen($directorio, $nombreorigen, $nombretemporal, $tipofichero, $tam_p, $tam_g, $crop = false, $solo_anchura = false, $cual = 0)
{
    /*
        $directorio = Directorio de destino normalmente "fotos_anuncios" sin barra ni nada
        $nombreorigen = Nombre original
        $nombretemporal = Temporal explorador
        $tipofichero = tipo
        $tam_p = Tamańo máximo pequeńo (ancho o alto)
        $tam_g =  Tamańo máximo grande (ancho o alto
        $crop = recortar imagen TRUE/FALSE
        $solo_anchura = S&oacute;lo tener en cuenta anchura para calculo (TRUE/FALSE)
        $cual = 0: crear ambas, 1: crear solo grande, 2: crear solo pequeńa

    */
    global $nombrefinal_p, $nombrefinal_g, $error;

    $nombrefinal_p = "";
    $nombrefinal_g = "";
    $error = "";
    if ($nombreorigen == "") {
        // Al estar en blanco, es OK
        echo "El nombre no puede estar en blanco\n";
        exit;
    }
    $types_array = array("image/gif", "image/pjpeg", "image/jpeg", "image/x-png", "image/png");
    //echo $tipofichero."<br>";
    if (!in_array($tipofichero, $types_array)) {
        echo "No se puede cargar este formato de imagen! (" . $tipofichero . ")\n";
        exit;
    }
    $tipo = 0;
    $tipo = array_search($tipofichero, $types_array);

    //echo $tipo."<br>";

    $nombreorigen = limpiar_acentos($nombreorigen);

    // Primero colocamos en la temporal
    $nombrefinal = $directorio . "/originales/" . $nombreorigen;
    if (!move_uploaded_file($nombretemporal, $nombrefinal)) {
        $nombrefinal_p = "Error enviando";
        $nombrefinal_g = "Error enviando";
    } else {
        chmod($nombrefinal, 0644);

        if ($cual < 2) {
            //redimensionar_jpeg($img_original, $img_nueva, $ancho, $alto, $tipo, $solo_anchura)
            if (redimensionar_jpeg($nombrefinal, $directorio . '/grandes/' . $nombreorigen, $tam_g, $tam_g, $tipo, $solo_anchura) == TRUE)
                $nombrefinal_g = $directorio . '/grandes/' . $nombreorigen;
            else
                $nombrefinal_g = "Error subiendo imagen";
        }
        if ($cual == 2) {
            if (redimensionar_jpeg($nombrefinal, $directorio . '/peque/' . $nombreorigen, $tam_p, $tam_p, $tipo, $solo_anchura) == TRUE)
                $nombrefinal_p = $directorio . '/peque/' . $nombreorigen;
            else
                $nombrefinal_p = "Error subiendo imagen";
        }
    } // if no upload
    unlink($nombrefinal);
    //echo "--> ".$nombrefinal_p .", ". $nombrefinal_g .", ". $nombreorigen .", ". $nombrefinal ."<br>";
}

function envia_imagen_id($directorio, $nombreorigen, $nombretemporal, $tipofichero, $tam_p, $tam_g, $crop = false, $solo_anchura = false, $cual = 0, $id_foto = null)
{
    /*
        $directorio = Directorio de destino normalmente "fotos_anuncios" sin barra ni nada
        $nombreorigen = Nombre original
        $nombretemporal = Temporal explorador
        $tipofichero = tipo
        $tam_p = Tamańo máximo pequeńo (ancho o alto)
        $tam_g =  Tamańo máximo grande (ancho o alto
        $crop = recortar imagen TRUE/FALSE
        $solo_anchura = S&oacute;lo tener en cuenta anchura para calculo (TRUE/FALSE)
        $cual = 0: crear ambas, 1: crear solo grande, 2: crear solo pequeńa

    */
    global $nombrefinal_p, $nombrefinal_g, $error;

    $nombrefinal_p = "";
    $nombrefinal_g = "";
    $error = "";
    if ($nombreorigen == "") {
        // Al estar en blanco, es OK
        //echo "El nombre no puede estar en blanco\n";
        return;
    }
    $types_array = array("image/gif", "image/pjpeg", "image/jpeg", "image/x-png", "image/png");
    //echo $tipofichero."<br>";
    if (!in_array($tipofichero, $types_array)) {
        //echo "No se puede cargar este formato de imagen! (".$tipofichero.")\n";
        return;
    }
    $tipo = 0;
    $tipo = array_search($tipofichero, $types_array);

    $nombreorigen = "e_" . $id_foto . "_" . limpiar_acentos($nombreorigen);

    // Primero colocamos en la temporal
    $nombrefinal = $directorio . "/originales/" . $nombreorigen;
    if (!move_uploaded_file($nombretemporal, $nombrefinal)) {
        $nombrefinal_p = "Error enviando";
        $nombrefinal_g = "Error enviando";
    } else {
        chmod($nombrefinal, 0644);

        if ($cual < 2) {
            //redimensionar_jpeg($img_original, $img_nueva, $ancho, $alto, $tipo, $solo_anchura)
            if (redimensionar_jpeg($nombrefinal, $directorio . '/grandes/' . $nombreorigen, $tam_g, $tam_g, $tipo, $solo_anchura) == TRUE)
                $nombrefinal_g = $directorio . '/grandes/' . $nombreorigen;
            else
                $nombrefinal_g = "Error subiendo imagen";
        }
        if (cual <> 2) {
            if (redimensionar_jpeg($nombrefinal, $directorio . '/peque/' . $nombreorigen, $tam_p, $tam_p, $tipo, $solo_anchura) == TRUE)
                $nombrefinal_p = $directorio . '/peque/' . $nombreorigen;
            else
                $nombrefinal_p = "Error subiendo imagen";
        }
    } // if no upload
    unlink($nombrefinal);
    return $error;
}

function envia_documento($directorio, $nombreorigen, $nombretemporal, $id_foto)
{
    /*
        $directorio = Directorio de destino normalmente "fotos_anuncios" sin barra ni nada
        $nombreorigen = Nombre original
        $nombretemporal = Temporal explorador
        $id_foto = Identificador del anunciante cambio de nombre

    */
    global $nombredocumento;

    /* echo "Directorio: ".$directorio."<br>";
    echo "Temporal: ".$nombretemporal."<br>";
    echo "Origen: ".$nombreorigen."<br>";
    echo "ID: ".$id_foto."<br>";  */

    $nombredocumento = "";
    $error = "";
    if ($nombreorigen == "") {
        // Al estar en blanco, es OK
        $error = "El nombre no puede estar en blanco";
        exit;
    }
    $nombreorigen = "doc_" . $id_foto . "_" . limpiar_acentos($nombreorigen);

    // Primero colocamos en la temporal
    $nombredocumento = $directorio . $nombreorigen;
    $error = move_uploaded_file($nombretemporal, $nombredocumento);
    if (!$error) {
        $error = "Error enviando";
    } // if no upload

    /* echo "Nombre Archivo: ".$nombredocumento."<br>";  */

    return $error;
}

function hyperlink($text)
{
    // si coindice protocolo://direccion/ruta/
    $text = ereg_replace("[a-zA-Z]+://([.]?[a-zA-Z0-9_/-])*", "<a href=\"\0\">\0</a>", $text);

    // si coincide www.something
    $text = ereg_replace("(^| )(www([.]?[a-zA-Z0-9_/-])*)", "\1<a href=\"http://\2\">\2</a>", $text);

    return $text;
}

function cortar_texto($string, $limit, $break = " ", $pad = "...")
{
    // return with no change if string is shorter than $limit
    if (strlen($string) <= $limit)
        return $string;

    $string = substr($string, 0, $limit);
    if (false !== ($breakpoint = strrpos($string, $break))) {
        $string = substr($string, 0, $breakpoint);
    }
    return $string . $pad;
}


function filedata($path)
{
    // Vaciamos la caché de lectura de disco
    clearstatcache();
    // Comprobamos si el fichero existe
    $data["exists"] = is_file($path);
    // Comprobamos si el fichero es escribible
    $data["writable"] = is_writable($path);
    // Leemos los permisos del fichero
    $data["chmod"] = ($data["exists"] ? substr(sprintf("%o", fileperms($path)), -4) : FALSE);
    // Extraemos la extensi&oacute;n, un s&oacute;lo paso
    $data["ext"] = substr(strrchr($path, "."), 1);
    // Primer paso de lectura de ruta
    $data["path"] = array_shift(explode("." . $data["ext"], $path));
    // Primer paso de lectura de nombre
    $data["name"] = array_pop(explode("/", $data["path"]));
    // Ajustamos nombre a FALSE si está vacio
    $data["name"] = ($data["name"] ? $data["name"] : FALSE);
    // Ajustamos la ruta a FALSE si está vacia
    $data["path"] = ($data["exists"] ? ($data["name"] ? realpath(array_shift(explode($data["name"], $data["path"]))) : realpath(array_shift(explode($data["ext"], $data["path"])))) : ($data["name"] ? array_shift(explode($data["name"], $data["path"])) : ($data["ext"] ? array_shift(explode($data["ext"], $data["path"])) : rtrim($data["path"], "/"))));
    // Ajustamos el nombre a FALSE si está vacio o a su valor en caso contrario
    $data["filename"] = (($data["name"] or $data["ext"]) ? $data["name"] . ($data["ext"] ? "." : "") . $data["ext"] : FALSE);
    // Devolvemos los resultados
    return $data;
}

function parsearray($field, $alist, $sep, $comp = "=", $quoted = 0)
{
    $tarr = explode($sep, $alist);
    if ($quoted) {
        $q1 = $q2 = "'";
        if (strtolower($comp) == "like") {
            $q1 = "'%";
            $q2 = "%'";
        }
    } else {
        $q1 = $q2 = "";
    }
    $out = $field . " " . $comp . " " . $q1 . $tarr[0] . $q2;
    if (count($tarr) > 1) {
        for ($i = 1; $i < count($tarr); $i++) {
            if ($tarr[$i] != "") {
                $out .= " or " . $field . " " . $comp . " " . $q1 . $tarr[$i] . $q2;
            }
        }
    }
    return "(" . $out . ")";
}

function pon_boton($color, $texto, $link = "", $id = "", $estilo = "", $class = "")
{
    if ($id != "") $id = 'id="' . $id . '"';
    if ($class != "") $class = 'class="' . $class . '"';
    $devuetlo = '<table border="0" cellspacing="0" cellpadding="0" class="estilo_pon_boton_' . $color . '" style="' . $estilo . '">
								<tr>
									<td width="9"><img src="imagenes/btn_' . $color . '_iz.png" width="9" height="23" /></td>
									<td style="background-image: url(imagenes/btn_' . $color . '_cn.png); background-position: left top; background-repeat: repeat-x; font-weight: bold; cursor: pointer;" ' . $id . ' ' . $class . ' nowrap="nowrap">';
    if ($link != "") $devuetlo .= '<a href="' . $link . '">';
    $devuetlo .= $texto;
    if ($link != "") $devuetlo .= '</a>';
    $devuetlo .= '</td>
									<td><img src="imagenes/btn_' . $color . '_de.png" width="9" height="23" /></td>
								</tr>
							</table>';
    return $devuetlo;
}

function pon_boton_g($color, $texto, $link = "", $id = "", $estilo = "", $class = "")
{
    if ($id != "") $id = 'id="' . $id . '"';
    if ($class != "") $class = 'class="' . $class . '"';
    $devuetlo = '<table border="0" cellspacing="0" cellpadding="0" class="estilo_pon_boton_' . $color . '" style="' . $estilo . '">
								<tr>
									<td width="9"><img src="imagenes/btn_' . $color . '_g_iz.png" width="9" height="28" /></td>
									<td style="background-image: url(imagenes/btn_' . $color . '_g_cn.png); background-position: left top; background-repeat: repeat-x; font-weight: bold; cursor: pointer;" ' . $id . ' ' . $class . ' nowrap="nowrap">';
    if ($link != "") $devuetlo .= '<a href="' . $link . '">';
    $devuetlo .= $texto;
    if ($link != "") $devuetlo .= '</a>';
    $devuetlo .= '</td>
									<td><img src="imagenes/btn_' . $color . '_g_de.png" width="9" height="28" /></td>
								</tr>
							</table>';
    return $devuetlo;
}

function get_config($parametro, $crear_valor = false)
{
    global $database_qal3_db, $qal3_db;

    mysqli_select_db($qal3_db, $database_qal3_db);
    $query_config = sprintf("SELECT * FROM config WHERE param_config = '%s'", $parametro);
    $config = mysqli_query($qal3_db, $query_config) or die(mysqli_error($qal3_db));
    $row_config = mysqli_fetch_assoc($config);
    $totalRows_config = mysqli_num_rows($config);

    $resultado = "";
    if ($totalRows_config > 0) {
        $resultado = $row_config['valor_config'];
    } else {
        if ($crear_valor) {
            $comandSQL = sprintf("INSERT INTO config (param_config) VALUES ('%s')", $parametro);
            mysqli_select_db($qal3_db, $database_qal3_db);
            $Result1 = mysqli_query($qal3_db, $comandSQL) or die(mysqli_error($qal3_db));
        }
    }

    mysqli_free_result($config);

    return $resultado;
}


function pon_tipo_usuario($valor)
{
    $texto = "?? ";
    switch ($valor) {
        case 0:
            $texto = "Tablet";
            break;
        case 1:
            $texto = "Web";
            break;
        case 2:
            $texto = "Tablet y Web";
            break;
    }
    return $texto;
}

function titulos_lineas($numero)
{
    $texto = "??";
    switch ($numero) {
        case 1:
            $texto = "Línea " . $numero;
            break;
        case 2:
            $texto = "Línea " . $numero;
            break;
        case 3:
            $texto = "Línea " . $numero;
            break;
        case 4:
            $texto = "Línea " . $numero;
            break;
        case 5:
            $texto = "Comunes";
            break;
        case 6:
            $texto = "Niveles";
            break;
        case 10:
            $texto = "Resumen";
            break;
    }
    return $texto;
}

function titulos_dcs($numero)
{
    $texto = $numero;
    switch ($numero) {
        case 1:
            $texto = "Línea " . $numero;
            break;
        case 2:
            $texto = "Línea " . $numero;
            break;
        case 3:
            $texto = "Línea " . $numero;
            break;
        case 4:
            $texto = "Línea " . $numero;
            break;
    }
    return $texto;
}

if (!function_exists("esta_id_en_array")) {
    function esta_id_en_array($array, $variable, $valor_encontrar)
    {

        if (!isset($array) || !isset($variable) || !isset($valor_encontrar)) return false;

        for ($t = 0; $t < count($array); $t++) {
            if ($array[$t]->$variable == $valor_encontrar)
                return true;
        }
        return false;
    }
}

function dame_valor_desde_id($array, $variable, $valor_encontrar, $campo_devolver)
{
    $devuelto = "";

    for ($t = 0; $t < count($array); $t++) {
        if ($array[$t]->$variable == $valor_encontrar)
            $devuelto = $array[$t]->$campo_devolver;
        break;
    }
    return $devuelto;
}

function pon_valor_desde_id($array, $variable, $valor_encontrar, $campo_devolver, $valor_meter)
{
    $devuelto = "No";

    for ($t = 0; $t < count($array); $t++) {
        $valor = $array[$t]->$variable;
        if ($valor == $valor_encontrar) {
            $array[$t]->$campo_devolver = $valor_meter;
            $devuelto = "OK";
            break;
        }
    }
    return $devuelto;
}

function control_colores_lineas($param = 0)
{
    global $colors;

    if ($param == 1) {
        if (isset($_SESSION['filtro_dcs'])) {
            for ($t = 0; $t < count($_SESSION['filtro_dcs']); $t++) {
                $_SESSION['filtro_dcs'][$t]->color_escala = $colors[$t];
            }
            for ($r = 0; $r < count($_SESSION['formula_dcs']); $r++) {
                $_SESSION['formula_dcs'][$r]->color_escala = $colors[$r + $t];
            }
        }
    } else {
        $array_colores = [];
        // Camos a crear una lista temporal de colores

        if (isset($_SESSION['filtro_dcs'])) {
            // Si se ha definido la lista
            for ($t = 0; $t < count($_SESSION['filtro_dcs']); $t++) {
                // Buscamos en el array temporal si existe ese color
                if (in_array($_SESSION['filtro_dcs'][$t]->color_escala, $array_colores) or ($_SESSION['filtro_dcs'][$t]->color_escala == "")) {
                    // SI !!!! ya existe
                    for ($r = 0; $r < count($colors); $r++) {
                        // Buscamos en colores un color que no se haya seleccionado
                        if (!in_array($colors[$r], $array_colores)) {
                            $_SESSION['filtro_dcs'][$t]->color_escala = $colors[$r];
                            $array_colores[] = $colors[$r];
                            break;
                        }
                    }
                } else {
                    $array_colores[] = $_SESSION['filtro_dcs'][$t]->color_escala;
                }
            }
        }
    }

    if (isset($_SESSION['formula_dcs'])) {
        // Si se ha definido la lista
        for ($t = 0; $t < count($_SESSION['formula_dcs']); $t++) {
            // Buscamos en el array temporal si existe ese color
            if (in_array($_SESSION['formula_dcs'][$t]->color_escala, $array_colores) or ($_SESSION['formula_dcs'][$t]->color_escala == "")) {
                // SI !!!! ya existe
                for ($r = 0; $r < count($colors); $r++) {
                    // Buscamos en colores un color que no se haya seleccionado
                    if (!in_array($colors[$r], $array_colores)) {
                        $_SESSION['formula_dcs'][$t]->color_escala = $colors[$r];
                        $array_colores[] = $colors[$r];
                        break;
                    }
                }
            } else {
                $array_colores[] = $_SESSION['formula_dcs'][$t]->color_escala;
            }
        }
    }

    return true;
}


if (!function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param mixed
     * @return void
     */
    function dd($param)
    {
        array_map(function ($param) {
            var_dump($param);
        }, func_get_args());

        die(1);
    }
}


/**
 * @param $componente
 * @return array
 */
function busca_componente($componente)
{
    global $array_componentes_formulario;

    $arr_componente = [];
    if (isset($array_componentes_formulario)) {
        foreach ($array_componentes_formulario as $ind => $row) {
            if ($componente == $row['componente']) {
                $arr_componente = $row;
                continue;
            }
        }
    }
    return $arr_componente;
}

/**
 * @param $row
 * @param $campo
 * @param string $valor
 * @return string
 */
function pon_set_campo($row, $campo, $valor = 'selected')
{
    $devuelto = '';
    if (isset($row[$campo]) and ($row !== null)) {
        $devuelto = $valor;
    }
    return $devuelto;
}

/**
 * @param $campo
 * @param array $array
 * @return mixed|string
 */
function pon_dato_row($campo, $array = [])
{
    $valor_campo = '';
    if ($array !== null) {
        if (isset($array[$campo])) {
            if (($campo == 'fecha') || ($campo == 'fecha_qal1') || ($campo == 'fecha_qal2') || ($campo == 'fecha_qal1_mod') || ($campo == 'fecha_qal2_mod')) {
                $valor_campo = (empty($array[$campo])) ? date("Y-m-d") : date("Y-m-d", dame_fecha_0($array[$campo]));
            } else {
                $valor_campo = $array[$campo];
            }
        }
    } else {
        if (($campo == 'fecha') || ($campo == 'fecha_qal1') || ($campo == 'fecha_qal2') || ($campo == 'fecha_qal1_mod') || ($campo == 'fecha_qal2_mod')) {
            $valor_campo = date("Y-m-d");
        }
    }
    return $valor_campo;
}

function pon_fecha_datetime($fecha)
{
    $devuelto = '';
    if ($fecha != '') {
        $tmp_fecha = strtotime($fecha);
        if ($tmp_fecha !== false) {
            $devuelto = date("Y-m-d", $tmp_fecha);
        }
    }
    return $devuelto;
}


/**
 * @param $valor
 * @param $campo
 * @param array $array
 * @return string
 */
function pon_selected($valor, $campo, $array = [])
{
    $val_campo = '';
    if ($array !== null) {
        $val_campo = pon_dato_row($campo, $array);
    }
    return ($valor == $val_campo) ? 'selected' : '';
}

function convierte_numero($valor, $pon_cero = true)
{
    if ($valor == '') $valor = 0;
    $valor = str_replace(",", ".", $valor) * 1;
    return (($pon_cero) && ($valor == '') ? 0 : $valor);
}

$arr_componentes = [
    'HCL' => 'HCL',
    'O2' => 'O2',
    'CO' => 'CO',
    'SO2' => 'SO2',
    'NOx' => 'NOX',
    'PARTICULAS' => 'PARTICULAS',
    'H2O' => 'H2O',
    'TOC' => 'TOC',
    'NH3' => 'NH3',
    'NO2' => 'NO2',
    'CO2' => 'CO2',
    'NO' => 'NO',
    'N2' => 'N2',
    'C3H8' => 'C3H8'
];

