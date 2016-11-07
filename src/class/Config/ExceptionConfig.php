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

    namespace AloFramework\Handlers\Config;

    /**
     * Error config class
     * @author Art <a.molcanovas@gmail.com>
     * @since  1.4.1 property PHPdocs added
     * @property int  $prevExceptionDepth   How many previous exceptions to output
     * @property bool $logExceptionLocation Whether to include the exception location in the log message
     */
    class ExceptionConfig extends AbstractConfig {

        /**
         * [INT] How many previous exceptions to echo configuration key
         * @var string
         */
        const CFG_EXCEPTION_DEPTH = 'prevExceptionDepth';

        /**
         * [BOOL] Whether to log where the exception occurred configuration key
         * @var string
         */
        const CFG_LOG_EXCEPTION_LOCATION = 'logExceptionLocation';

        /**
         * Default config array
         * @var array
         */
        private static $defaults;

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array $cfg Default configuration overrides
         */
        function __construct(array $cfg = []) {
            self::setDefaultConfig();
            parent::__construct(self::$defaults, $cfg);
        }

        /**
         * Sets the default configuration
         * @author Art <a.molcanovas@gmail.com>
         */
        private static function setDefaultConfig() {
            if (!self::$defaults) {
                self::$defaults = [self::CFG_EXCEPTION_DEPTH        => 10,
                                   self::CFG_LOG_EXCEPTION_LOCATION => true];
            }
        }
    }
