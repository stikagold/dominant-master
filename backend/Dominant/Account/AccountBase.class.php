<?php

namespace Dominant\Account;

use Dominant\DataSource\MySql;
use Dominant\Elementary\Response;
use Dominant\Managers\DataHostManager;
use exceptions\accountException;
use exceptions\dataSourceException;
use exceptions\dominantException;
use exceptions\invalidStructureException;
use exceptions\notFountException;

class AccountBase
{
    const ACCOUNT_NOT_ACTIVATED = 0;
    const ACCOUNT_ACTIVATED     = 1;
    const ACCOUNT_BLOCKED       = 2;
    const ACCOUNT_DELETED       = 3;

    const tbl_name = "baccounts";

    protected $account_id    = null;
    protected $account_email = "";
    /** @var string  - SHA256 encoded */
    protected $account_password = "";

    /** @var $account_type null | AccountType */
    protected $account_type   = null;
    protected $account_status = null;
    protected $properties     = [];

    public function __construct( int $account_id = null )
    {
        if ($account_id) {
            $res = DataHostManager::getInstance()->getSource("default");
            if ($res->status) {
                $db = $res->responseData;
                $accountType = new AccountType();
                $accountTypeTableName = AccountType::tbl_name;
                $accountBaseTableName = self::tbl_name;
                $db->join("{$accountTypeTableName} a_type", "a_type.type_id=a_user.account_type", "INNER");
                $db->where("a_user.account_id", $account_id);
                $res = $db->getOne("{$accountBaseTableName} a_user", null);
                if (!empty($res)) {
                    $accountType->initialFromArray([
                                                       'type_id'    => $res[ 'type_id' ],
                                                       'type_descr' => $res[ 'type_descr' ],
                                                       'properties' => json_decode($res[ 'properties' ], true),
                                                   ]);
                    $this->account_type = $accountType;
                    $this->account_id = $res[ 'account_id' ];
                    $this->account_email = $res[ 'account_email' ];
                    $this->account_password = $res[ 'account_password' ];
                    $this->account_status = $res[ 'account_status' ];
                    $this->properties = json_decode($res[ 'account_properties' ], true);

                } else {
                    throw new notFountException("Account with id: {$account_id} does not found",
                                                notFountException::NOT_FOUND_SUBJECT);
                }
            } else {
                throw $res->responseData;
            }
        }
    }

    public function initialFromArray( array $params ): Response
    {
        $ret = new Response();
        try {
            $this->account_type = new AccountType();
            $this->account_type->initialFromArray($params);
            if (!empty($params[ 'account_id' ]) && !empty($params[ 'account_email' ]) && !empty
                ($params[ 'account_password' ])
            ) {
                $this->account_id = $params[ 'account_id' ];
                $this->account_email = $params[ 'account_email' ];
                $this->account_password = $params[ 'account_password' ];
                $this->account_status = $params[ 'account_status' ];
                $this->properties = json_decode($params[ 'account_properties' ], true);

                return new Response(true);
            } else {
                throw new invalidStructureException("Invalid fields given for account object initialisation");
            }

        }
        catch (dominantException $e) {
            $ret->assertion = $e;
        }

        return $ret;

    }

    static public function getAccount( string $login ): Response
    {
        $ret = new Response();
        try {
            $typeTableName = AccountType::tbl_name;
            $accountTableName = static::tbl_name;
            $res = DataHostManager::getInstance()->getSource("default");
            if ($res->status) {
                $db = $res->responseData;
                $db->join("{$typeTableName} a_type", "a_type.type_id=a_user.account_type", "INNER");
                $db->where("a_user.account_email", $login);
                $result = $db->getOne("{$accountTableName} a_user", null);
                if (empty($result)) {
                    return new Response(null);
                } else {
                    $account = new AccountBase();
                    $creatingResult = $account->initialFromArray($result);
                    if (!$creatingResult->status) {
                        throw $creatingResult->assertion;
                    }
                    if ($creatingResult->responseData) {
                        return new Response($account);
                    }
                }
            }
        }
        catch (\Exception $e) {
            $ret->assertion = $e;
        }

        return $ret;
    }

