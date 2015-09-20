<?php

    if (!defined('ALO_HANDLERS_CSS_PATH')) {
        /** Path to the handlers' CSS file for injection */
        define('ALO_HANDLERS_CSS_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'error.min.css');
    }

    if (!defined('ALO_HANDLERS_ERROR_LEVEL')) {
        /** The error level to register the error handler for */
        define('ALO_HANDLERS_ERROR_LEVEL', ini_get('error_reporting'));
    }

    if (!defined('ALO_HANDLERS_EXCEPTION_DEPTH')) {
        /** Maximum number of previous exceptions to output */
        define('ALO_HANDLERS_EXCEPTION_DEPTH', 10);
    }

    if (!defined('ALO_HANDLERS_TRACE_MAX_DEPTH')) {
        /** Maximum number of items to appear in the debug backtrace */
        define('ALO_HANDLERS_TRACE_MAX_DEPTH', 100);
    }

    if (!defined('ALO_HANDLERS_BACKGROUND')) {
        /** CLI output background colour */
        define('ALO_HANDLERS_BACKGROUND', 'default');
    }

    if (!defined('ALO_HANDLERS_FOREGROUND_NOTICE')) {
        /** CLI output foreground colour for notices */
        define('ALO_HANDLERS_FOREGROUND_NOTICE', 'cyan');
    }

    if (!defined('ALO_HANDLERS_FOREGROUND_WARNING')) {
        /** CLI output foreground colour for warnings */
        define('ALO_HANDLERS_FOREGROUND_WARNING', 'yellow');
    }

    if (!defined('ALO_HANDLERS_FOREGROUND_ERROR')) {
        /** CLI output foreground colour for errors */
        define('ALO_HANDLERS_FOREGROUND_ERROR', 'red');
    }
