<?php

namespace lib\elementary;

class CGetBrick extends CAbstractBricks{

    public function __construct(array $args = null){
        if($args){
            $this->Initial($args);
        }
    }

    /**
     * @return CResponse
     */
    public function getAsURLPart(){
        $ret = "";
        foreach ($this->storage as $key=>$value){
            $ret.=$key.'='.$value.'&';
        }
        if($ret){
            $ret = mb_substr($ret, 0, -1);
        }
        return new CResponse($ret);
    }

    /**
     * @return string
     */
    public function __toString(){
        return $this->getAsURLPart()->getData();
    }
}