    static public function getAllAccounts(): Response
    {
        $ret = new Response();
        try {
            $typeTableName = AccountType::tbl_name;
            $accountTableName = static::tbl_name;
            $res = DataHostManager::getInstance()->getSource("default");
            if ($res->status) {
                $db = $res->responseData;
                $db->join("{$typeTableName} a_type", "a_type.type_id=a_user.account_type", "INNER");
                $result = $db->get("{$accountTableName} a_user", null);
                if (empty($result)) {
                    return new Response([]);
                } else {
                    $returnAccounts = [];
                    foreach ($result as $values) {
                        $account = new AccountBase();
                        $creatingResult = $account->initialFromArray($values);
                        if ($creatingResult->responseData) {
                            $returnAccounts[ $account->getAccountId() ] = $account;
                        }

                    }

                    return new Response($returnAccounts);
                }
            }
        }
        catch (\Exception $e) {
            $ret->assertion = $e;
        }

        return $ret;

    }

    static public function createAccount( array $params ): Response
    {
        $ret = new Response();
        $params = static::validateParams($params);
        if (!empty($params)) {
            $existsAccount = static::getAccount($params[ 'account_email' ]);
            if ($existsAccount->responseData === null) {
                $params[ 'account_properties' ] = json_encode($params[ 'account_properties' ]);
                $params[ 'account_password' ] = hash("sha256", $params[ 'account_password' ]);
                $res = DataHostManager::getInstance()->getSource("default");
                if ($res->status) {
                    $db = $res->responseData;
                    /** @var MySql $db */
                    if ($db->insert(static::tbl_name, $params)) {
                        $retAccount = new AccountBase();
                        $retAccount->initialFromArray($params);
                        $retAccount->setAccountID($db->getInsertId());

                        return new Response($retAccount);
                    } else {
                        $ret->assertion = new dataSourceException("Insert of new account failed. MySql error",
                                                                  dataSourceException::INVALID_INSERTION);
                    }
                }
                $ret->assertion = $res->assertion;
            } else {
                $ret->assertion = new accountException("The account {$params['account_email']} is exists",
                                                       accountException::ACCOUNT_EXISTS);
            }
        }

        return $ret;

    }

    static public function validateParams( array $params ): array
    {
        if (!empty($params[ 'account_email' ]) && !empty($params[ 'account_password' ]) && isset($params[ 'account_type' ])
            && is_numeric($params[ 'account_type' ]) && isset($params[ 'account_properties' ]) && is_array($params[ 'account_properties' ])
        ) {
            $acc = [
                'account_email'      => $params[ 'account_email' ],
                'account_password'   => $params[ 'account_password' ],
                'account_type'       => $params[ 'account_type' ],
                'account_properties' => $params[ 'account_properties' ],
            ];

            if (isset($params[ 'account_status' ]) && is_numeric($params[ 'account_status' ])) {
                $acc[ 'account_status' ] = $params[ 'account_status' ];
            }

            return $acc;
        }

        return [];
    }

    public function verifyAuthentication( string $password ): Response
    {
        $sha256 = hash("sha256", $password);
        if ($this->account_password === $sha256) {
            return new Response(true);
        }

        return new Response(false);
    }

    /**
     * @return null
     */
    public function getAccountId()
    {
        return $this->account_id;
    }

    /**
     * @return string
     */
    public function getAccountEmail(): string
    {
        return $this->account_email;
    }

    /**
     * @param string $account_email
     */
    public function setAccountEmail( string $account_email )
    {
        $this->account_email = $account_email;
    }

    /**
     * @return string
     */
    public function getAccountPassword(): string
    {
        return $this->account_password;
    }

    /**
     * @param string $account_password
     */
    public function setAccountPassword( string $account_password )
    {
        $this->account_password = hash("sha256", $account_password);
    }

    /**
     * @return \Dominant\Account\AccountType|null
     */
    public function getAccountType()
    {
        return $this->account_type;
    }

    /**
     * @param \Dominant\Account\AccountType|null $account_type
     */
    public function setAccountType( $account_type )
    {
        $this->account_type = $account_type;
    }

    /**
     * @return null
     */
    public function getAccountStatus()
    {
        return $this->account_status;
    }

    /**
     * @param null $account_status
     */
    public function setAccountStatus( $account_status )
    {
        $this->account_status = $account_status;
    }

    /**
     * @return array|mixed
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array|mixed $properties
     */
    public function setProperties( $properties )
    {
        $this->properties = $properties;
    }

    /**
     * @param $accountID
     */
    public function setAccountID( $accountID )
    {
        $this->account_id = $accountID;
    }

}