<?php

namespace Dominant\Elementary;
use Dominant\Interfaces\JsonIntegrated;
use exceptions\notImplementedException;

/**
 * Class Response
 * @package Dominant\Elementary
 */
class Response implements JsonIntegrated {
    public $status = false;

    /** @var mixed */
    public $responseData = [];

    public $assertion = null;

    function __construct( $result = DNULL )
    {
        if ($result !== DNULL) {
            $this->status = true;
            $this->responseData = $result;
        }
    }

    /**
     * @return string
     * @throws notImplementedException
     */
    public function getAsJSON(): string
    {
        return json_encode($this->getAsArray());
    }

    /**
     * @return array
     * @throws notImplementedException
     */
    public function getAsArray(): array
    {
        return [
            'status' => $this->status,
            'response_data' => $this->responseData,
            'assertion' => $this->assertion
        ];
    }

}