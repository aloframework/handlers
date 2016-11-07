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

    use AloFramework\Config\AbstractConfig as ACFG;

    /**
     * AbstractHandler configuration
     *
     * @author Art <a.molcanovas@gmail.com>
     * @since  1.4.1 property phpdocs added<br/>
     *         1.4
     * @property string $fgInfo      Foreground colour for info-level messages in CLI mode
     * @property string $fgWarn      Foreground colour for info-level messages in CLI mode
     * @property string $fgErr       Foreground colour for info-level messages in CLI mode
     * @property bool   $forceHTML   Whether to force HTML output even in CLI mode
     * @property string $bgCli       Background colour in CLI mode
     * @property string $cssPath     Path to the CSS file
     * @property int    $traceDepth  Maximum number of debug backtrace items to output
     */
    class AbstractConfig extends ACFG {

        /**
         * [STR] The CSS path configuration key
         *
         * @var string
         */
        const CFG_CSS_PATH = 'cssPath';

        /**
         * [INT] Maximum debug backtrace depth configuration key
         *
         * @var string
         */
        const CFG_TRACE_MAX_DEPTH = 'traceDepth';

        /**
         * [STR] CLI background colour configuration key
         *
         * @var string
         */
        const CFG_BACKGROUND = 'bgCli';

        /**
         * [STR] CLI notice level foreground colour cofiguration key
         *
         * @var string
         */
        const CFG_FOREGROUND_NOTICE = 'fgInfo';

        /**
         * [STR] CLI warning level foreground colour cofiguration key
         *
         * @var string
         */
        const CFG_FOREGROUND_WARNING = 'fgWarn';

        /**
         * [STR] CLI error level foreground colour cofiguration key
         *
         * @var string
         */
        const CFG_FOREGROUND_ERROR = 'fgErr';

        /**
         * [BOOL] Whether to force HTML output regardless of whether the error is raised in CLI mode (configuration key)
         *
         * @var string
         */
        const CFG_FORCE_HTML = 'forceHTML';

        /**
         * Default configuration array
         *
         * @var array
         */
        private static $defaults;

        /**
         * Constructor
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array $defaults The default config of extending classes
         * @param array $cfg      Default configuration overrides
         */
        public function __construct(array $defaults = [], array $cfg = []) {
            self::setDefaults();
            parent::__construct(array_merge(self::$defaults, $defaults), $cfg);
        }

        /**
         * Sets default config
         *
         * @author Art <a.molcanovas@gmail.com>
         */
        private static function setDefaults() {
            if (!self::$defaults) {
                self::$defaults =
                    [self::CFG_CSS_PATH           => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' .
                                                     DIRECTORY_SEPARATOR . 'error.min.css',
                     self::CFG_TRACE_MAX_DEPTH    => 50,
                     self::CFG_BACKGROUND         => 'default',
                     self::CFG_FOREGROUND_NOTICE  => 'cyan',
                     self::CFG_FOREGROUND_WARNING => 'yellow',
                     self::CFG_FOREGROUND_ERROR   => 'red',
                     self::CFG_FORCE_HTML         => false];
            }
        }
    }
