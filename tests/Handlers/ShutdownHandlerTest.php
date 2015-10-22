<?php

    namespace AloFramework\Handlers\Tests\Handlers;

    use AloFramework\Handlers\Config\ErrorConfig as Cfg;
    use AloFramework\Handlers\ShutdownHandler as H;
    use AloFramework\Log\Log;
    use PHPUnit_Framework_TestCase;

    class ShutdownHandlerTest extends PHPUnit_Framework_TestCase {

        function testRegister() {
            $this->assertFalse(H::isRegistered());
            $h = H::register();

            $this->assertTrue(H::isRegistered());
            $this->assertTrue($h instanceof H);
            $this->assertTrue(H::getLastRegisteredHandler() === $h);
        }

        function testToString() {
            $cfg    = new Cfg([Cfg::CFG_FORCE_HTML => true]);
            $logger = get_class(new Log());
            $str    = H::register()->__toString();

            $expect = 'CSS injected: ' . 'No' . H::EOL . 'Logger: ' . $logger . H::EOL . 'Max stack trace size: ' .
                      $cfg->traceDepth . H::EOL . 'Registered: ' . (H::isRegistered() ? 'Yes' : 'No');

            $this->assertEquals($expect, $str);
        }
    }
