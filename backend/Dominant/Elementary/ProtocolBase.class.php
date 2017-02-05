<?php

namespace Dominant\Elementary;

/**
 * Class ProtocolBase
 * @package Dominant\Elementary
 */
class ProtocolBase
{
    const PROTOCOL_HTTP  = "http";
    const PROTOCOL_HTTPS = "https";
    const PROTOCOL_TCP   = "tcp";
    const PROTOCOL_UDP   = "udp";
    const PROTOCOL_FTP   = "ftp";
    const PROTOCOL_SSH   = "ssh";
    const PROTOCOL_FILE  = "file";

    public $currentProtocol = ProtocolBase::PROTOCOL_HTTP;

    public function __construct( $currentProtocol = ProtocolBase::PROTOCOL_HTTP )
    {
        if ($this->validateThis($currentProtocol)) {
            $this->currentProtocol = $currentProtocol;
        }
    }

    /**
     * @param $protocol
     *
     * @return bool
     */
    public function validateThis( $protocol ): bool
    {
        if ($protocol === self::PROTOCOL_HTTP || $protocol === self::PROTOCOL_HTTPS ||
            $protocol === self::PROTOCOL_TCP || $protocol === self::PROTOCOL_UDP || $protocol === self::PROTOCOL_SSH ||
            $protocol === self::PROTOCOL_FTP || $protocol === self::PROTOCOL_FILE
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param string $protocol
     *
     * @return bool
     */
    public function setProtocol( string $protocol ): bool
    {
        if ($this->validateThis($protocol)) {
            $this->currentProtocol = $protocol;

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->currentProtocol;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->currentProtocol."://";
    }
}
