<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 3/1/17
 * Time: 3:04 PM
 */

namespace Controllers\caprice;


use Dominant\Controller\DominantController;

class registerController extends DominantController
{


    /**
     * @return registerController
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