<?php
    require_once 'init.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    use \lib\elementary\CAbstractBricks;
    use \lib\elementary\CResponse;
//    $tmp = new lib\testclass();
//
//    use exceptions\unexception;
//    $exc = new unexception();
//    $exc->ShowYou();
try{
    echo "Before try AbstractBricks";

    $tmp = new CAbstractBricks();
    var_dump((array)$tmp->getStorage());
    echo $tmp->getStorage()->toJSON();
    echo "<hr>";
    echo $tmp->getStorage()->dataType();
    echo "<hr>";
    $res = new CResponse();
    $res->setData(new CResponse());
    echo $res->dataType();
}
catch (Exception $e){
    echo $e->getMessage();
}