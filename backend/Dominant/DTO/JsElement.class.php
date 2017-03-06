<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 2/27/17
 * Time: 10:44 PM
 */

namespace Dominant\DTO;


class JsElement extends FrontResourceBase
{
    public function __toString():string
    {
        return "<script src=\"{$this->contextPath}{$this->path}\"></script>";
    }
}