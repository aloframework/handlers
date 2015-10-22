<?php

    namespace AloFramework\Handlers\Tests\Handlers;

    use AloFramework\Handlers\AbstractHandler;
    use AloFramework\Handlers\Config\ErrorConfig;
    use AloFramework\Handlers\Config\ErrorConfig as Cfg;
    use AloFramework\Handlers\ErrorHandler as H;
    use AloFramework\Handlers\ExceptionHandler as ExH;
    use AloFramework\Log\Log;
    use PHPUnit_Framework_TestCase;

    class AbstractAndErrorTest extends PHPUnit_Framework_TestCase {

        function testConstructNoParams() {
            $this->assertFalse(H::isRegistered());

            $h = H::register();

            $this->assertTrue($h instanceof H);
            $this->assertTrue($h instanceof AbstractHandler);
            $this->assertTrue($h::getLastRegisteredHandler() === $h);
            $this->assertTrue(H::isRegistered());
            $this->assertFalse(ExH::isRegistered());
        }

        function testInjectCSS() {
            $h = H::register(null, new Cfg([Cfg::CFG_FORCE_HTML => true]));
            ob_start();
            $h->handle(1, 'msg', 'file', 1103);
            $ob = ob_get_clean();

            $this->assertTrue(stripos($ob, '<style type="text/css">.alo-bold,.alo-err') !== false);
        }

        function testCSSInvalidPath() {
            $h = H::register(null,
                             new Cfg([Cfg::CFG_FORCE_HTML => true,
                                      Cfg::CFG_CSS_PATH   => __FILE__ . DIRECTORY_SEPARATOR . 'nonexistent.php']));
            ob_start();
            $h->handle(1, 'msg', 'file', 1103);
            $ob = ob_get_clean();

            $this->assertTrue(stripos($ob,
                                      'The AloFramework handlers\' CSS file could not be found: ' .
                                      $h->getConfig(Cfg::CFG_CSS_PATH) . PHP_EOL) !== false);
        }

        function testToString() {
            $cfg    = new ErrorConfig();
            $logger = get_class(new Log());
            $str    = H::register(null, $cfg)->__toString();

            $this->assertEquals('CSS injected: ' . 'No' . H::EOL . 'Logger: ' . $logger . H::EOL .
                                'Max stack trace size: ' . $cfg->traceDepth . H::EOL . 'Registered: ' .
                                (H::isRegistered() ? 'Yes' : 'No') . H::EOL . 'Last reported error: ' .
                                (H::getLastReportedError() ? H::getLastReportedError()->__toString() : '<<none>>'),
                                $str);
        }
    }
