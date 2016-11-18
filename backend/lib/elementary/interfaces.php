<?php

namespace lib\elementary;

/**
 * Interface IBricks
 *
 * @package lib\elementary
 */
interface IBricks{

    /**
     * @param array|null $args
     *
     * @return mixed
     */
    public function Initial(array $args=null);

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getBlock($key);

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function setBlock($key, $value);
}