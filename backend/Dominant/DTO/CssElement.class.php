<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 2/27/17
 * Time: 8:18 PM
 */

namespace Dominant\DTO;


class CssElement extends FrontResourceBase
{
    const TYPE_STANDARD = "standard";
    const TYPE_MEDIA    = "media";

    protected $type = self::TYPE_STANDARD;

    public function __construct( string $path, string $contextPath, string $type=self::TYPE_STANDARD )
    {
        parent::__construct($path, $contextPath);
        $this->type = $type;
    }

    public function __toString():string
    {
        if($this->type === self::TYPE_MEDIA){
            return "<link href=".$this->contextPath.$this->path." rel=\"stylesheet\" media=\"screen\">\n";
        }
        return "<link href=".$this->contextPath.$this->path." rel=\"stylesheet\">\n";
    }

}