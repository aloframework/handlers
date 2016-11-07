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

    namespace AloFramework\Handlers\Output;

    /**
     * A small extension to Symfony's ConsoleOutput so write methods return $this
     * @author Art <a.molcanovas@gmail.com>
     * @codeCoverageIgnore
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
