<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("init.php");


use exceptions\dominantException;
use Dominant\Elementary\UrlParser;
use Dominant\Managers\UrlManager;
try {
//    $url = new UrlParser();
//    $url->getAsArray();
    echo "<pre>";
    var_dump($_REQUEST);
    var_dump($_SERVER['REQUEST_URI']);
    echo "<hr>";
    UrlManager::getInstance()->someFunction();
}catch (dominantException $e){
    echo "Catched error<br>", $e->getMessage();
}