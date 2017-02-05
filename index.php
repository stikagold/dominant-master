<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("init.php");

use Dominant\Elementary\UrlParser;

try {
    echo "<pre>";
//    $parser = new UrlParser();
//    var_dump($parser->getAsArray());
    $caprice = [
        'protocol'=>\Dominant\Elementary\ProtocolBase::PROTOCOL_HTTP,
        'domain'=>'caprice.ru',
        'controllers'=>[
            'admin',
            'show_tasks'
        ],
        'arguments'=>[
            'gamer'=>100,
            'level'=>3
        ]
    ];
//    $parser->initialFromArray($caprice);
//    echo $parser, "<hr>";
//    echo $parser->getAsJSON();
    $urlManager = \Dominant\Managers\UrlManager::getInstance();
    $currentURL = $urlManager->getCurrentURL();
    var_dump($currentURL->getAsArray());
}catch (dominantException $e){
    echo "Catched error<br>", $e->getMessage();
}
