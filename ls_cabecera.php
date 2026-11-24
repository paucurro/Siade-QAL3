<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="utf-8">
    <title> Siade Control de botellas </title>
    <meta name="description" content="">
    <meta name="author" content="Kepi Sistemas para Tirme">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- #CSS Links -->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.css<?php echo $version_app_link; ?>">
    <link rel="stylesheet" type="text/css" media="screen"
          href="css/font-awesome.min.css<?php echo $version_app_link; ?>">

    <!-- selectPicker component -->
    <link rel="stylesheet" type="text/css" media="screen"
          href="css/bootstrap-select.min.css<?php echo $version_app_link; ?>">

    <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
    <link rel="stylesheet" type="text/css" media="screen"
          href="css/smartadmin-production-plugins.min.css<?php echo $version_app_link; ?>">
    <link rel="stylesheet" type="text/css" media="screen"
          href="css/smartadmin-production.min.css<?php echo $version_app_link; ?>">
    <link rel="stylesheet" type="text/css" media="screen"
          href="css/smartadmin-skins.min.css<?php echo $version_app_link; ?>">

    <!-- SmartAdmin RTL Support -->
    <link rel="stylesheet" type="text/css" media="screen"
          href="css/smartadmin-rtl.min.css<?php echo $version_app_link; ?>">
    <link rel="stylesheet" type="text/css" media="screen"
          href="css/jquery-ui-timepicker-addon.min.css<?php echo $version_app_link; ?>">

    <!-- We recommend you use "your_style.css" to override SmartAdmin
         specific styles this will also ensure you retrain your customization with each SmartAdmin update. -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css<?php echo $version_app_link; ?>">

    <!-- #FAVICONS -->
    <link rel="shortcut icon" href="img/favicon/favicon1.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon/favicon1.ico" type="image/x-icon">

    <!-- #GOOGLE FONT -->
    <link rel="stylesheet" href="./css/fonts.googleapis.css?family=Open+Sans:400italic,700italic,300,400,700">

    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="css/jquery.fileupload.css">

    <!-- #APP SCREEN / ICONS -->
    <!-- Specifying a Webpage Icon for Web Clip
         Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
    <link rel="apple-touch-icon" href="img/splash/sptouch-icon-iphone.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/splash/touch-icon-ipad.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/splash/touch-icon-iphone-retina.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/splash/touch-icon-ipad-retina.png">

    <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Startup image for web apps -->
    <link rel="apple-touch-startup-image" href="img/splash/ipad-landscape.png"
          media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
    <link rel="apple-touch-startup-image" href="img/splash/ipad-portrait.png"
          media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
    <link rel="apple-touch-startup-image" href="img/splash/iphone.png" media="screen and (max-device-width: 320px)">

</head>

<!--

TABLE OF CONTENTS.

Use search to find needed section.

===================================================================

|  01. #CSS Links                |  all CSS links and file paths  |
|  02. #FAVICONS                 |  Favicon links and file paths  |
|  03. #GOOGLE FONT              |  Google font link              |
|  04. #APP SCREEN / ICONS       |  app icons, screen backdrops   |
|  05. #BODY                     |  body tag                      |
|  06. #HEADER                   |  header tag                    |
|  07. #PROJECTS                 |  project lists                 |
|  08. #TOGGLE LAYOUT BUTTONS    |  layout buttons and actions    |
|  09. #MOBILE                   |  mobile view dropdown          |
|  10. #SEARCH                   |  search field                  |
|  11. #NAVIGATION               |  left panel & navigation       |
|  12. #MAIN PANEL               |  main panel                    |
|  13. #MAIN CONTENT             |  content holder                |
|  14. #PAGE FOOTER              |  page footer                   |
|  15. #SHORTCUT AREA            |  dropdown shortcuts area       |
|  16. #PLUGINS                  |  all scripts and plugins       |

===================================================================

-->

<!-- #BODY -->
<!-- Possible Classes

    * 'smart-style-{SKIN#}'
    * 'smart-rtl'         - Switch theme mode to RTL
    * 'menu-on-top'       - Switch to top navigation (no DOM change required)
    * 'no-menu'			  - Hides the menu completely
    * 'hidden-menu'       - Hides the main menu but still accessable by hovering over left edge
    * 'fixed-header'      - Fixes the header
    * 'fixed-navigation'  - Fixes the main menu
    * 'fixed-ribbon'      - Fixes breadcrumb
    * 'fixed-page-footer' - Fixes footer
    * 'container'         - boxed layout mode (non-responsive: will not work with fixed-navigation & fixed-ribbon)
