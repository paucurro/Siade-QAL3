<?php
/**
 * Esta versión se usa para que los navegadores borren el cache y vuelvan a cargar los JS si los hemos cambiado.
 * Debe incluirse dentro de todos los archivos donde esten los js
 */

$version_app = '1.16';
$version_app_link = '?' . $version_app;
$version_app_add_link = '&' . $version_app;
$version_app_fecha = '?' . round((time() / 86400));

