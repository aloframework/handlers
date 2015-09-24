<?php

    namespace AloFramework\Handlers;

    use Psr\Log\LoggerInterface;

    /**
     * Handles PHP errors
     * @author Art <a.molcanovas@gmail.com>
     * @since  1.1 log() accepts the $file and $line parameters
     */
    class ErrorHandler extends AbstractHandler {

        /**
         * Whether this handler has been enabled
         * @var bool
         */
        private static $registered = false;

        /**
         * Checks whether the handler has been registered
         * @author Art <a.molcanovas@gmail.com>
         * @return bool
         */
        static function isRegistered() {
            return self::$registered;
        }

        /**
         * The error handler
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param int    $errno   The level of the error raised
         * @param string $errstr  The error message
         * @param string $errfile The filename that the error was raised in
         * @param int    $errline The line number the error was raised at
         *
         * @uses   ErrorHandler::log()
         * @uses   AbstractHandler::injectCSS()
         * @uses   ErrorHandler::handleHTML()
         * @uses   ErrorHandler::handleCLI()
         */
        function handle($errno, $errstr, $errfile, $errline) {
            $this->injectCss();
            $type  = $errno;
            $label = 'warning';

            switch ($errno) {
                case E_NOTICE:
                case E_USER_NOTICE:
                    $type  = 'NOTICE';
                    $label = 'info';
                    break;
                case E_ERROR:
                case E_USER_ERROR:
                case E_COMPILE_ERROR:
                case E_RECOVERABLE_ERROR:
                case E_CORE_ERROR:
                    $type  = 'ERROR';
                    $label = 'danger';
                    break;
                case E_DEPRECATED:
                case E_USER_DEPRECATED:
                    $type = 'DEPRECATED';
                    break;
                case E_WARNING:
                case E_USER_WARNING:
                case E_CORE_WARNING:
                    $type = 'WARNING';
                    break;
            }

            $file = implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, $errfile), -2));

            if (!$errline) {
                $errline = '<<unknown>>';
            }

            if ($this->isCLI) {
                $label = $label == 'danger' ? 'e' : substr($label, 0, 1);
                $this->handleCLI($type, $label, $errno, $errstr, $file, $errline);
            } else {
                $this->handleHTML($type, $label, $errno, $errstr, $file, $errline);
            }

            $this->log($errno, $errstr, $errfile, $errline);
        }

        /**
         * Logs an error
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param int    $errcode The error's code
         * @param string $errstr  The error message
         * @param string $file    File where the error occurred
         * @param int    $line    Line number where the error occurred
         *
         * @since  1.1 Accepts the $file and $line parameters
         * @uses   LoggerInterface::error()
         * @uses   LoggerInterface::notice()
         * @uses   LoggerInterface::warning()
         */
        protected function log($errcode, $errstr, $file = null, $line = null) {
            if ($this->logger) {
                switch ($errcode) {
                    case E_NOTICE:
                    case E_USER_NOTICE:
                        $method = 'notice';
                        break;
                    case E_WARNING:
                    case E_USER_WARNING:
                    case E_CORE_WARNING:
                    case E_DEPRECATED:
                    case E_USER_DEPRECATED:
                        $method = 'warning';
                        break;
                    default:
                        $method = 'error';
                }

                $msg = '[' . $errcode . '] ' . $errstr;

                if (ALO_HANDLERS_LOG_ERROR_LOCATION && $file && $line) {
                    $msg .= ' (occurred in ' . $file . ' @ line ' . $line . ')';
                }

                $this->logger->{$method}($msg);
            }
        }

        /**
         * Generates HTML output for errors
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param string $type    Error type
         * @param string $label   Error label ("danger", "info" or "warning")
         * @param int    $errno   Error code
         * @param string $errstr  Error message
         * @param string $errfile File where the error occurred
         * @param int    $errline Line where the error occurred
         *
         * @uses   AbstractHandler::getTrace()
         */
        protected function handleHTML($type, $label, $errno, $errstr, $errfile, $errline) {
            ?>
            <div class="text-center">
                <div class="alo-err alert alert-<?= $label ?>">
                    <div>
                        <span class="alo-bold"><?= $type ?>: </span>
                        <span>[<?= $errno ?>] <?= $errstr ?></span>
                    </div>
                    <div>
                        <span class="alo-bold">Raised in </span>
                        <span class="alo-uline"><?= $errfile ?></span>
                        <span> @ line </span>
                        <span class="alo-uline"><?= $errline ?></span>
                    </div>
                    <div>
                        <span class="alo-bold">Backtrace:</span>
                        <?= $this->getTrace(array_slice(debug_backtrace(), 2), $label) ?>
                    </div>
                </div>
            </div>
            <?php

        }

        /**
         * Generates console output for errors
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param string $type    Error type
         * @param string $label   Error colour code ("e" for error, "w" for warning, "i" for info)
         * @param int    $errno   Error code
         * @param string $errstr  Error message
         * @param string $errfile File where the error occurred
         * @param int    $errline Line where the error occurred
         *
         * @uses   AloFramework\Handlers\Output\ConsoleOutput::write()
         * @uses   AloFramework\Handlers\Output\ConsoleOutput::writeln()
         * @uses   AbstractHandler::getTrace()
         */
        protected function handleCLI($type, $label, $errno, $errstr, $errfile, $errline) {
            $this->console->write('<' . $label . 'b>' . $type . '</>')
                ->write('<' . $label . '>: [' . $errno . '] ' . $errstr . '</>',
                        true)
                ->write('<' . $label . '>Raised in </>')
                ->write('<' . $label . 'u>' . $errfile . '</>')
                ->write('<' . $label . '> @ line </><' . $label . 'u>' . $errline . '</>', true)
                ->write('<' . $label . 'b>Debug backtrace:</>', true)
                ->writeln('');

            $this->getTrace(array_slice(debug_backtrace(), 2), $label);
        }

        /**
         * Registers the error handler
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         *                                AloFramework\Log\Log extends this interface.
         *
         * @return self The created handler
         * @since  1.0.4 Checks what class has called the method instead of explicitly registering ErrorHandler -
         * allows easy class extendability.
         */
        static function register(LoggerInterface $logger = null) {
            self::$registered = true;

            // To allow easy extending
            $class   = get_called_class();
            $handler = new $class($logger);

            set_error_handler([$handler, 'handle'], ALO_HANDLERS_ERROR_LEVEL);

            return $handler;
        }
    }
