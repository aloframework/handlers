<?php

    namespace AloFramework\Handlers\Tests\Output;

    use PHPUnit_Framework_TestCase;
    use AloFramework\Handlers\Output\Dump;

    class DumpTest extends PHPUnit_Framework_TestCase {

        function testDump() {
            $this->assertTrue(stripos(Dump::html(1), '<script class="-kint-js">') !== false);
        }
    }
