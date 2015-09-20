<?php

    use AloFramework\Handlers\AbstractHandler;

    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
    //
    require 'vendor/autoload.php';

    AbstractHandler::register();

    function one($one, $two) {
        trigger_error('I am an error!', E_USER_WARNING);
    }

    function two($k) {
        unset($k);
        one(new \StdClass(), 11.6);
    }

    function three() {
        two('bar');
    }

    three();
