<?php

namespace Dominant\Managers;

abstract class DominantManager{
    private static $instances;

    protected function __construct()
    {
    }


    protected function __clone()
    {
    }

    /**
     * @return mixed
     */
    protected static function getInstance()
    {
        $type = get_called_class();
        if(!isset(self::$instances[$type]))
        {
            self::$instances[$type] = new $type();
        }
        assert(self::$instances[$type] instanceof $type);
        return self::$instances[$type];
    }

}