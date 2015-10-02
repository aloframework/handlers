<?php

    namespace AloFramework\Handlers;

    use AloFramework\Handlers\ErrorHandler as H;

    class ExampleErrorHandler extends ErrorHandler {

    }

    class ErrorHandlerTest extends \PHPUnit_Framework_TestCase {

        function testToString() {
            $ts = H::register()->__toString();

            $this->assertEquals('CSS injected: No' . H::EOL . 'Logger: AloFramework\Log\Log' . H::EOL .
                                'Max stack trace size: ' . ALO_HANDLERS_TRACE_MAX_DEPTH . H::EOL . 'Registered: Yes' .
                                H::EOL . 'Last reported error: <<none>>',
                                $ts);
        }

        function testLastReg() {
            $h = H::register();

            $this->assertEquals($h, H::getLastRegisteredHandler());
        }

        function testLastRepError() {
            $h = H::register();
            $e = error_get_last();

            if ($e && H::getLastReportedError()) {
                $e = new Error($e);

                if (Error::shouldBeReported($e->getType())) {
                    $this->assertTrue($e->equals($h::getLastReportedError()));
                }
            }
        }

        function testExtendedHandler() {
            $this->assertFalse(ExampleErrorHandler::isRegistered());
            $this->assertTrue(ExampleErrorHandler::register() instanceof ExampleErrorHandler, 'Extension failed');
            $this->assertTrue(ExampleErrorHandler::isRegistered());
        }

        function testRegister() {
            $this->assertFalse(ErrorHandler::isRegistered());
            $this->assertTrue(ErrorHandler::register() instanceof ErrorHandler);
            $this->assertTrue(ErrorHandler::isRegistered(), 'Failed to register error handler');
        }
    }
