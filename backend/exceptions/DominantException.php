<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 11/21/16
 * Time: 10:31 PM
 */

namespace exceptions;

class DominantException extends \Exception{

    public function __array(){
        return [
            'code'=>$this->getCode(),
            'message'=>$this->getMessage(),
            'file'=>$this->getFile(),
            'line'=>$this->getLine(),
            'assert'=>$this->getTrace()
        ];
    }

    public function toJSON(){
        return json_encode($this->__array());
    }
}