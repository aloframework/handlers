<?php
    /**
 *    Copyright (c) Arturas Molcanovas <a.molcanovas@gmail.com> 2016.
 *    https://github.com/aloframework/handlers
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

    namespace AloFramework\Handlers\Tests\Config;

    use AloFramework\Handlers\Config\AbstractConfig as Cfg;
    use PHPUnit_Framework_TestCase;

    class AbstractConfigTest extends PHPUnit_Framework_TestCase {

        static $defaults = [Cfg::CFG_TRACE_MAX_DEPTH    => 50,
                            Cfg::CFG_BACKGROUND         => 'default',
                            Cfg::CFG_FOREGROUND_NOTICE  => 'cyan',
                            Cfg::CFG_FOREGROUND_WARNING => 'yellow',
                            Cfg::CFG_FOREGROUND_ERROR   => 'red',
                            Cfg::CFG_FORCE_HTML         => false];

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
