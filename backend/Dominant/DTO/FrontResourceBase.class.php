<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 2/27/17
 * Time: 8:14 PM
 */

namespace Dominant\DTO;


abstract class FrontResourceBase
{
    protected $path        = "";
    protected $contextPath = "";

    public function __construct( string $path, string $contextPath )
    {
        $this->path = $path;
        $this->contextPath = $contextPath;
    }

    abstract public function __toString();

}