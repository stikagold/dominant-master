<?php

namespace Dominant\Elementary;

use Dominant\Interfaces\JsonIntegrated;
use exceptions\notImplementedException;
use Dominant\Elementary\ProtocolBase;

class UrlParser implements JsonIntegrated
{
    protected $sourceURL = "";
    protected $controllers = [];
    protected $getArguments = [];

    /** @var null|ProtocolBase $currentProtocol */
    protected $currentProtocol = null;

    protected $currentDomain = "";

    public function __construct(string $url="")
    {
    }

    private function parseURL()
    {

    }

    public function getAsJSON(): string
    {
        // TODO: Implement getAsJSON() method.
        throw new notImplementedException("This method is not implemented", notImplementedException::UNIMPLEMENTED_METHOD);
    }

    public function getAsArray(): array
    {
        // TODO: Implement getAsArray() method.
        throw new notImplementedException("This method is not implemented", notImplementedException::UNIMPLEMENTED_METHOD);
    }
}