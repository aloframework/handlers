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

    namespace AloFramework\Handlers\Tests\Output;

    use AloFramework\Handlers\Output\OutputFormatter;
    use AloFramework\Handlers\Output\Styles\Error;
    use AloFramework\Handlers\Output\Styles\Info;
    use AloFramework\Handlers\Output\Styles\Warning;
    use PHPUnit_Framework_TestCase;

    class OutputFormatterTest extends PHPUnit_Framework_TestCase {

        function testStyles() {
            $o = new OutputFormatter();

            $this->assertTrue($o->getStyle('e') instanceof Error);
            $this->assertTrue($o->getStyle('eb') instanceof Error);
            $this->assertTrue($o->getStyle('eu') instanceof Error);

            $this->assertTrue($o->getStyle('i') instanceof Info);
            $this->assertTrue($o->getStyle('ib') instanceof Info);
            $this->assertTrue($o->getStyle('iu') instanceof Info);

            $this->assertTrue($o->getStyle('w') instanceof Warning);
            $this->assertTrue($o->getStyle('wb') instanceof Warning);
            $this->assertTrue($o->getStyle('wu') instanceof Warning);
        }
    }
