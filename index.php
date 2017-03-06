<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once( "init.php" );

try {
    $url = new \Dominant\Elementary\UrlParser();
    $controllerManager = \Dominant\Managers\ControllerManager::getInstance();
    $controllerRes = $controllerManager->getController($url);
    if (!$controllerRes->status) {
        throw $controllerRes->assertion;
    }
    /** @var \Controllers\caprice\homeController $currentController */
    $currentController = $controllerRes->responseData;
    ?>
    <!DOCTYPE html>
    <!--[if IE 8]>
    <html class="ie8" lang="en"><![endif]-->
    <!--[if IE 9]>
    <html class="ie9" lang="en"><![endif]-->
    <!--[if !IE]><!-->
    <html lang="en">
    <!--<![endif]-->
    <!-- start: HEAD -->
    <head>
        <?php
        $currentController->loadLocalCSS();
        ?>
    </head>
    <body>
    <div id="app">
    <?php
    $currentController->loadPage("header");
    $currentController->loadPage("content");
    $currentController->loadPage("footer");
    ?>
    </div>
    <?php
    $currentController->loadLocalJS();
    ?>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            Index.init();
        });
    </script>

    </body>

    </html>
    <?php
}
catch (\exceptions\dominantException $e) {
    echo "<pre>";
    echo "Catched error<br>", $e->getMessage();
    var_dump($e);
}
