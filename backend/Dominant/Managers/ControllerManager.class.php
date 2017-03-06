<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 2/25/17
 * Time: 10:35 PM
 */

namespace Dominant\Managers;


use Controllers\caprice\homeController;
use Dominant\Controller\DominantController;
use Dominant\Elementary\Response;
use Dominant\Elementary\UrlParser;
use Dominant\Package\PackageBase;
use exceptions\notFountException;

class ControllerManager extends DominantController
{

    /** @var null | PackageBase */
    protected $context = null;
    /**
     * @return ControllerManager
     */
    public static function getInstance()
    {
        $type = get_called_class();
        if (!isset(static::$instances[ $type ])) {
            static::$instances[ $type ] = new $type();
            static::$instances[ $type ]->initial();
        }
        assert(static::$instances[ $type ] instanceof $type);

        return static::$instances[ $type ];
    }

    public function initial( array $params = null ): bool
    {
        return true;
    }

    public function getController( UrlParser $context ): Response
    {
        $packageRes = PackageManager::getInstance()->getPackage($context);
        if ($packageRes->status) {
            /** @var PackageBase $package */
            $package = $packageRes->responseData;
            $controllerNameRes = $package->getControllerParams($context->getCoreController()->responseData);
            if (!$controllerNameRes->status) {
                throw $controllerNameRes->assertion;
            }
            if (empty($controllerNameRes->responseData[ 'controller' ])) {
                throw new notFountException("Controller class name for controller {$context->getCoreController()->responseData} does not specified",
                                            notFountException::NOT_FOUND_SUBJECT);
            }

            $controllerClassName = $controllerNameRes->responseData[ 'controller' ].'Controller';
            $usableNamespace = $package->getControllersCurrentEnv();
            $instanceName = $usableNamespace.$controllerClassName;
            $instance = $instanceName::getInstance();
            $instance->initial([ 'package' => $package ]);

            return new Response($instance);
        }

        return $packageRes;
    }


}