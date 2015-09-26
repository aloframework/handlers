<?php

    namespace AloFramework\Handlers;

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

            $type = self::get($last['type']);
            $msg  = self::get($last['message']);
            $file = self::get($last['file']);
            $line = self::get($last['line']);

            if ($type && $msg && $file && $line) {
                $e = new Error($last);

                $this->assertEquals($type, $e->getType());
                $this->assertEquals($msg, $e->getMessage());
                $this->assertEquals($file, $e->getFile());
                $this->assertEquals($line, $e->getLine());
            }
        }

        private static function get(&$var) {
            return isset($var) ? $var : null;
        }
    }
