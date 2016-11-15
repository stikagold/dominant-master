<?php

namespace lib\elementary;

class CResponse{
    public $responseStatus = false;
    public $data           = [];
    public $assertion      = [];

    /**
     * CResponse constructor.
     * @param array $data
     */
    function __construct(array $data = []){
        $this->data = $data;
    }

    /**
     * @return boolean
     */
    public function getResponseStatus()
    {
        return $this->responseStatus;
    }

    /**
     * @param boolean $responseStatus
     */
    public function setResponseStatus($responseStatus)
    {
        $this->responseStatus = $responseStatus;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array[] $data
     */
    public function setData(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getAssertion()
    {
        return $this->assertion;
    }

    /**
     * @param array[] $assertion
     */
    public function setAssertion(array $assertion = [])
    {
        $this->assertion = $assertion;
    }

    public function __array(){
        return [
            'status' => $this->getResponseStatus(),
            'data'   => $this->getData(),
            'assertion' => $this->getAssertion()
        ];
    }

    public function toJSON(){
        return json_encode($this->__array());
    }


}