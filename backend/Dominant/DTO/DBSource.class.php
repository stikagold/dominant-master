<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 2/19/17
 * Time: 12:39 AM
 */

namespace Dominant\DTO;

use Dominant\Elementary\Dto;
use Dominant\Managers\DataHostManager;
use exceptions\DTOException;

class DBSource extends Dto
{
    protected $host     = "";
    protected $login    = "";
    protected $password = "";
    protected $db       = "";
    protected $type     = DataHostManager::TYPE_MYSQL;

    public function __construct( array $params )
    {
        if (empty($params[ 'host' ]) || empty($params[ 'login' ]) || empty($params[ 'db' ])) {
            throw new DTOException("Some important fields is missing for DTO element",
                                   DTOException::IMPORTANT_INDEX_MISSING);
        }

        $this->host = $params['host'];
        $this->login = $params['login'];
        if(!empty($params['password'])){
            $this->password = $params['password'];
        }
        $this->db = $params['db'];
        if(!empty($params['type'])){
            $this->type = $params['type'];
        }
    }

    public function getAsArray(): array
    {
        // TODO: Implement getAsArray() method.
    }

    /**
     * @return mixed|string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed|string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return mixed|string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed|string
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }


}