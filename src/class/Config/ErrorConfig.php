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
     *
     * @author Art <a.molcanovas@gmail.com>
     * @since  1.4.1 property PHPdocs added
     * @property bool $logErrorLocation Whether to include the error location in the log file
     * @property int  $errorLevel       Which error levels to log
     */
    class ErrorConfig extends AbstractConfig {

        /**
         * [INT] Which errors to log configuration key
         *
         * @var string
         */
        const CFG_ERROR_LEVEL = 'errorLevel';

        /**
         * [BOOL] Whether to log where the error occurred configuration key
         *
         * @var string
         */
        const CFG_LOG_ERROR_LOCATION = 'logErrorLocation';

        /**
         * Default config array
         *
         * @var array
         */
        private static $defaults;

        /**
         * Constructor
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array $cfg Default configuration overrides
         */
        public function __construct(array $cfg = []) {
            self::setDefaultConfig();
            parent::__construct(self::$defaults, $cfg);
        }

        /**
         * Sets the default configuration
         *
         * @author Art <a.molcanovas@gmail.com>
         */
        private static function setDefaultConfig() {
            if (!self::$defaults) {
                self::$defaults = [self::CFG_ERROR_LEVEL        => error_reporting(),
                                   self::CFG_LOG_ERROR_LOCATION => true];
            }
        }
    }
