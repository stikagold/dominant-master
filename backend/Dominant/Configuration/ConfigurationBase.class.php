<?php

namespace Dominant\Configuration;

use Dominant\Elementary\Response;
use exceptions\configurationException;
use exceptions\notFountException;

class ConfigurationBase
{

    protected $configURL = "";

    protected $configuration = [];

    protected $isInitialised = false;

    public function __construct( array $params = null )
    {
        if ($params && is_array($params)) {
            if (!empty($params[ 'storage' ]) && is_string($params[ 'storage' ])) {
                $this->configURL = CONFIG_DIR.$params[ 'storage' ].'.php';
                $initialise = $this->loadConfiguration();
                if (!$initialise->status) {
                    //There is error at configuration initialisation, such the missing file
                    throw $initialise->assertion;
                }
                $this->isInitialised = true;
            }
        }
    }

    /**
     * @return \Dominant\Elementary\Response
     */
    final public function loadConfiguration(): Response
    {
        if (file_exists($this->configURL)) {
            $this->configuration = require_once( $this->configURL );

            return new Response(true);

        }
        $return = new Response();
        $return->assertion = new configurationException("Does not valid URL for configuration: {$this->configURL}",
                                                        configurationException::CONFIGURATION_FILE_MISSING);

        return $return;
    }

    final public function uploadConfiguration(): Response
    {
        $return = new Response();
        if ($this->isInitialised) {
            $contents = var_export($this->configuration, true);
            if (file_put_contents($this->configURL, "<?php\n return {$contents};\n")) {
                return new Response(true);
            } else {
                $return->assertion = new configurationException("Configuration file missing or there is no permission. URL:{$this->configURL}",
                                                                configurationException::CONFIGURATION_PERMISSION_DENY);
            }

        } else {
            $return->assertion = new configurationException("Try upload not initialised configuration",
                                                            configurationException::CONFIGURATION_UNSUPPORTED_ACTION);
        }

        return $return;
    }

    static public function createStorage( string $storageName ): Response
    {
        $ret = new Response();
        $url = CONFIG_DIR.$storageName.'.php';
        $fileHandle = fopen($url, "a+");
        if ($fileHandle) {
            $string = "<?php\n return ".var_export([], true).";\n ";
            if (fwrite($fileHandle, $string)) {
                fclose($fileHandle);

                return new Response(true);
            } else {
                $ret->assertion = new configurationException("There is an error at writing content of storage",
                                                             configurationException::CONFIGURATION_PERMISSION_DENY);
            }
        } else {
            $ret->assertion = new configurationException("Can not create storage file on {$url}",
                                                         configurationException::CONFIGURATION_PERMISSION_DENY);
        }

        return $ret;
    }

    public function addArea( string $areaName, $content ): Response
    {
        $ret = new Response();
        if ($this->isInitialised) {
            $this->configuration[ $areaName ] = $content;

            return $this->uploadConfiguration();
        }
        $ret->assertion = new configurationException("Not initialised and linked storage",
                                                     configurationException::CONFIGURATION_UNSUPPORTED_ACTION);

        return $ret;
    }

    public function getArea( string $areaName ): Response
    {
        $ret = new Response();
        if ($this->isInitialised) {
            if (!empty($this->configuration[ $areaName ])) {
                return new Response($this->configuration[ $areaName ]);
            }
            $ret->assertion = new configurationException("Area {$areaName} does not exists",
                                                         configurationException::CONFIGURATION_MISSING_AREA);
        }
        $ret->assertion = new configurationException("Not initialised and linked storage",
                                                     configurationException::CONFIGURATION_UNSUPPORTED_ACTION);

        return $ret;
    }

    public function exportMigration( string $storageName )
    {
        //TODO realise migration
    }

    final protected function recursiveSearch( array $list, array $step )
    {
        if (!empty($step)) {
            $curStep = current($step);
            unset($step[ key($step) ]);
            if (isset($list[ $curStep ])) {
                if (empty($step)) {
                    return $list[ $curStep ];
                } else {
                    if (is_array($list[ $curStep ])) {
                        return $this->recursiveSearch($list[ $curStep ], $step);
                    } else {
                        return DNULL;
                    }
                }
            } else {
                return DNULL;
            }
        } else {
            return DNULL;
        }
    }

    public function getConfig( string $configPath ): Response
    {
        $path = explode(':', $configPath);
        $res = $this->recursiveSearch($this->configuration, $path);
        if ($res !== DNULL) {
            return new Response($res);
        }
        $ret = new Response();
        $ret->responseData = DNULL;
        $ret->assertion = new notFountException("The path does not exists: {$configPath}",
                                                notFountException::NOT_FOUND_SUBJECT);

        return $ret;
    }
}
