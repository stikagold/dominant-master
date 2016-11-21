<?php
    require_once 'init.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    use lib\elementary\CGetBrick;

    $get = new CGetBrick(['p'=>125, 'lang'=>'am']);
    echo $get;