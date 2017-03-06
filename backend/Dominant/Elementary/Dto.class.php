<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 2/19/17
 * Time: 12:34 AM
 */

namespace Dominant\Elementary;

use Dominant\Interfaces\JsonIntegrated;

abstract class Dto implements JsonIntegrated
{
    public function getAsJSON(): string
    {
        return json_encode($this->getAsArray());
    }

    abstract public function getAsArray(): array;

}