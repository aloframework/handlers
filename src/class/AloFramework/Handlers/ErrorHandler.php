<?php

    namespace AloFramework\Handlers;

    use Psr\Log\LoggerInterface;

    /**
     * Handles PHP errors
     * @author Art <a.molcanovas@gmail.com>
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

            $this->log($errno, $errstr);
        }

        /**
         * @param $type
         * @param $label
         * @param $errno
         * @param $errstr
         * @param $errfile
         * @param $errline
         */
        protected function handleHTML($type, $label, $errno, $errstr, $errfile, $errline) {
            echo '<div class="text-center">' //BEGIN outer container
                 . '<div class="alo-err alert alert-' . $label . '">' //BEGIN inner container
                 . '<div>' //BEGIN header
                 . '<span
class="alo-bold">' . $type . ': ' . '</span><span>[' . $errno . '] ' . $errstr . '</span></div>'//END header
                 . '<div><span
class="alo-bold">Raised
in </span>' . '<span class="alo-uline">' . $errfile . '</span>';

            echo '<span> @ line </span><span class="alo-uline">' . $errline . '</span>';

            echo '</div><span class="alo-bold">Backtrace:</span>';

            $trace = array_reverse(debug_backtrace());
            array_pop($trace);

            echo $this->getTrace($trace, $label);

            echo '</div>'//END inner
                 . '</div>'; //END outer

        }

        /**
         * @param $type
         * @param $label
         * @param $errno
         * @param $errstr
         * @param $errfile
         * @param $errline
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
         * @return ErrorHandler The created handler
         */
        static function register(LoggerInterface $logger = null) {
            self::$registered = true;
            $handler          = new ErrorHandler($logger);
            set_error_handler([$handler, 'handle'], ALO_HANDLERS_ERROR_LEVEL);

            return $handler;
        }
    }
