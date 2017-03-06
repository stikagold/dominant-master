<?php

namespace Dominant\Managers;

use Dominant\DataSource\MySql;
use Dominant\DTO\DBSource;
use Dominant\Elementary\Response;
use exceptions\dataSourceException;

class DataHostManager extends DominantManager
{
    const TYPE_MYSQL      = "mysql";
    const TYPE_PSQL       = "psql";
    const TYPE_ORACLE_SQL = "osql";
    const TYPE_MS_SQL     = "mssql";

    protected $dataSources = [];

    /** @var MySql */
    public $dbLinks = [];

    /**
     * @return DataHostManager
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
        $conf = ConfigurationManager::getInstance()->getStorage("global");
        $res = $conf->getArea("databases");
        if ($res->status) {
            $this->dataSources = $res->responseData;

            return true;
        }

        throw $res->assertion;
    }

    public function getSource( string $sourceName ): Response
    {
        $ret = new Response();
        if (empty($this->dbLinks[ $sourceName ]) && empty($this->dataSources[ $sourceName ])) {
            $ret = new Response();
            $ret->assertion = new dataSourceException("The data source {$sourceName} does not exists",
                                                      dataSourceException::DATA_SOURCE_MISSING);

            return $ret;

        }

        if (empty($this->dbLinks[ $sourceName ])) {
            $tmp_dto = new DBSource($this->dataSources[ $sourceName ]);
            switch ($tmp_dto->getType()) {
                case self::TYPE_MYSQL:
                    $this->dbLinks[ $sourceName ] = new MySql($tmp_dto->getHost(),
                                                              $tmp_dto->getLogin(),
                                                              $tmp_dto->getPassword(),
                                                              $tmp_dto->getDb());
                    break;
                default:
                    $ret->assertion = new dataSourceException("Type {$tmp_dto->getType()} is not valid data source type",
                                                              dataSourceException::UNKNOWN_DATASOURCE_TYPE);

                    return $ret;

            }

        }

        return new Response($this->dbLinks[ $sourceName ]);

    }

}