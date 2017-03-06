<?php

namespace Dominant\Managers;

use Dominant\Configuration\ConfigurationBase;
use Dominant\Elementary\Response;
use exceptions\configurationException;

class ConfigurationManager extends DominantManager
{
    /** @var ConfigurationBase[]
     *  [
     *      'storage name'=> Configuration object
     *  ]
     */
    protected $configurations = [];

    public function getStorage( string $storage ): ConfigurationBase
    {
        if (!empty($this->configurations[ $storage ])) {
            return $this->configurations[ $storage ];
        }

        return new ConfigurationBase([ 'storage' => $storage ]);
    }

    public function getConfiguration( string $config, string $storage ): Response
    {
        $res = $this->loadStorage($storage);
        if ($res->responseData) {
            return $this->configurations[ $storage ]->getConfig($config);
        }

        return $res;
    }

    protected function loadStorage( string $storageName ): Response
    {
        $ret = new Response();
        if (!empty($this->configurations[ $storageName ])) {
            return new Response(true);
        }

        try {
            $tmpConf = new ConfigurationBase([ 'storage' => $storageName ]);
            $this->configurations[ $storageName ] = $tmpConf;

            return new Response(true);
        }
        catch (configurationException $e) {
            $ret->assertion = $e;
        }

        return $ret;
    }

    /**
     * @return ConfigurationManager
     */
    public static function getInstance()
    {
        $type = get_called_class();
        if (!isset(self::$instances[ $type ])) {
            self::$instances[ $type ] = new $type();
        }
        assert(self::$instances[ $type ] instanceof $type);

        return self::$instances[ $type ];
    }

    public function initial( array $params = null ): bool
    {
        return true;
    }

}