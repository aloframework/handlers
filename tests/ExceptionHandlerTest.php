<?php

    namespace AloFramework\Handlers;

    use AloFramework\Handlers\ExceptionHandler as H;

    class ExampleExceptionHandler extends H {

    }

    class ExceptionHandlerTest extends \PHPUnit_Framework_TestCase {

        function testExtendedHandler() {
            $this->assertFalse(ExampleExceptionHandler::isRegistered());
            $this->assertTrue(ExampleExceptionHandler::register() instanceof ExampleExceptionHandler,
                              'Extension failed');
            $this->assertTrue(ExampleExceptionHandler::isRegistered());
        }

        function testToString() {
            $ts = H::register()->__toString();

            $this->assertEquals('CSS injected: No' . H::EOL . 'Logger: AloFramework\Log\Log' . H::EOL .
                                'Max stack trace size: ' . ALO_HANDLERS_TRACE_MAX_DEPTH . H::EOL . 'Registered: Yes' .
                                H::EOL . 'Previous exception recursion limit: ' . ALO_HANDLERS_EXCEPTION_DEPTH .
                                H::EOL . 'Last reported exception: [none]',
                                $ts);
        }

        function testLastReg() {
            $h = H::register();

            $this->assertEquals($h, H::getLastRegisteredHandler());
        }

        function testExceptionHandlerRegister() {
            $this->assertFalse(ExceptionHandler::isRegistered());
            $this->assertTrue(ExceptionHandler::register() instanceof ExceptionHandler);
            $this->assertTrue(ExceptionHandler::isRegistered());
        }
    }
