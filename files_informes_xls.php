<?php

if (isset($_REQUEST['del'])) {

    $file = $_REQUEST['del'];
    if (unlink($file)) {
        echo json_encode(['resp' => 'OK', 'data' => 'Archivo eliminado correctamente.']);
    } else {
        echo json_encode(['resp' => 'ERROR', 'data' => 'No se ha podido eliminar el archivo: [' . $file . ']']);
    }

} else {
    $url = $_REQUEST['url'];
    $files = [];
    if ($handle = opendir($url)) {

        chdir($url);
        array_multisort(array_map('filemtime', ($files = glob("*.xlsx"))), SORT_DESC, $files);
    }
    echo json_encode($files);
}
