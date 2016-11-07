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

    namespace AloFramework\Handlers\Tests\Misc;

    use AloFramework\Handlers\Error;
    use PHPUnit_Framework_TestCase;

    class ErrorTest extends PHPUnit_Framework_TestCase {

        function testConstructAllParams() {
            $e = new Error(E_USER_WARNING, 'foo', 'bar', 55);
            $this->checkAssertions($e, E_USER_WARNING, 'foo', 'bar', 55);
            $this->assertFalse($e->isEmpty());
        }

        private function checkAssertions(Error $e, $type, $msg, $file, $line) {
            $this->assertEquals($type, $e->getType());
            $this->assertEquals($type, $e['type']);
            $this->assertEquals($type, $e->type);

            $this->assertEquals($msg, $e->getMessage());
            $this->assertEquals($msg, $e['message']);
            $this->assertEquals($msg, $e->message);

            $this->assertEquals($file, $e->getFile());
            $this->assertEquals($file, $e['file']);
            $this->assertEquals($file, $e->file);

            $this->assertEquals($line, $e->getLine());
            $this->assertEquals($line, $e['line']);
            $this->assertEquals($line, $e->line);

            $expect = '[' . $type . '] ' . $msg . ' @ ' . $file . ' @ line ' . $line;
            $this->assertEquals($expect, $e->__toString());
            $this->assertEquals($expect, (string)$e);

        }

        function testConstructSomeParams() {
            $e = new Error(E_CORE_WARNING, 'foo', 'bar');
            $this->checkAssertions($e, E_CORE_WARNING, 'foo', 'bar', null);
            $this->assertTrue($e->isEmpty());
        }

        function testConstructArrayEmpty() {
            $e = new Error([]);
            $this->checkAssertions($e, null, null, null, null);
            $this->assertTrue($e->isEmpty());
        }

        function testConstructArrayFull() {
            $e = new Error(['type'    => E_COMPILE_ERROR,
                            'message' => 'foo',
                            'file'    => 'bar',
                            'line'    => 55]);

            $this->checkAssertions($e, E_COMPILE_ERROR, 'foo', 'bar', 55);
            $this->assertFalse($e->isEmpty());
        }

        function testConstructArrayPartial() {
            $e = new Error(['type' => E_COMPILE_ERROR,
                            'file' => 'bar',
                            'line' => 55]);

            $this->checkAssertions($e, E_COMPILE_ERROR, null, 'bar', 55);
            $this->assertTrue($e->isEmpty());
        }

        function testWeirdType() {
            $type = ['foo' => 'bar'];
            $e    = new Error(['type'    => $type,
                               'message' => 'foo',
                               'file'    => 'bar',
                               'line'    => 55]);

            $this->assertEquals(null, $e->type);
            $this->assertEquals('foo', $e->message);
            $this->assertEquals('bar', $e->file);
            $this->assertEquals(55, $e->line);
        }

        function testAssertEquals() {
            $e1 = new Error(E_COMPILE_ERROR, 'foo', 'bar', 666);
            $e2 = new Error($e1->getArrayCopy());
            $e3 = new Error(E_WARNING, 'foo', 'bar', 666);

            $this->assertTrue($e1->equals($e2));
            $this->assertFalse($e1->equals($e3));
            $this->assertFalse($e2->equals($e3));
        }

        function testShouldBeReported() {
            $reporting = E_ALL & ~E_COMPILE_WARNING;

            $this->assertTrue(Error::shouldBeReported(E_WARNING, $reporting));
            $this->assertFalse(Error::shouldBeReported(E_COMPILE_WARNING, $reporting));

            error_reporting($reporting);
            $this->assertTrue(Error::shouldBeReported(E_WARNING));
            $this->assertFalse(Error::shouldBeReported(E_COMPILE_WARNING));
        }
    }
