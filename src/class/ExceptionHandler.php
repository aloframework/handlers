<?php

    namespace AloFramework\Handlers;

    use Exception;
    use Psr\Log\LoggerInterface;

    /**
     * Exception handling class
     * @author Art <a.molcanovas@gmail.com>
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
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         *                                AloFramework\Log\Log extends this interface.
         */
        function __construct(LoggerInterface $logger = null) {
            parent::__construct($logger);
            $this->maxRecursion = (int)ALO_HANDLERS_EXCEPTION_DEPTH;
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
         * @param Exception $e The exception to log
         */
        protected function log(Exception $e) {
            if ($this->logger) {
                $this->logger->error('[' . $e->getCode() . '] ' . $e->getMessage());
            }
        }

        /**
         * Registers the exception handler
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         *                                AloFramework\Log\Log extends this interface.
         *
         * @return ExceptionHandler The created handler instance
         */
        static function register(LoggerInterface $logger = null) {
            self::$registered = true;
            $handler          = new ExceptionHandler($logger);

            set_exception_handler([$handler, 'handle']);

            return $handler;
        }
    }
