<?php

namespace Dominant\Managers;

use Dominant\Account\AccountBase;
use Dominant\Elementary\Response;
use exceptions\accountException;
use exceptions\dominantException;


class AccountManager extends DominantManager
{
    /** @var null | AccountBase */
    protected $currentAccount = null;

    /**
     * @return AccountManager
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

    public function getAccount( int $accountID ): Response
    {
        $ret = new Response();
        try {
            $account = new AccountBase($accountID);

            return new Response($account);
        }
        catch (dominantException $e) {
            $ret->assertion = $e;
        }

        return $ret;
    }

    public function getAccountByMail( string $account_mail ): Response
    {
        return AccountBase::getAccount($account_mail);
    }

    public function getAllAccounts(): Response
    {
        return AccountBase::getAllAccounts();
    }

    public function registerAccount( array $params ): Response
    {
        return AccountBase::createAccount($params);
    }

    public function loginAccount( string $login, string $password ): Response
    {
        $tmpRes = AccountBase::getAccount($login);
        $ret = new Response();
        if ($tmpRes->status && $tmpRes->responseData !== null) {
            $currentAccount = $tmpRes->responseData;
            /** @var AccountBase $currentAccount */
            $autStatus = $currentAccount->verifyAuthentication($password);
            if ($currentAccount->getAccountStatus() !== AccountBase::ACCOUNT_ACTIVATED) {
                switch ($currentAccount->getAccountStatus()) {
                    case AccountBase::ACCOUNT_NOT_ACTIVATED:
                        $ret->assertion = new accountException("Account {$currentAccount->getAccountEmail()} is not activated");
                        break;
                    case AccountBase::ACCOUNT_BLOCKED:
                        $ret->assertion = new accountException("Account {$currentAccount->getAccountEmail()} is blocked");
                        break;
                    case AccountBase::ACCOUNT_DELETED:
                        $ret->assertion = new accountException("Account {$currentAccount->getAccountEmail()} is deleted");
                        break;
                    default:
                        $ret->assertion = new accountException("Account {$currentAccount->getAccountEmail()} have unknown status");
                        break;
                }
            } elseif ($autStatus->responseData) {
                $this->currentAccount = $currentAccount;

                return new Response($currentAccount);
            } else {
                $ret->assertion = new accountException("Invalid password entered for {$currentAccount->getAccountEmail()}");
            }
        }

        return $ret;
    }

    public function getCurrentAccount(): Response
    {
        return new Response($this->currentAccount);
    }

}