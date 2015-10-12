<?php

    namespace AloFramework\Handlers\Tests\Config;

    use AloFramework\Handlers\Config\AbstractConfig;
    use AloFramework\Handlers\Config\ExceptionConfig as Cfg;
    use PHPUnit_Framework_TestCase;

    class ExceptionConfigTest extends PHPUnit_Framework_TestCase {

        function testDefault() {
            $this->performUsualTests(new Cfg());
        }

        private function performUsualTests(AbstractConfig $cfg) {
            $this->assertEquals(10, $cfg[Cfg::CFG_EXCEPTION_DEPTH]);
            $this->assertEquals(true, $cfg[Cfg::CFG_LOG_EXCEPTION_LOCATION]);
            $this->assertEquals('default', $cfg[Cfg::CFG_BACKGROUND]);
        }

        function testCustom() {
            $cfg = new Cfg(['foo' => 'bar']);

            $this->performUsualTests($cfg);
            $this->assertEquals('bar', $cfg['foo']);
        }
    }
