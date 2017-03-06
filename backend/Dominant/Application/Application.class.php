<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 2/25/17
 * Time: 9:26 PM
 */

namespace Dominant\Application;


use Dominant\Controller\DominantController;
use Dominant\Elementary\UrlParser;
use Dominant\Managers\ConfigurationManager;
use Dominant\Managers\DominantManager;
use Dominant\Managers\PackageManager;
use Dominant\Package\PackageBase;

class Application extends DominantManager
{
    /** @var null | UrlParser */
    protected $currentURL = null;

    /** @var null | ConfigurationManager */
    protected $configurationRouter = null;

    /** @var null | PackageManager */
    protected $packageManager = null;

    /** @var null | PackageBase */
    protected $currentPackage = null;

    /** @var null | DominantController */
    protected $currentController = null;

    public function initial( array $params = null ): bool
    {
        $this->currentURL = new UrlParser();
        $coreControllerRes = $this->currentURL->getCoreController();
        if($coreControllerRes->status){

        }

        $this->configurationRouter = ConfigurationManager::getInstance();
        $this->packageManager = PackageManager::getInstance();
        $this->currentPackage = $this->packageManager->getPackage($this->currentURL);
        if ($this->currentPackage->status) {
            $this->currentPackage = $this->currentPackage->responseData;
        } else {
            throw $this->currentPackage->assertion;
        }

        return true;
    }

    /**
     * @return Application
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

    public function getAsArray():array {
        return [
            'url'     => $this->currentURL->getAsArray(),
            'package' => $this->currentPackage->getAsArray(),
        ];
    }

}