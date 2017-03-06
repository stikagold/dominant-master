<?php

namespace exceptions;

class configurationException extends dominantException{

    const CONFIGURATION_FILE_MISSING = 200;
    const CONFIGURATION_UNSUPPORTED_ACTION = 201;
    const CONFIGURATION_PERMISSION_DENY = 202;
    const CONFIGURATION_MISSING_AREA = 203;
}