<?php

    namespace AloFramework\Handlers;

    use AloFramework\Handlers\ShutdownHandler as H;

    class ExampleShutdownHandler extends H {

    }

    class ShutdownHandlerTest extends \PHPUnit_Framework_TestCase {

        function testExtendedHandler() {
            $this->assertFalse(ExampleShutdownHandler::isRegistered());
            $this->assertTrue(ExampleShutdownHandler::register() instanceof ExampleShutdownHandler,
                              'Extension failed');
            $this->assertTrue(ExampleShutdownHandler::isRegistered());
        }

        function testToString() {
            $ts = H::register()->__toString();

            $this->assertEquals('CSS injected: No' . H::EOL . 'Logger: AloFramework\Log\Log' . H::EOL .
                                'Max stack trace size: ' . ALO_HANDLERS_TRACE_MAX_DEPTH . H::EOL . 'Registered: Yes',
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