-->
<?php

$minified = '';
if (isset($_COOKIE['menu_minified'])) {
    $minified = 'minified';
}

$css_solo_contenido = 'fixed-ribbon fixed-header fixed-page-footer ' . $minified;
$hidden_solo_contenido = '';
if ($_SESSION['SoloContenido'] == true) {
    $css_solo_contenido = 'no-menu';
    $hidden_solo_contenido = 'hidden';
}
?>

<body class="<?php echo $css_solo_contenido; ?>">

<!-- #HEADER -->
<header id="header" class="<?php echo $hidden_solo_contenido; ?>">
    <div id="logo-group">

        <!-- PLACE YOUR LOGO HERE -->
        <span id="logo"> <img src="img/logo_tirme.png" alt="Siade Botellas"/> </span>
        <!-- END LOGO PLACEHOLDER -->

        <!-- Note: The activity badge color changes when clicked and resets the number to 0
             Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
        <span id="activity" class="activity-dropdown"> <i class="fa fa-user"></i> </span>

    </div>

    <!-- #PROJECTS: projects dropdown -->
    <div class="project-context hidden-xs">

        <span class="label">usuario:</span>
        <span class="project-selector dropdown-toggle"><?php echo $_SESSION['datos_usuario_qal3']->row_usuarios['Nombre']; ?></span>

        <!-- Suggestion: populate this list with fetch and push technique -->
        <ul class="dropdown-menu">
            <li></li>
        </ul>
        <!-- end dropdown-menu-->

    </div>
    <!-- end projects dropdown -->

    <!-- #TOGGLE LAYOUT BUTTONS -->
    <!-- pulled right: nav area -->
    <div class="pull-right">

        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
            <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i
                            class="fa fa-reorder"></i></a> </span>
        </div>
        <!-- end collapse menu -->

        <!-- logout button -->
        <div id="logout" class="btn-header transparent pull-right">
            <span> <a href="logout.php" title="Sign Out" data-action="userLogout"
                      data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i
                            class="fa fa-sign-out"></i></a> </span>
        </div>
        <!-- end logout button -->

        <!-- fullscreen button -->
        <div id="fullscreen" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i
                            class="fa fa-arrows-alt"></i></a> </span>
        </div>
        <!-- end fullscreen button -->

    </div>
    <!-- end pulled right: nav area -->

</header>
<!-- END HEADER -->

<!-- #NAVIGATION -->
<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">

    <!-- User info -->
    <div class="login-info">
        <span> </span>
    </div>
    <!-- end user info -->

    <nav>
        <!--
        NOTE: Notice the gaps after each icon usage <i></i>..
        Please note that these links work a bit different than
        traditional href="" links. See documentation for details.
        -->

        <ul>
            <li class="<?php echo pon_seleccionado($_SERVER['PHP_SELF'], ["botellas"], 'active', [], true) ?>">
                <a href="botellas.php"><i class="fa fa-lg fa-fw fa-flask" style="color: #ffe377;"></i> <span
                            class="menu-item-parent"> Botellas</span></a>
            </li>

            <li class="<?php echo pon_seleccionado($_SERVER['PHP_SELF'], ["analizadores", "qal", "verificaciones_"], 'active', [], true) ?>">
                <a href="analizadores.php"><i class="fa fa-lg fa-fw fa-flag-checkered" style="color: #6d8efa;"></i>
                    <span class="menu-item-parent"> Analizadores</span></a>
            </li>

            <?php /*
            <li class="<?php echo pon_seleccionado($_SERVER['PHP_SELF'], ["buscador"], 'active', [], true) ?>">
                <a href="buscador.php"><i class="fa fa-lg fa-fw fa-search" style="color: #b3e6b5;"></i> Buscador</a>
            </li>

            <li class="<?php echo pon_seleccionado($_SERVER['PHP_SELF'], ["parametros"], 'active', [], true); ?>">
                <a href="parametros.php"><i class="fa fa-lg fa-fw fa-gears" style="color: #cebfff;"></i> Parámetros</a>
            </li>
*/ ?>

        </ul>

    </nav>


    <span class="minifyme" data-action="minifyMenu">
        <i class="fa fa-arrow-circle-left hit"></i>
    </span>

</aside>
<!-- END NAVIGATION -->

