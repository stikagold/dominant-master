<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 2/18/17
 * Time: 11:52 PM
 */

namespace Dominant\DataSource;

use Dominant\Interfaces\JsonIntegrated;
use MysqliDb;

class MySql extends MysqliDb implements JsonIntegrated
{

    public function __clone()
    {
        $_mysqli = $this->_mysqli;
        $this->_mysqli = &$_mysqli;
        $host = $this->host;
        $this->host = &$host;
        $username = $this->username;
        $this->username = &$username;
        $password = $this->password;
        $this->password = &$password;
        $db = $this->db;
        $this->db = &$db;
    }

    public function getAsJSON(): string
    {
        return json_encode($this->getAsArray());
    }

    public function getAsArray(): array
    {
        return [
            'host'=>$this->host,
            'login'=>$this->username,
            'password'=>$this->password,
            'db'=>$this->db
        ];
    }


}