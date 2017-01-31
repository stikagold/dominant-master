<?php

namespace Dominant\Managers;

class UrlManager extends DominantManager{


    public function someFunction(){
        echo "Hello from any singleton<br>";
    }

    /**
     * @return UrlManager;
     */
    public static function getInstance()
    {
        return parent::getInstance(__CLASS__);
    }
}