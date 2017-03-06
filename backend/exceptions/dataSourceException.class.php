<?php

namespace exceptions;

class dataSourceException extends dominantException{

    const DATA_SOURCE_MISSING = 300;
    const UNKNOWN_DATASOURCE_TYPE = 301;
    const INVALID_INSERTION = 302;
}