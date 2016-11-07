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
    use AloFramework\Handlers\Config\ExceptionConfig;
    use Psr\Log\LoggerInterface;

    /**
     * Exception handling class
     *
     * @author Art <a.molcanovas@gmail.com>
     * @since  1.4 Uses the Configurable interface<br/>
     *         1.2 Tracks the last set handler & exception<br/>
     *         1.1 log() accepts the $includeLocation parameter
     * @property ExceptionConfig $config Handler configuration
     */
    class ExceptionHandler extends AbstractHandler {

        /**
         * Whether this handler has been enabled
         *
         * @var bool
         */
        private static $registered = false;

        /**
         * Last reported exception
         *
         * @var \Throwable
         */
        private static $lastReported = null;

        /**
         * The last registered handler
         *
         * @var self
         */
        private static $lastRegisteredHandler = null;

        /**
         * Constructor
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log exceptions.
         * @param ExceptionConfig $cfg    The configuration class
         *
         * @since  1.4 $cfg added. This will become the first parameter in the constructor in 2.0
         */
        public function __construct(LoggerInterface $logger = null, ExceptionConfig $cfg = null) {
            parent::__construct($logger, Alo::ifnull($cfg, new ExceptionConfig()));
        }

        /**
         * Returns the last registered handler
         *
         * @author Art <a.molcanovas@gmail.com>
         * @return self|null
         * @since  1.2
         */
        public static function getLastRegisteredHandler() {
            return self::$lastRegisteredHandler;
        }

        /**
         * Returns the last reported exception
         *
         * @author Art <a.molcanovas@gmail.com>
         * @return \Throwable|null The last reported exception or NULL if none have been reported
         * @since  1.2
         */
        public static function getLastReportedException() {
            return self::$lastReported;
        }

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
         * Registers the exception handler
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         * @param ExceptionConfig $cfg    Your custom configuration settings
         *
         * @return self The created handler instance
         * @since  1.4 $cfg added<br/>
         *         1.0.4 Checks what class has called the method instead of explicitly registering ExceptionHandler -
         *         allows easy class extendability.
         */
        public static function register(LoggerInterface $logger = null, ExceptionConfig $cfg = null) {
            self::$registered = true;

            // To allow easy extending
            $class = get_called_class();
            $handler = new $class($logger, $cfg);

            set_exception_handler([$handler, 'handle']);
            self::$lastRegisteredHandler = &$handler;

            return $handler;
        }

        /**
         * Exception handler
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param \Throwable $e The exception
         *
         * @codeCoverageIgnore
         */
        public function handle($e) {
            $this->injectCSS();
            self::$lastReported = $e;

            if ($this->isCLI) {
                $this->handleCLI($e);
            } else {
                $this->handleHTML($e);
            }

            $this->log($e);
        }

        /**
         * Handles the exception with CLI output
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param \Throwable $e The exception
         *
         * @codeCoverageIgnore
         */
        protected function handleCLI($e) {
            $this->console->write('<eb>Uncaught ' . get_class($e) . ': </>')
                          ->write('<e>[' . $e->getCode() . '] ' . $e->getMessage() . '</>', true)
                          ->write('<e>Raised in </>')
                          ->write('<eu>' . $e->getFile() . '</>')
                          ->write('<e> @ line </>')
                          ->write('<eu>' . $e->getLine() . '</>', true);

            $this->echoPreviousExceptions($e->getPrevious());

            $this->console->write('<eb>Debug backtrace:</eb>', true)->writeln('');

            echo $this->getTrace($e->getTrace(), 'e');
        }

        /**
         * Echoes previous exceptions if applicable
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param null|\Throwable $e     The previous exception
         * @param int             $level How many previous exceptions have been echoed so far
         *
         * @codeCoverageIgnore
         */
        protected function echoPreviousExceptions($e, $level = 0) {
            if ($level < $this->config->prevExceptionDepth && $e) {
                if ($this->isCLI) {
                    $this->console->write('<eb>Preceded by </>')
                                  ->write('<e>[' . $e->getCode() . '] ' . $e->getMessage() . '</>')
                                  ->write('<e> @ ' . $e->getFile() . '\'s line ' . $e->getLine() . '</>', true)
                                  ->writeln('');
                } else {
                    ?>
                    <div>
                        <span class="alo-bold">Preceded by </span>
                        <span>[<?= $e->getCode() ?>]: <?= $e->getMessage() ?></span>
                        <span> @ </span>
                        <span class="alo-uline"><?= $e->getFile() ?></span>
                        <span> @ line </span>
                        <span class="alo-uline"><?= $e->getLine() ?>
                    </div>
                    <?php
                }

                $this->echoPreviousExceptions($e->getPrevious(), ++$level);
            }
        }

        /**
         * Handles an exception with HTML output
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param \Throwable $e The exception
         */
        protected function handleHTML($e) {
            ?>

            <div class="text-center">
                <div class="alo-err alert alert-danger">
                    <div>
                        <span class="alo-bold">Uncaught <?= get_class($e) ?>: </span>
                        <span>[<?= $e->getCode() ?>] <?= $e->getMessage() ?></span>
                    </div>
                    <div>
                        <span class="alo-bold">Raised in </span>
                        <span class="alo-uline"><?= $e->getFile() ?>
                            <span> @ line </span>
                            <span class="alo-uline"><?= $e->getLine() ?>
                    </div>
                    <?php $this->echoPreviousExceptions($e->getPrevious()) ?>
                    <div>
                        <span class="alo-bold">Backtrace:</span>
                        <?= $this->getTrace($e->getTrace(), 'e') ?>
                    </div>
                </div>
            </div>
            <?php
        }

        /**
         * Logs a message if the logger is enabled
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param \Throwable $e               The exception to log
         * @param bool       $includeLocation Whether to include the file and line where the exception occurred
         *
         * @since  1.1 Accepts the $includeLocation parameter
         */
        protected function log($e, $includeLocation = true) {
            $msg = '[' . $e->getCode() . '] ' . $e->getMessage();

            if ($this->config->logExceptionLocation && $includeLocation) {
                $msg .= ' (occurred in ' . $e->getFile() . ' @ line ' . $e->getLine() . ')';
            }

            $this->logger->error($msg);
        }

        /**
         * Returns a string representation of the handler
         *
         * @author Art <a.molcanovas@gmail.com>
         * @return string
         */
        public function __toString() {
            return parent::__toString() . self::EOL . 'Registered: ' . (self::$registered ? 'Yes' : 'No') . self::EOL .
                   'Previous exception recursion limit: ' . ($this->config->prevExceptionDepth) . self::EOL .
                   'Last reported exception: ' . (self::$lastReported ? self::$lastReported->__toString() : '[none]');
        }
    }
