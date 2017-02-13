<?php

namespace Dominant\Elementary;

use Dominant\Interfaces\JsonIntegrated;
use exceptions\invalidArgumentException;
use exceptions\notFountException;
use exceptions\notImplementedException;

class UrlParser implements JsonIntegrated
{
    protected $sourceURL    = "";
    protected $controllers  = [];
    protected $getArguments = [];

    /** @var null|ProtocolBase $currentProtocol */
    protected $currentProtocol = null;

    protected $currentDomain = "";

    public function __construct( string $url = "" )
    {
        if (empty($url)) {
            $this->InitialYourself();
        } else {
            throw new notImplementedException("This logic line does not ready to realise",
                                              notImplementedException::UNIMPLEMENTED_LOGIC);
        }
    }

    private function InitialYourself()
    {
        $this->currentProtocol = new ProtocolBase($_SERVER[ 'REQUEST_SCHEME' ]);
        $this->currentDomain = $_SERVER[ 'SERVER_NAME' ];
        $details = $_REQUEST;
        if (!empty($details)) {
            if (!empty($details[ 'query' ])) {
                $this->controllers = explode('/', $details[ 'query' ]);
                unset($details[ 'query' ]);
            }
            if (!empty($details)) {
                $this->getArguments = $details;
            }
        }
    }

    public function getAsJSON(): string
    {
        return json_encode($this->getAsArray());
    }

    public function getAsArray(): array
    {
        return [
            "protocol"    => $this->currentProtocol->getProtocol(),
            "domain"      => $this->currentDomain,
            "controllers" => $this->controllers,
            "arguments"   => $this->getArguments,
        ];
    }

    public function __toString()
    {
        $ret = "";
        $ret .= $this->currentProtocol->__toString();
        $ret .= $this->currentDomain.'/';
        $ret .= implode('/', $this->controllers);
        $ret .= "?";
        foreach ($this->getArguments as $command => $value) {
            $ret .= $command."=".$value."&";
        }
        $ret = mb_substr($ret, 0, -1);

        return $ret;
    }

    /**
     * @param array $params
     *
     * @return bool
     * @throws \exceptions\invalidArgumentException
     */
    public function initialFromArray( array $params ): bool
    {
        if (!empty($params)) {

            if (!empty($params[ 'protocol' ]) && is_string($params[ 'protocol' ])) {
                if (!$this->currentProtocol->setProtocol($params[ 'protocol' ])) {
                    throw new invalidArgumentException("Unknown protocol {$params['protocol']}");
                }
            }

            if (!empty($params[ 'domain' ]) && is_string($params[ 'domain' ])) {
                $this->currentDomain = $params[ 'domain' ];
            } else {
                throw new invalidArgumentException("Missing argument domain name");
            }

            if (!empty($params[ 'controllers' ]) && is_array($params[ 'controllers' ])) {
                $this->controllers = $params[ 'controllers' ];
            }

            if (!empty($params[ 'arguments' ]) && is_array($params[ 'arguments' ])) {
                $this->getArguments = $params[ 'arguments' ];
            }

            return true;

        }

        return false;
    }

    /**
     * @return \Dominant\Elementary\Response
     */
    public function getCoreController(): Response
    {
        $return = new Response();
        try {
            if (empty($this->controllers)) {
                throw new notFountException("No defined base controller");
            } else {
                $return->responseData = $this->controllers[ 0 ];
                $return->status = true;
            }

        }
        catch (notFountException $e) {
            $return->assertion = $e;
        }

        return $return;
    }

}