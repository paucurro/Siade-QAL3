<?php
require_once('ls_usuario_session.php');
require_once('lenguaje.php');

if (!isset($_SESSION)) {
    session_start();
}

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
    // For security, start by assuming the visitor is NOT authorized.
    $isValid = False;

    // When a visitor has logged into this site, the Session variable MM_Username set equal to their username.
    // Therefore, we know that a user is NOT logged in if that Session variable is blank.
    if (!empty($UserName)) {
        // Besides being logged in, you may restrict access to only certain users based on an ID established when they login.
        // Parse the strings into arrays.
        $arrUsers = Explode(",", $strUsers);
        $arrGroups = Explode(",", $strGroups);
        if (in_array($UserName, $arrUsers)) {
            $isValid = true;
        }
        // Or, you may restrict access to only certain users based on their username.
        if (in_array($UserGroup, $arrGroups)) {
            $isValid = true;
        }
        if (($strUsers == "") && true) {
            $isValid = true;
        }
    }
    return $isValid;
}

$MM_authorizedUsers = '';
$url = '';

require_once('Connections/qal3_db.php');

$usuario_seleccionado = null;

if (isset($_SESSION['datos_usuario_qal3'])) {

    if ($_SESSION['datos_usuario_qal3']->row_usuarios['id'] == 1) {
        if (isset($_REQUEST['user'])) $usuario_seleccionado = $_REQUEST['user'];
    } else {
        $usuario_seleccionado = $_SESSION['datos_usuario_qal3']->row_usuarios['id'];
    }

    if ($_SESSION['datos_usuario_qal3']->row_usuarios != null) {

        if (isset($_SESSION['datos_usuario_qal3']->row_usuarios['Usuario']) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['datos_usuario_qal3']->row_usuarios['Usuario'], $_SESSION['datos_usuario_qal3']->row_usuarios['id']))) {

            //coge_instalaciones_usuario($usuario_seleccionado);

        } else {
            $url = 'location: logout.php';
        }

    }
} else {
    if ($_SERVER['PHP_SELF'] != '/set_login.php') {
        $url = 'location: logout.php?err_check';
    }
}

if ($url != '') {
    header($url);
    exit;
}

