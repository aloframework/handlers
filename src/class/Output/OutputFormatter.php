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

    use Symfony\Component\Console\Formatter\OutputFormatter as TheClass;

    /**
     * The handlers' output formatter
     * @author Art <a.molcanovas@gmail.com>
     */
    class OutputFormatter extends TheClass {

        /**
         * Styles to set
         * @var array
         */
        private static $styles = [];

        /**
         * Colours to initialise
         * @var array
         */
        private static $styleColours = ['e' => '\AloFramework\Handlers\Output\Styles\Error',
                                        'i' => '\AloFramework\Handlers\Output\Styles\Info',
                                        'w' => '\AloFramework\Handlers\Output\Styles\Warning'];

        /**
         * Variants to initialise
         * @var array
         */
        private static $styleVariants = ['b' => 'bold',
                                         'u' => 'underscore'];

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         */
        function __construct() {
            parent::__construct();
            self::initStyles();

            foreach (self::$styles as $code => $class) {
                $this->setStyle($code, $class);
            }
        }

        /**
         * Initialises the style objects
         * @author Art <a.molcanovas@gmail.com>
         */
        private static function initStyles() {
            if (empty(self::$styles)) {
                foreach (self::$styleColours as $colourCode => $class) {
                    self::$styles[$colourCode] = new $class();

                    foreach (self::$styleVariants as $varCode => $var) {
                        self::$styles[$colourCode . $varCode] = new $class($var);
                    }
                }
            }
        }
    }
