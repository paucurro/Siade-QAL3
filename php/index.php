<?php
/*
 * jQuery File Upload Plugin PHP Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */

$op = (isset($_REQUEST['op'])) ? ['field_id_padre' => 'id_botellas'] : null;
// Los avisos de PHP (Deprecated/Warning) se imprimirían antes del JSON y romperían la respuesta
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', '0');
require('UploadHandler.php');
$upload_handler = new UploadHandler($op);
