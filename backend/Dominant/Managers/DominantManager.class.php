<?php

namespace Dominant\Managers;

/**
 * Class DominantManager
 * @package Dominant\Managers
 */
abstract class DominantManager
{
    protected static $instances;

    protected function __construct()
    {
    }


    protected function __clone()
    {
    }

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        $type = get_called_class();
        if (!isset(self::$instances[ $type ])) {
            self::$instances[ $type ] = new $type();
        }
        assert(self::$instances[ $type ] instanceof $type);

        return self::$instances[ $type ];
    }

    abstract public function initial( array $params = null ): bool;

}