<?php

    namespace AloFramework\Handlers\Tests\Handlers;

    use AloFramework\Handlers\ExceptionHandler as H;
    use AloFramework\Handlers\Config\ExceptionConfig as Cfg;
    use AloFramework\Log\Log;
    use PHPUnit_Framework_TestCase;
    use RuntimeException;

    class ExceptionHandlerTest extends PHPUnit_Framework_TestCase {

        function testRegister() {
            $h = H::register();

            $this->assertTrue($h instanceof H);
            $this->assertTrue(H::isRegistered());
            $this->assertTrue(H::getLastRegisteredHandler() instanceof $h);
        }

        function testLastReportedException() {
            $e = new RuntimeException('N&C', 69);
            $c = new Cfg([Cfg::CFG_FORCE_HTML => true]);
            $h = H::register(null, $c);

            ob_start();
            $h->handle($e);
            ob_end_clean();

            $this->assertTrue($e === $h::getLastReportedException());
        }

        function testToString() {
            $ts     = H::register()->__toString();
            $cfg    = new Cfg();
            $logger = get_class(new Log());

            $expect = 'CSS injected: ' . 'No' . H::EOL . 'Logger: ' . $logger . H::EOL . 'Max stack trace size: ' .
                      $cfg->traceDepth . H::EOL . 'Registered: ' . (H::isRegistered() ? 'Yes' : 'No') . H::EOL .
                      'Previous exception recursion limit: ' . ($cfg->prevExceptionDepth) . H::EOL .
                      'Last reported exception: ' .
                      (H::getLastReportedException() ? H::getLastReportedException()->__toString() : '[none]');

            $this->assertEquals($expect, $ts);
        }
    }
