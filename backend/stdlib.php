<?php

    define("BACKEND_ROOT", __DIR__);
    function dominant_autoload($class_name) {
        $valid_url = str_replace('\\', '/', $class_name) . '.php';
        if(file_exists(BACKEND_ROOT.'/'.$valid_url)){
            require_once($valid_url);
        }
        else{
            require_once 'AutoloaderException.php';
            throw new AutoloaderException("Cant find class {$class_name}", AutoloaderException::UNKNOWN_CLASS);
        }
    }
    
    spl_autoload_register("dominant_autoload", true);