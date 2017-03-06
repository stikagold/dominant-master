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

    /** Context of application */
    const CONTEXT_HTTPD = "httpd";
    const CONTEXT_PROCESS = "process";

    protected $context = self::CONTEXT_HTTPD;

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
        if (empty($_SERVER[ 'REQUEST_SCHEME' ])) {
            $this->context = self::CONTEXT_PROCESS;
        } else {
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
            else{
                $this->controllers[] = "home";
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
            } else {
                $this->controllers[] = "home";
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

    /**
     * @return string
     */
    public function getSourceURL(): string
    {
        return $this->sourceURL;
    }

    /**
     * @param string $sourceURL
     */
    public function setSourceURL( string $sourceURL )
    {
        $this->sourceURL = $sourceURL;
    }

    /**
     * @return array
     */
    public function getControllers(): array
    {
        return $this->controllers;
    }

    /**
     * @param array $controllers
     */
    public function setControllers( array $controllers )
    {
        $this->controllers = $controllers;
    }

    /**
     * @return array
     */
    public function getGetArguments(): array
    {
        return $this->getArguments;
    }

    /**
     * @param array $getArguments
     */
    public function setGetArguments( array $getArguments )
    {
        $this->getArguments = $getArguments;
    }

    /**
     * @return \Dominant\Elementary\ProtocolBase|null
     */
    public function getCurrentProtocol()
    {
        return $this->currentProtocol;
    }

    /**
     * @param \Dominant\Elementary\ProtocolBase|null $currentProtocol
     */
    public function setCurrentProtocol( $currentProtocol )
    {
        $this->currentProtocol = $currentProtocol;
    }

    public function getCurrentDomain(): string
    {
        return $this->currentDomain;
    }

    public function setCurrentDomain( string $currentDomain )
    {
        $this->currentDomain = $currentDomain;
    }

    public function getWebPath():string {
        return $this->currentProtocol->__toString().$this->currentDomain.'/';
    }

}