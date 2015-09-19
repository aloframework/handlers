<?php

    namespace AloFramework\Handlers;

    class HandlersTest extends \PHPUnit_Framework_TestCase {

        function testDefined() {
            $req = ['ALO_HANDLERS_CSS_PATH', 'ALO_HANDLERS_ERROR_LEVEL'];

            foreach ($req as $r) {
                $this->assertTrue(defined($r), $r . ' wasn\'t defined');
            }
        }

        function testPath() {
            $this->assertTrue(file_exists(ALO_HANDLERS_CSS_PATH), 'File doesn\'t exist: ' . ALO_HANDLERS_CSS_PATH);
        }

        function testErrorHandlerRegister() {
            ErrorHandler::register();

            $this->assertTrue(ErrorHandler::isRegistered(), 'Failed to register error handler');
        }

        function testExceptionHandlerRegister() {
            ExceptionHandler::register();

            $this->assertTrue(ExceptionHandler::isRegistered(), 'Failed to register exception handler');
        }
    }
