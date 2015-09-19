<?php

    use AloFramework\Handlers\AbstractHandler;

    error_reporting(E_ALL);
    ini_set('display_errors', 'on');

    require 'vendor/autoload.php';

    AbstractHandler::register();

    function one() {
        trigger_error('I am an error!', E_USER_ERROR);
    }

    function two() {
        one();
    }

    function three() {
        two();
    }

    three();
