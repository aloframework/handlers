<?php

    namespace AloFramework\Handlers\Output;

    /**
     * A small extension to Symfony's ConsoleOutput so write methods return $this
     * @author Art <a.molcanovas@gmail.com>
     */
    class ConsoleOutput extends \Symfony\Component\Console\Output\ConsoleOutput {

        /**
         * Constructor.
         * @author Art <a.molcanovas@gmail.com>
         */
        function __construct() {
            parent::__construct(self::VERBOSITY_NORMAL, null, new OutputFormatter());
        }

        /**
         * Writes a console message
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array|string $messages The message or array of messages
         * @param bool         $newline  Whether to insert a newline after the message(s)
         * @param int          $type     Output type
         *
         * @return self
         */
        function write($messages, $newline = false, $type = self::OUTPUT_NORMAL) {
            parent::write($messages, $newline, $type);

            return $this;
        }
    }
