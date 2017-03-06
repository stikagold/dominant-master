<?php

/** Base directories */
define("LIB_DIR", __DIR__.'/');
define("ROOT_DIR", __DIR__.'/../');
define("EXCEPTIONS", LIB_DIR.'exceptions/');
define("MIGRATION_DIR", ROOT_DIR."migration/");
define("CONFIG_DIR", LIB_DIR."config/");
define("CONFIG_MIGRATION_DIR", MIGRATION_DIR.'configurations/');

define("PACKAGES_DIR", ROOT_DIR.'packages/');

define("PUBLIC_RESOURCES_PATH", 'public/');

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
//    echo "<br>This taked in autoload: {$className}<br>";
    $valid_url = LIB_DIR.str_replace('\\', '/', $className);
//    echo "<br>Try to find:-> ",$valid_url.".class.php", "<br>";
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
