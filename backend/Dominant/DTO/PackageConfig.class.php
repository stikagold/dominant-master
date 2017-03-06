<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 2/21/17
 * Time: 7:51 PM
 */

namespace Dominant\DTO;


use Dominant\Elementary\Response;
use Dominant\Interfaces\JsonIntegrated;
use exceptions\invalidStructureException;
use exceptions\notFountException;

class PackageConfig implements JsonIntegrated
{
    protected $path           = "";
    protected $controllerPath = "";
    protected $controllers    = [];
    protected $global         = [];
    protected $shortName      = "";

    public function __construct( array $params )
    {
        if (isset($params[ 'path' ]) && is_string($params[ 'path' ])) {
            $this->path = $params[ 'path' ];
        }
        if (isset($params[ 'controllers' ]) && is_array($params[ 'controllers' ])) {
            $this->controllers = $params[ 'controllers' ];
        }
        if (isset($params[ 'global' ]) && is_array($params[ 'global' ])) {
            $this->global = $params[ 'global' ];
        }
        if (isset($params[ 'controllerPath' ]) && is_string($params[ 'controllerPath' ])) {
            $this->controllerPath = $params[ 'controllerPath' ];
        }
        if (isset($params[ 'shortName' ]) && is_string($params[ 'shortName' ])) {
            $this->shortName = $params[ 'shortName' ];
        }

    }

    public function getAsJSON(): string
    {
        return json_encode($this->getAsArray());
    }

    public function getAsArray(): array
    {
        return [
            'path'        => $this->path,
            'controllers' => $this->controllers,
        ];
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFrontContext(): array
    {
        return $this->global;
    }

    public function getControllerPath(): string
    {
        return $this->controllerPath;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function getControllerParams( string $controllerShortname ): Response
    {
        $ret = new Response();
        if (!empty($this->controllers[ $controllerShortname ])) {
            return new Response($this->controllers[ $controllerShortname ]);
        }
        $ret->assertion = new notFountException("The controller {$controllerShortname} does not exists",
                                                notFountException::NOT_FOUND_SUBJECT);

        return $ret;
    }

}