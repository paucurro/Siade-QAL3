<?php require_once('../Connections/qal3_db.php'); ?>
<?php

if (isset($_REQUEST['select']) && isset($_REQUEST['tabla'])) {

    /**
     * @param $respuesta
     */
    function pon_mensaje($respuesta)
    {
        echo json_encode($respuesta, JSON_NUMERIC_CHECK);
        exit;
    }

    $sql_sentence = 'SELECT ' . $_REQUEST['select'] . ' FROM ' . $_REQUEST['tabla'];

    if (isset($_REQUEST['where'])) {
        $sql_where = '';
        foreach ($_REQUEST['where'] as $where) {
            if (!isset($where['condicion'])) {
                pon_mensaje(['resp' => 'ERROR', 'data' => 'Falta condicion en apartado WHERE']);
            }
            if (!isset($where['campo'])) {
                pon_mensaje(['resp' => 'ERROR', 'data' => 'Falta campo en apartado WHERE']);
            }
            if (!isset($where['comparacion'])) {
                pon_mensaje(['resp' => 'ERROR', 'data' => 'Falta comparacion en apartado WHERE']);
            }
            if (!isset($where['valor'])) {
                pon_mensaje(['resp' => 'ERROR', 'data' => 'Falta valor en apartado WHERE']);
            }

            if ($sql_where != '') $sql_where .= $where['condicion'] . ' ';
            $sql_where .= $where['campo'] . ' ' . $where['comparacion'] . ' "' . $where['valor'] . '" ';

        }
        if ($sql_where != '') {
            $sql_sentence .= ' WHERE ' . $sql_where . ' ';
        }
    }

    if (isset($_REQUEST['order'])) {
        $sql_order = '';
        foreach ($_REQUEST['order'] as $order) {
            if (!isset($order['campo'])) {
                pon_mensaje(['resp' => 'ERROR', 'data' => 'Falta campo en apartado ORDER']);
            }
            if (!isset($order['orden'])) {
                pon_mensaje(['resp' => 'ERROR', 'data' => 'Falta orden en apartado ORDER']);
            }

            if ($sql_order != '') $sql_order .= ', ';
            $sql_order .= $order['campo'] . ' ' . $order['orden'] . ' ';

        }
        if ($sql_order != '') {
            $sql_sentence .= ' ORDER BY ' . $sql_order . ' ';
        }
    }

    if (isset($_REQUEST['limit'])) {
        $sql_sentence .= ' LIMIT ' . $_REQUEST['limit'];
    }

    mysqli_select_db($qal3_db, $database_qal3_db);
    $data = mysqli_query($qal3_db, $sql_sentence);

    if ($data == false) {
        $devuelto = mysqli_error($qal3_db);
    } else {
        $devuelto = mysqli_fetch_all($data, MYSQLI_ASSOC);
        mysqli_free_result($data);
    }
    $respuesta = ['resp' => 'OK', 'data' => $devuelto];
} else {
    $respuesta = ['resp' => 'ERROR', 'data' => 'Faltan parametros'];
}

echo json_encode($respuesta, JSON_NUMERIC_CHECK);
