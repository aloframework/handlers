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

    use AloFramework\Handlers\Config\ExceptionConfig as Cfg;
    use AloFramework\Handlers\ExceptionHandler as H;
    use AloFramework\Log\Log;
    use PHPUnit_Framework_TestCase;
    use RuntimeException;

    class ExceptionHandlerTest extends PHPUnit_Framework_TestCase {

        function testRegister() {
            $this->assertFalse(H::isRegistered());
            $h = H::register();

            $this->assertTrue($h instanceof H);
            $this->assertTrue(H::isRegistered());
            $this->assertTrue(H::getLastRegisteredHandler() === $h);
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
