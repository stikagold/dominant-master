<?php

namespace lib\elementary;

/**
 * Class CResponse
 * @package lib\elementary
 *
 * Description - This class we use everywhere to return result and know is returned result valid, or
 * maybe there are we have any exception, as it, every class and method return this object and everywhere
 * if in object responseStatus is false, it's mean, that there is some error and it's error we can find in
 * assertion, elsewhere any value in data is valid returned value
 */
class CResponse{
    const TYPE_OBJECT = "object";

    public $responseStatus = false;
    public $data           = null;
    public $assertion      = [];

    /**
     * CResponse constructor.
     * @param null $data
     */
    function __construct($data = null){
        $this->data = $data;
        if($data)$this->setResponseStatus(true);
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
     * @return null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param null $data
     */
    public function setData($data = null)
    {
        $this->setResponseStatus(1);
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

    /**
     * @return array
     */
    public function __array(){
        return [
            'status' => $this->getResponseStatus(),
            'data'   => $this->getData(),
            'assertion' => $this->getAssertion()
        ];
    }

    /**
     * @return string
     */
    public function toJSON(){
        return json_encode($this->__array());
    }

    /**
     * @return string
     */
    public function dataType(){
        $type = gettype($this->data);
        if($type === CResponse::TYPE_OBJECT)
            return get_class($this->data);
        return $type;
    }
}