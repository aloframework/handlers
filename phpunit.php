<?php

    if (!defined('ALO_HANDLERS_ERROR_LEVEL')) {
        define('ALO_HANDLERS_ERROR_LEVEL', E_ALL & ~E_STRICT);
    }

    if (!defined('ALO_HANDLERS_FORCE_HTML')) {
        define('ALO_HANDLERS_FORCE_HTML', true);
    }

    $f = __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

    if (file_exists($f)) {
        include_once $f;
    }
