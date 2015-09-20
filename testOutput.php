<?php

    namespace AloFoo;

    use Exception;

    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
    //
    require 'vendor/autoload.php';

    class CustomException extends Exception {

    }

    function one($one, $two) {
        throw new CustomException('Exception One', 666, new Exception('Exception Two', 777));
    }

    function two($k) {
        unset($k);
        one(new \StdClass(), 11.6);
    }

    function three() {
        two('bar');
    }

    three();
