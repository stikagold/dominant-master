<?php
    require_once 'init.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

//    $tmp = new lib\testclass();
//
//    use exceptions\unexception;
//    $exc = new unexception();
//    $exc->ShowYou();

    try{
        $ce = new AnyClass();
    }catch (AutoloaderException $e){
        echo $e->getMessage();
    }