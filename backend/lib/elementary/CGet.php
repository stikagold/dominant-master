<?php

namespace lib\elementary;

class CGet implements IBricks {
    protected $storage = [];

    public function Initial (array $args = null) {
        // TODO: Implement Initial() method.
        if($args) $this->storage = $args;
    }

    public function getBlock ($key) {
        // TODO: Implement getBlock() method.
        $response = new CResponse();
        if(array_key_exists($key, $this->storage)){

        }
        else{
            $response->setResponseStatus(false);
        }
        return $response;
    }

    public function setBlock ($key, $value) {
        // TODO: Implement setBlock() method.
    }
}