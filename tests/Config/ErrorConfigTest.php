<?php

    namespace AloFramework\Handlers\Tests\Config;

    use AloFramework\Handlers\Config\AbstractConfig;
    use AloFramework\Handlers\Config\ErrorConfig as Cfg;
    use PHPUnit_Framework_TestCase;

    class ErrorConfigTest extends PHPUnit_Framework_TestCase {

        function testDefault() {
            $this->performUsualTests(new Cfg());
        }

        private function performUsualTests(AbstractConfig $cfg) {
            $this->assertEquals(true, $cfg[Cfg::CFG_LOG_ERROR_LOCATION]);
            $this->assertEquals(error_reporting(), $cfg[Cfg::CFG_ERROR_LEVEL]);
            $this->assertEquals('default', $cfg[Cfg::CFG_BACKGROUND]);
        }

        function testCustom() {
            $cfg = new Cfg(['foo' => 'bar']);

            $this->performUsualTests($cfg);
            $this->assertEquals('bar', $cfg['foo']);
        }
    }
