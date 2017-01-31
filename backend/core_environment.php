<?php

define("LIB_DIR", __DIR__.'/');
define("ROOT_DIR", __DIR__.'/../');
define("EXCEPTIONS", LIB_DIR.'exceptions/');

use exceptions\dominantException;

/**
 * @param $className
 * @throws dominantException
 */
function dominantAutoload($className){
    $valid_url = LIB_DIR.str_replace('\\', '/', $className);

    if(file_exists($valid_url.".class.php")){
        require_once($valid_url.".class.php");
        return;
    }

    if(file_exists($valid_url.".interface.php")){
        require_once($valid_url.".interface.php");
        return;
    }

    throw new dominantException("Error: class {$className} not found", dominantException::UNFOUNDED_CLASS);
}

spl_autoload_register("dominantAutoload");