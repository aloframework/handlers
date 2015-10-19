<?php

    namespace AloFramework\Handlers\Tests\OutputFormatters;

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
