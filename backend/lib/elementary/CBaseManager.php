<?php
    /**
     * Created by PhpStorm.
     * User: rafik
     * Date: 11/24/16
     * Time: 1:31 PM
     */

    namespace lib\elementary;

    /**
     * Class CBaseManager
     *
     * @package lib\elementary
     */
    abstract class CBaseManager{
        /**
         * Singletone objects of concrete Managers
         *
         * @var array Manager
         */
        private static $instances;

        protected function __construct()
        {
        }

        private function __clone()
        {
        }

        /**
         * Returns singleton instance of Manager
         *
         * @param string $type
         * @return $type
         */
        protected static function &getInstance()
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