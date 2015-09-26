<?php

    define('ALO_HANDLERS_ERROR_LEVEL', E_ALL & ~E_STRICT);

    $f = __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

    if (file_exists($f)) {
        include_once $f;
    }
