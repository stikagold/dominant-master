<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 2/14/17
 * Time: 1:31 PM
 */

namespace Dominant\Account;

use Dominant\Elementary\Response;
use Dominant\Managers\DataHostManager;
use exceptions\invalidStructureException;

class AccountType
{
    const tbl_name = "account_types";

    protected $id         = null;
    protected $desc       = "";
    protected $properties = [];

    public function __construct( int $type_id = null )
    {
        if ($type_id) {
            $res = DataHostManager::getInstance()->getSource("default");
            if ($res->status) {
                $db = $res->responseData;
                $db->where('type_id', $type_id);
                $tmp = $db->getOne(static::tbl_name);
                if (empty($tmp)) {
                    $this->id = $tmp[ 'type_id' ];
                    $this->desc = $tmp[ 'type_descr' ];
                    $this->properties = json_decode($tmp[ 'properties' ], true);
                }
            } else {
                throw $res->assertion;
            }
        }
    }

    public function createType( string $desc, array $properties ): int
    {
        $res = DataHostManager::getInstance()->getSource("default");
        if ($res->status) {
            $insertData[ 'type_descr' ] = $desc;
            $insertData[ 'properties' ] = json_encode($properties);
            $db = $res->responseData;
            if ($db->insert($this->tbl_name, $insertData)) {
                $this->id = $db->getInsertId();
                $this->desc = $desc;
                $this->properties = $properties;
            }

            return new Response($this->id);
        }

        return $res;
    }

    public function initialFromArray( array $params )
    {
        if (!isset($params[ 'type_id' ]) || !is_numeric($params[ 'type_id' ])) {
            throw new invalidStructureException("ID woes not entered for account type");
        }
        if (!isset($params[ 'type_descr' ])) {
            throw new invalidStructureException("Description woes not entered for account type");
        }
        $this->id = $params[ 'type_id' ];
        $this->desc = $params[ 'type_descr' ];
        if (isset($params[ 'properties' ]) && is_array($params[ 'properties' ])) {
            $this->properties = $params[ 'properties' ];
        } else {
            $this->properties = [];
        }
    }

    public function getDescription(): string
    {
        return $this->desc;
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getTableName(): string
    {
        return $this->tbl_name;
    }

}