<?php

    if (!defined('ALO_HANDLERS_CSS_PATH')) {
        /** Path to the handlers' CSS file for injection */
        define('ALO_HANDLERS_CSS_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'error.min.css');
    }

    if (!defined('ALO_HANDLERS_ERROR_LEVEL')) {
        /** The error level to register the error handler for */
        define('ALO_HANDLERS_ERROR_LEVEL', ini_get('error_reporting'));
    }

    if (!defined('ALO_HANDLERS_BACKGROUND')) {
        /** CLI output background colour */
        define('ALO_HANDLERS_BACKGROUND', 'black');
    }
