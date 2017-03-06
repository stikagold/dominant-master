<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 2/25/17
 * Time: 9:23 PM
 */

namespace Dominant\Controller;


use Dominant\Managers\DominantManager;

class DominantController extends DominantManager
{
    /** @var null | PackageBase */
    protected $currentPackage        = null;
    protected $currentControllerName = "";

    public function loadPage( string $pageName )
    {
        $pagePath = $this->getCurrentPackagePath().$this->currentControllerName.'/';
        $pageName .= '.php';
        if (file_exists($pagePath.$pageName)) {
            require_once( $pagePath.$pageName );
        } else {
            $pagePath = PACKAGES_DIR.'default/'.$this->currentControllerName.'/';
            if (file_exists($pagePath.$pageName)) {
                require_once( $pagePath.$pageName );
            } else {
                throw new notFountException("The header file does not find in any possible area",
                                            notFountException::NOT_FOUND_AREA);
            }
        }

    }


    public function initial( array $params = null ): bool
    {
        if (!empty($params[ 'package' ])) {
            $this->currentPackage = $params[ 'package' ];
            $contRes = $this->currentPackage->currentURL->getCoreController();
            if ($contRes->status) {
                $this->currentControllerName = $contRes->responseData;
            } else {
                throw $contRes->assertion;
            }

            //            var_dump($this->currentPackage);
            return true;
        }

        return true;
    }


    public function getCurrentPackagePath(): string
    {
        return $this->currentPackage->getCurrentPath();
    }

    public function getCurrentControllersPath(): string
    {
        return $this->currentPackage->getPackageControllersPath();
    }

    public function loadLocalCSS()
    {
        echo $this->currentPackage->getLocalCSS();
    }

    public function loadLocalJS()
    {
        echo $this->currentPackage->getLocalJS();
    }

    /**
     * @return DominantController
     */
    public static function getInstance()
    {
        $type = get_called_class();
        if (!isset(static::$instances[ $type ])) {
            static::$instances[ $type ] = new $type();
        }
        assert(static::$instances[ $type ] instanceof $type);

        return static::$instances[ $type ];
    }


}