<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

/** @var string $hostname_qal3_db */
/** @var mysqli $qal3_db */

$hostname_qal3_db = "localhost";
$database_qal3_db = "qal3_db";
$username_qal3_db = "qal3_user";
$password_qal3_db = "Siade2018";
// PHP >= 8.1 lanza excepciones en mysqli por defecto; mantenemos el comportamiento clasico (false + mysqli_error)
mysqli_report(MYSQLI_REPORT_OFF);
$qal3_db = mysqli_connect($hostname_qal3_db, $username_qal3_db, $password_qal3_db) or die ('sql error:' . mysqli_connect_error());

mysqli_set_charset($qal3_db, "utf8");
date_default_timezone_set('Europe/Berlin');

if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        global $qal3_db;
        $theValue = mysqli_real_escape_string($qal3_db, (string)$theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }
}
