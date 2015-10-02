<?php

    namespace AloFramework\Handlers;

    use AloFramework\Common\Alo;
    use PHPUnit_Framework_TestCase;

    class ErrorTest extends PHPUnit_Framework_TestCase {

        function testCustom() {
            $e = new Error(E_USER_WARNING, 'foo', 'bar', 666);

            $this->assertEquals('[' . E_USER_WARNING . '] foo @ bar @ line 666', (string)$e);

            $this->assertEquals(E_USER_WARNING, $e['type']);
            $this->assertEquals(E_USER_WARNING, $e->getType());

            $this->assertEquals('foo', $e['message']);
            $this->assertEquals('foo', $e->getMessage());

            $this->assertEquals('bar', $e['file']);
            $this->assertEquals('bar', $e->getFile());

            $this->assertEquals(666, $e['line']);
            $this->assertEquals(666, $e->getLine());

            $this->assertFalse($e->isEmpty());
        }

        function testEmpty() {
            $this->assertTrue((new Error([]))->isEmpty());
        }

        function testLast() {
            $last = error_get_last();

            $type = Alo::get($last['type']);
            $msg  = Alo::get($last['message']);
            $file = Alo::get($last['file']);
            $line = Alo::get($last['line']);

            if ($type && $msg && $file && $line) {
                $e = new Error($last);

                $this->assertEquals($type, $e->getType());
                $this->assertEquals($msg, $e->getMessage());
                $this->assertEquals($file, $e->getFile());
                $this->assertEquals($line, $e->getLine());
            }
        }

        function testToString() {
            $e = new Error(E_USER_ERROR, 'foo', 'bar', 6);

            $this->assertEquals('[' . E_USER_ERROR . '] foo @ bar @ line 6', $e->__toString());
        }

        function testEquals() {
            $e1  = new Error(E_USER_ERROR, 'foo', 'bar', 6);
            $e11 = new Error(E_USER_ERROR, 'foo', 'bar', 6);
            $e2  = new Error(E_USER_ERROR, 'foo', 'bar', 7);

            $this->assertTrue($e1->equals($e11));
            $this->assertFalse($e1->equals($e2));
            $this->assertFalse($e11->equals($e2));
        }

        /** @dataProvider reportingProvider */
        function testShouldBeReported($code, $shouldBeReported) {
            $reporting = E_ALL & ~E_STRICT & ~E_NOTICE;

            $this->assertEquals($shouldBeReported,
                                Error::shouldBeReported($code, $reporting),
                                Error::$map[$code] . ' failed.
            $shouldBeReported: ' . ($shouldBeReported ? 'Yes' : 'No'));
        }

        function reportingProvider() {
            return [[E_NOTICE, false],
                    [E_USER_NOTICE, true],
                    [E_CORE_ERROR, true],
                    [E_ERROR, true],
                    [E_USER_ERROR, true],
                    [E_COMPILE_ERROR, true],
                    [E_RECOVERABLE_ERROR, true],
                    [E_CORE_WARNING, true],
                    [E_DEPRECATED, true],
                    [E_USER_DEPRECATED, true],
                    [E_WARNING, true],
                    [E_USER_WARNING, true],
                    [E_STRICT, false],
                    [E_CORE_WARNING, true]];
        }
    }
