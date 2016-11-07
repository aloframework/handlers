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
