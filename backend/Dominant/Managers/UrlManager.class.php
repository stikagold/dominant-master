<?php

namespace Dominant\Managers;
use Dominant\Elementary\UrlParser;

/**
 * Class UrlManager
 * @package Dominant\Managers
 */
class UrlManager extends DominantManager
{
    /** @var null | UrlParser */
    private $currentURL = null;

    /**
     * @return \Dominant\Elementary\UrlParser
     */
    public function getCurrentURL(): UrlParser
    {
        return new UrlParser();
    }

    /**
     * @return UrlManager;
     */
    public static function getInstance()
    {
        return parent::getInstance(__CLASS__);
    }

    /**
     * This function doing first step initialisation of manager,like settings, environment and etc
     *
     * @param array|null $params
     *
     * @return bool
     */
    public function initial( array $params = null ): bool
    {
        $this->currentURL = new UrlParser();

        return true;
    }
}