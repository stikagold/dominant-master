<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("init.php");

use Dominant\Elementary\UrlParser;

try {
    echo "<pre>";
    $url = new UrlParser();
    $controller = $url->getCoreController();
    if($controller->status){
        switch ($controller->responseData) {
            case "account":
                require_once(PACKAGES_DIR."index.php");
                break;
            default:
                echo "No defined anything";
                break;
        }
    }
    $db = new MysqliDb("localhost", "root", "Gizma85451605", "mykruto_ru");
    $admins = $db->get("adm_users");
    var_dump($admins);
//    var_dump($controller->getAsArray());
}catch (dominantException $e){
    echo "Catched error<br>", $e->getMessage();
}
