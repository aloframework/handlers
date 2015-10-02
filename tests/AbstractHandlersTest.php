<?php

    namespace AloFramework\Handlers;

    class AbstractHandlersTest extends \PHPUnit_Framework_TestCase {

        function testAbstractHandlerRegister() {
            $this->assertFalse(ExceptionHandler::isRegistered());
            $this->assertFalse(ErrorHandler::isRegistered());
            AbstractHandler::register();
            $this->assertTrue(ExceptionHandler::isRegistered());
            $this->assertTrue(ErrorHandler::isRegistered());
        }

        function testShutdownHandlerRegister() {
            $this->assertTrue(ShutdownHandler::register() instanceof ShutdownHandler, 'Shutdown register() failed');
            $this->assertTrue(ShutdownHandler::isRegistered(), 'Shutdown isRegistered() failed');
        }
    }
