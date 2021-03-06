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

    namespace AloFramework\Handlers;

    use AloFramework\Common\Alo;
    use AloFramework\Handlers\Config\AbstractConfig;
    use Psr\Log\LoggerInterface;

    /**
     * The shutdown handler. Allows fatal error reporting
     *
     * @author Art <a.molcanovas@gmail.com>
     * @since  1.2.1 Should now report fatal errors if no errors had been raised beforehand.<br/>
     *         1.2
     */
    class ShutdownHandler extends AbstractHandler {

        /**
         * Whether the handler has been registered
         *
         * @var bool
         */
        private static $registered = false;

        /**
         * The last registered handler
         *
         * @var self
         */
        private static $lastRegisteredHandler = null;

        /**
         * Checks whether the handler has been registered
         *
         * @author Art <a.molcanovas@gmail.com>
         * @return bool
         */
        public static function isRegistered() {
            return self::$registered;
        }

        /**
         * Returns the last registered handler
         *
         * @author Art <a.molcanovas@gmail.com>
         * @return self|null
         * @since  1.3
         */
        public static function getLastRegisteredHandler() {
            return self::$lastRegisteredHandler;
        }

        /**
         * Registers the shutdown handler
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log shutdowns.
         * @param AbstractConfig  $cfg    Configuration options
         *
         * @return self
         */
        public static function register(LoggerInterface $logger = null, $cfg = null) {
            self::$registered = true;
            $class = get_called_class();
            $handler = new $class($logger, $cfg);

            register_shutdown_function([$handler, 'handle']);
            self::$lastRegisteredHandler = &$handler;

            return $handler;
        }

        /**
         * The shutdown handler
         *
         * @author Art <a.molcanovas@gmail.com>
         * @since  1.2.1 Should now report fatal errors if no errors had been raised beforehand.
         * @codeCoverageIgnore
         */
        public function handle() {
            if (ErrorHandler::isRegistered()) {
                $e = new Error(error_get_last());

                if (Error::shouldBeReported($e->getType())) {
                    $r = ErrorHandler::getLastReportedError();
                    $h = ErrorHandler::getLastRegisteredHandler();

                    if (!$e->isEmpty() && $h && ($r ? !$r->isEmpty() && !$r->equals($e) : true)) {
                        $h->handle($e->getType(),
                                   Alo::ifnull($e->getMessage(), '<<unknown error>>'),
                                   Alo::ifnull($e->getFile(), '<<unknown file>>'),
                                   Alo::ifnull($e->getLine(), '<<unknown line>>'));
                    }
                }
            }
        }

        /**
         * Returns a string representation of the object
         *
         * @author Art <a.molcanovas@gmail.com>
         * @return string
         */
        public function __toString() {
            return parent::__toString() . self::EOL . 'Registered: Yes';
        }
    }
