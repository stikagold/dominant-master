<?php

/** Base directories */
define("LIB_DIR", __DIR__.'/');
define("ROOT_DIR", __DIR__.'/../');
define("EXCEPTIONS", LIB_DIR.'exceptions/');

/** Base custom types */
define("DNULL", "CUSTOM_NULL");
use exceptions\dominantException;

/**
 * @param $className
 *
 * @throws dominantException
 */
function dominantAutoload( $className )
{
    $valid_url = LIB_DIR.str_replace('\\', '/', $className);

    if (file_exists($valid_url.".class.php")) {
        require_once( $valid_url.".class.php" );

        return;
    }

    if (file_exists($valid_url.".interface.php")) {
        require_once( $valid_url.".interface.php" );

        return;
    }

    throw new dominantException("Error: class {$className} not found", dominantException::UNFOUNDED_CLASS);
}

spl_autoload_register("dominantAutoload", true);

define("DEFAULT_PACKAGE", "default");
define("PACKAGES_DIR", ROOT_DIR.'packages/');
define("ASSETS_DIR", PACKAGES_DIR.'assets/');
define("DEFAULT_PACKAGE_DIR", PACKAGES_DIR.DEFAULT_PACKAGE.'/');