<?php

    namespace AloFramework\Handlers\Output;

    use InvalidArgumentException;

    /**
     * A small extension to Symfony's ConsoleOutput so write methods return $this
     * @author Art <a.molcanovas@gmail.com>
     */
    class ConsoleOutput extends \Symfony\Component\Console\Output\ConsoleOutput {

        /**
         * Writes a message to the output.
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param string|array $messages The message as an array of lines or a single string
         * @param bool         $newline  Whether to add a newline
         * @param int          $type     The type of output (one of the OUTPUT constants)
         *
         * @throws InvalidArgumentException When unknown output type is given
         *
         * @return ConsoleOutput
         */
        function write($messages, $newline = false, $type = self::OUTPUT_NORMAL) {
            parent::write($messages, $newline, $type);

            return $this;
        }

        /**
         * Writes a message to the output and adds a newline at the end.
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param string|array $messages The message as an array of lines of a single string
         * @param int          $type     The type of output (one of the OUTPUT constants)
         *
         * @throws InvalidArgumentException When unknown output type is given
         *
         * @return ConsoleOutput
         */
        function writeln($messages, $type = self::OUTPUT_NORMAL) {
            return $this->write($messages, $type);
        }
    }
