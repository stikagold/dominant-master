<?php

namespace Dominant\Managers;

use Dominant\DTO\PackageConfig;
use Dominant\Elementary\Response;
use Dominant\Elementary\UrlParser;
use Dominant\Package\PackageBase;
use exceptions\notFountException;

class PackageManager extends DominantManager
{
    protected $configurationStorage = "global";
    protected $configurationArea = "packages";

    protected $packagesConfigs = [];

    /** @var null | PackageBase */
    protected $currentPackage = null;

    /**
     * @return PackageManager
     */
    public static function getInstance()
    {
        $type = get_called_class();
        if (!isset(self::$instances[ $type ])) {
            self::$instances[ $type ] = new $type();
            self::$instances[ $type ]->initial();
        }
        assert(self::$instances[ $type ] instanceof $type);

        return self::$instances[ $type ];
    }

    public function initial( array $params = null ): bool
    {
        $res = ConfigurationManager::getInstance()->getConfiguration($this->configurationArea,
                                                                     $this->configurationStorage);
        if($res->status){
            $this->packagesConfigs = $res->responseData;
            return true;
        }

        return false;
    }

    public function getPackage( UrlParser $thisContext ): Response
    {
        $ret = new Response();
        if (!empty($this->packagesConfigs[ $thisContext->getCurrentDomain() ])) {
            $packConf = new PackageConfig($this->packagesConfigs[ $thisContext->getCurrentDomain() ]);
            $package = new PackageBase($packConf, $thisContext);

            return new Response($package);
        }

        $ret->assertion = new notFountException("There is no defined package for domain {$thisContext->getCurrentDomain()}",
                                                notFountException::NOT_FOUND_SUBJECT);

        return $ret;
    }

}