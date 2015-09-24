<?php

    namespace AloFramework\Handlers;

    class ExampleErrorHandler extends ErrorHandler {

    }

    class HandlersTest extends \PHPUnit_Framework_TestCase {

        function testDefined() {
            $req = ['ALO_HANDLERS_CSS_PATH',
                    'ALO_HANDLERS_ERROR_LEVEL',
                    'ALO_HANDLERS_BACKGROUND',
                    'ALO_HANDLERS_FOREGROUND_NOTICE',
                    'ALO_HANDLERS_FOREGROUND_WARNING',
                    'ALO_HANDLERS_FOREGROUND_ERROR',
                    'ALO_HANDLERS_EXCEPTION_DEPTH',
                    'ALO_HANDLERS_TRACE_MAX_DEPTH',
                    'ALO_HANDLERS_LOG_ERROR_LOCATION',
                    'ALO_HANDLERS_LOG_EXCEPTION_LOCATION'];

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

        function testExtendedHandler() {
            $this->assertTrue(ExampleErrorHandler::register() instanceof ExampleErrorHandler, 'Extension failed');
        }
    }
