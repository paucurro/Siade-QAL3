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
$qal3_db = mysqli_connect($hostname_qal3_db, $username_qal3_db, $password_qal3_db) or die ('sql error:' . mysqli_connect_error());

mysqli_set_charset($qal3_db, "utf8");
date_default_timezone_set('Europe/Berlin');

if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        global $qal3_db;
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

        $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($qal3_db, $theValue) : mysqli_escape_string($qal3_db, $theValue);

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
