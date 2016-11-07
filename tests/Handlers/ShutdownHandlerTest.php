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
