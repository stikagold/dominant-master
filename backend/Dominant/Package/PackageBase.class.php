<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 2/21/17
 * Time: 6:26 PM
 */

namespace Dominant\Package;


use Dominant\DTO\CssElement;
use Dominant\DTO\JsElement;
use Dominant\DTO\PackageConfig;
use Dominant\Elementary\Response;
use Dominant\Elementary\UrlParser;
use Dominant\Interfaces\JsonIntegrated;

class PackageBase implements JsonIntegrated
{
    const CSS_CONFIG_AREA     = "css";
    const CSS_CDN_CONFIG_AREA = "css_cdn";
    const JS_CONFIG_AREA      = "js";
    const JS_CDN_CONFIG_AREA  = "js_cdn";
    /** @var null | UrlParser */
    public $currentURL = null;
    /** @var null | PackageConfig */
    protected $packageParams = null;

    /**
     * @var string
     * It's path of html content for any controller, if there no specified content for current package and
     * controller, package class will set default path
     * ex. - packages/default/
     */
    protected $currentPath    = "";

    /**
     * @var string
     * It's path for controllers for current package, if package does not content any controller and will use default
     * controllers of system, in config property for this will miss, package class will set default path:
     * ex. - backend/Controllers/Dominant/
     */
    protected $controllerPath = "";

    protected $currentControllersEnv = "";

    protected $currentFrontContext = null;

    /** @var JsElement[] */
    protected $js_locals = [];

    /** @var CssElement[] */
    protected $css_locals = [];

    public function __construct( PackageConfig $packageConfig, UrlParser $currentURL )
    {
        $this->currentURL = $currentURL;
        $this->packageParams = $packageConfig;
        $currentPath = $this->packageParams->getPath();
        ( empty($currentPath) ) ? $this->currentPath = PACKAGES_DIR.'default/' :
            $this->currentPath = PACKAGES_DIR.$this->packageParams->getPath();
        $controllerPath = $this->packageParams->getControllerPath();
        if( empty($controllerPath) ) {
            $this->controllerPath = LIB_DIR.'Controllers/Dominant/';
            $this->currentControllersEnv = '\\Controllers\\Dominant\\';
        }
        else {
            $this->controllerPath = LIB_DIR.'Controllers/'.$controllerPath;
            $this->currentControllersEnv = '\\Controllers\\'.$this->packageParams->getShortName().'\\';
        }
        $globalFront = $this->packageParams->getFrontContext();
        if (!empty($globalFront)) {
            if (!empty($globalFront[ self::CSS_CONFIG_AREA ])) {
                foreach ($globalFront[ self::CSS_CONFIG_AREA ] as $css) {
                    $this->css_locals[] = new CssElement($css[ 'path' ], PUBLIC_RESOURCES_PATH
                                                                       .$currentURL->getCurrentDomain().'/global/',
                                                         $css[ 'type' ]);
                }
            }
            if (!empty($globalFront[ self::JS_CONFIG_AREA ])) {
                foreach ($globalFront[ self::JS_CONFIG_AREA ] as $js) {
                    $this->js_locals[] = new JsElement($js,
                                                       PUBLIC_RESOURCES_PATH.$currentURL->getCurrentDomain().'/global/');
                }
            }
        }
        //        var_dump($this->packageParams);
    }

    public function getCurrentPath(): string
    {
        return $this->currentPath;
    }

    public function getAsJSON(): string
    {
        // TODO: Implement getAsJSON() method.
    }

    public function getAsArray(): array
    {
        return [
            'params' => $this->packageParams->getAsArray(),
            'path'   => $this->currentPath,
        ];
    }

    public function getLocalCSS(): string
    {
        $ret = "";
        foreach ($this->css_locals as $css_local) {
            $ret .= $css_local->__toString();
        }

        return $ret;
    }

    public function getLocalJS(): string
    {
        $ret = "";
        foreach ($this->js_locals as $js_local) {
            $ret .= $js_local->__toString();
        }

        return $ret;
    }

    public function getPackagePath(): string
    {
        return $this->currentPath;
    }

    public function getPackageControllersPath(): string
    {
        return $this->controllerPath;
    }

    public function getControllersCurrentEnv():string{
        return $this->currentControllersEnv;
    }

    public function getControllerParams(string $controllerShortname):Response{
        return $this->packageParams->getControllerParams($controllerShortname);
    }

}