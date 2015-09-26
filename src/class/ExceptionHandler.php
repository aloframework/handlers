<?php

    namespace AloFramework\Handlers;

    use Exception;
    use Psr\Log\LoggerInterface;

    /**
     * Exception handling class
     * @author Art <a.molcanovas@gmail.com>
     * @since  1.2 Tracks the last set handler & exception<br/>
     *         1.1 log() accepts the $includeLocation parameter
     */
    class ExceptionHandler extends AbstractHandler {

        /**
         * Whether this handler has been enabled
         * @var bool
         */
        private static $registered = false;

        /**
         * Maximum number of previous exceptions to echo
         * @var int
         */
        protected $maxRecursion;

        /**
         * Last reported exception
         * @var Exception
         */
        private static $lastReported = null;

        /**
         * The last registered handler
         * @var self
         */
        private static $lastRegisteredHandler = null;

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         * @inheritdoc
         */
        function __construct(LoggerInterface $logger = null) {
            parent::__construct($logger);
            $this->maxRecursion = (int)ALO_HANDLERS_EXCEPTION_DEPTH;
        }

        /**
         * Returns the last registered handler
         * @author Art <a.molcanovas@gmail.com>
         * @return self|null
         * @since  1.2
         */
        static function getLastRegisteredHandler() {
            return self::$lastRegisteredHandler;
        }

        /**
         * Returns the last reported exception
         * @author Art <a.molcanovas@gmail.com>
         * @return Exception|null The last reported exception or NULL if none have been reported
         * @since  1.2
         */
        static function getLastReportedException() {
            return self::$lastReported;
        }

        /**
         * Checks whether the handler has been registered
         * @author Art <a.molcanovas@gmail.com>
         * @return bool
         */
        static function isRegistered() {
            return self::$registered;
        }

        /**
         * Echoes previous exceptions if applicable
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param null|Exception $e     The previous exception
         * @param int            $level How many previous exceptions have been echoed so far
         */
        protected function echoPreviousExceptions($e, $level = 0) {
            if ($level < $this->maxRecursion && ($e instanceof Exception)) {
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
         * Exception handler
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param Exception $e The exception
         */
        function handle(Exception $e) {
            $this->injectCSS();

            if ($this->isCLI) {
                $this->handleCLI($e);
            } else {
                $this->handleHTML($e);
            }

            $this->log($e);
        }

        /**
         * Handles an exception with HTML output
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param Exception $e The exception
         */
        protected function handleHTML(Exception $e) {
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
         * Handles the exception with CLI output
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param Exception $e The exception
         */
        protected function handleCLI(Exception $e) {
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
         * Logs a message if the logger is enabled
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param Exception $e               The exception to log
         * @param bool      $includeLocation Whether to include the file and line where the exception occurred
         *
         * @since  1.1 Accepts the $includeLocation parameter
         */
        protected function log(Exception $e, $includeLocation = true) {
            $msg = '[' . $e->getCode() . '] ' . $e->getMessage();

            if (ALO_HANDLERS_LOG_EXCEPTION_LOCATION && $includeLocation) {
                $msg .= ' (occurred in ' . $e->getFile() . ' @ line ' . $e->getLine() . ')';
            }

            $this->logger->error($msg);
        }

        /**
         * Registers the exception handler
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         *
         * @return self The created handler instance
         * @since  1.0.4 Checks what class has called the method instead of explicitly registering ExceptionHandler -
         * allows easy class extendability.
         */
        static function register(LoggerInterface $logger = null) {
            self::$registered = true;

            // To allow easy extending
            $class   = get_called_class();
            $handler = new $class($logger);

            set_exception_handler([$handler, 'handle']);

            return $handler;
        }

        /**
         * Returns a string representation of the handler
         * @author Art <a.molcanovas@gmail.com>
         * @return string
         */
        function __toString() {
            return parent::__toString() . self::EOL . 'Registered: ' . (self::$registered ? 'Yes' : 'No') . self::EOL .
                   'Previous exception recursion limit: ' . ($this->maxRecursion) . self::EOL .
                   'Last reported exception: ' . (self::$lastReported ? self::$lastReported->__toString() : '[none]');
        }
    }
