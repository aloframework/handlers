<?php

    namespace AloFramework\Handlers\Tests\Config;

    use AloFramework\Handlers\Config\AbstractConfig as Cfg;
    use PHPUnit_Framework_TestCase;

    class AbstractConfigTest extends PHPUnit_Framework_TestCase {

        static $defaults = [Cfg::CFG_TRACE_MAX_DEPTH           => 50,
                            Cfg::CFG_BACKGROUND                => 'default',
                            Cfg::CFG_FOREGROUND_NOTICE         => 'cyan',
                            Cfg::CFG_FOREGROUND_WARNING        => 'yellow',
                            Cfg::CFG_FOREGROUND_ERROR          => 'red',
                            Cfg::CFG_FORCE_HTML                => false,
                            Cfg::CFG_REGISTER_SHUTDOWN_HANDLER => false];

        /** @dataProvider noOverridesProvider */
        function testNoOverrides($key, $value) {
            $cfg = new Cfg();

            $this->assertEquals($value, $cfg[$key]);
        }

        function testDefaultOverride() {
            $cfg = new Cfg([Cfg::CFG_BACKGROUND => 'foo', 'foo' => 'bar']);

            $this->assertEquals('foo', $cfg[Cfg::CFG_BACKGROUND]);
            $this->assertEquals('bar', $cfg['foo']);
        }

        function testCustom() {
            $cfg = new Cfg([Cfg::CFG_BACKGROUND => 'foo'], ['foo' => 'bar']);

            $this->assertEquals('foo', $cfg[Cfg::CFG_BACKGROUND]);
            $this->assertEquals('bar', $cfg['foo']);
        }

        function testCssContents() {
            $dirExpected =
                __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' .
                DIRECTORY_SEPARATOR . 'error.min.css';
            $dirActual   = (new Cfg())->get(Cfg::CFG_CSS_PATH);

            $this->assertEquals(file_get_contents($dirExpected), file_get_contents($dirActual));
        }

        function noOverridesProvider() {
            $r = [];

            foreach (self::$defaults as $k => $v) {
                $r[] = [$k, $v];
            }

            return $r;
        }
    }
