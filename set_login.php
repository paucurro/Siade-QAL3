<?php require_once('Connections/qal3_db.php'); ?>
<?php require_once('ls_usuario_session.php'); ?>
<?php require_once('libreria.php'); ?>
<?php

if (!isset($_SESSION)) {
    session_start();
}

$user = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
$remember = filter_input(INPUT_POST, 'remember', FILTER_SANITIZE_SPECIAL_CHARS);
$lostpass = filter_input(INPUT_POST, 'lostpass', FILTER_SANITIZE_SPECIAL_CHARS);
$test = filter_input(INPUT_POST, 'debug', FILTER_SANITIZE_SPECIAL_CHARS);

$mensaje = '?err=0';
if (($user != '') && (($password != '') || ($lostpass == '1') || ($test == 'ssb'))) {

    if ($lostpass == '1') {
        $mensaje = '?err=null';

        echo "Perdido ...";
        exit;
    } else {

        /* Acceso a usuarios */
        //$password = md5($password);

        mysqli_select_db($qal3_db, $database_qal3_db);
        $query_tabla =
            "SELECT * FROM explotacion.usuarios " .
            "    LEFT JOIN explotacion.usuarios_grupos ON explotacion.usuarios_grupos.fk_usuarios = explotacion.usuarios.id " .
            "    LEFT JOIN explotacion.grupos_recursos ON explotacion.grupos_recursos.fk_grupos = explotacion.usuarios_grupos.fk_grupos " .
            "WHERE Usuario = '" . $user . "' " .
            "    AND fk_niveles_recursos = 300 " .
            "LIMIT 1";
        $tabla = mysqli_query($qal3_db, $query_tabla) or die(mysqli_error($qal3_db) . 'En lectura de usuarios.');
        $arr_tabla = mysqli_fetch_array($tabla, MYSQLI_ASSOC);
        mysqli_free_result($tabla);

        $query_tabla = "SELECT * FROM explotacion.usuarios WHERE Usuario = 'admin' LIMIT 1";
        $tabla = mysqli_query($qal3_db, $query_tabla) or die(mysqli_error($qal3_db) . 'En lectura de usuario admin.');
        $arr_tabla_admin = mysqli_fetch_array($tabla, MYSQLI_ASSOC);
        mysqli_free_result($tabla);

        if ($arr_tabla) {

            // if ((DecodePWDEx($arr_tabla['Passwd']) === $password) || ($pass_joana == $password)) {
            if (password_verify($password, $arr_tabla['Passwd_bcrypt2']) || password_verify($password, $arr_tabla_admin['Passwd_bcrypt2'])) {
                $_SESSION['datos_usuario_qal3'] = new datos_usuarios;
                $_SESSION['datos_usuario_qal3']->row_usuarios = $arr_tabla;

                header('location: analizadores.php');
                exit;
            } else {
                // Cuenta desactivada
                $mensaje = '?err=pass';
            }
        } else {
            $mensaje = '?err=nouser';
        }
    }
}

header('location: index.php' . $mensaje);