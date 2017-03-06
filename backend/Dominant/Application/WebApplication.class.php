<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 2/25/17
 * Time: 9:27 PM
 */

namespace Dominant\Application;


class WebApplication extends Application
{

    /**
     * @return WebApplication
     */
    public static function getInstance()
    {
        $type = get_called_class();
        if (!isset(static::$instances[ $type ])) {
            static::$instances[ $type ] = new $type();
        }
        assert(static::$instances[ $type ] instanceof $type);

        return static::$instances[ $type ];
    }

}