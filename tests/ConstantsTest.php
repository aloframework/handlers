<?php

    class ConstantsTest extends PHPUnit_Framework_TestCase {

        /** @dataProvider definedProvider */
        function testDefined($r) {
            $this->assertTrue(defined($r), $r . ' wasn\'t defined');
        }

        function testPath() {
            $this->assertTrue(file_exists(ALO_HANDLERS_CSS_PATH), 'File doesn\'t exist: ' . ALO_HANDLERS_CSS_PATH);
        }

        function definedProvider() {
            return [['ALO_HANDLERS_CSS_PATH'],
                    ['ALO_HANDLERS_ERROR_LEVEL'],
                    ['ALO_HANDLERS_BACKGROUND'],
                    ['ALO_HANDLERS_FOREGROUND_NOTICE'],
                    ['ALO_HANDLERS_FOREGROUND_WARNING'],
                    ['ALO_HANDLERS_FOREGROUND_ERROR'],
                    ['ALO_HANDLERS_EXCEPTION_DEPTH'],
                    ['ALO_HANDLERS_TRACE_MAX_DEPTH'],
                    ['ALO_HANDLERS_FORCE_HTML'],
                    ['ALO_HANDLERS_LOG_ERROR_LOCATION'],
                    ['ALO_HANDLERS_LOG_EXCEPTION_LOCATION'],
                    ['ALO_HANDLERS_REGISTER_SHUTDOWN']];
        }
    }
