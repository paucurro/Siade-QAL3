<?php require_once('ls_usuario_session.php'); ?>
<?php
// *** Logout the current user.
$logoutGoTo = "index.php";

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['datos_usuario_qal3'])) {
    $_SESSION['datos_usuario_qal3']->row_usuarios = null;
}
unset($_SESSION['datos_usuario_qal3']);

session_destroy();

$parametros_cookies = session_get_cookie_params();
setcookie(session_name(), 0, 1, $parametros_cookies["path"]);

if ($logoutGoTo != "") {
    header("Location: $logoutGoTo");
    exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Untitled Document</title>
</head>

<body>
</body>
</html>