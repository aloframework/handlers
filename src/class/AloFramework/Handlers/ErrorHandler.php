<?php

    namespace AloFramework\Handlers;

    use Psr\Log\LoggerInterface;

    /**
     * Handles PHP errors
     * @author Art <a.molcanovas@gmail.com>
     */
    class ErrorHandler extends AbstractHandler {

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
            self::injectCss();
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

            $f = implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, $errfile), -2));

            echo '<div class="text-center">' //BEGIN outer container
                 . '<div class="alo-err alert alert-' . $label . '">' //BEGIN inner container
                 . '<div>' //BEGIN header
                 . '<span class="alo-bold">' . $type . ': ' . '</span><span>' . $errstr . '</span></div>'//END header
                 . '<div><span class="alo-bold">Raised in </span>' . '<span class="alo-uline">' . $f . '</span>';

            if ($errline) {
                echo '<span> @ line </span><span class="alo-uline">' . $errline . '</span>';
            }

            echo '</div><span class="alo-bold">Backtrace:</span>';

            $trace = array_reverse(debug_backtrace());
            array_pop($trace);

            self::echoTrace($trace);

            echo '</div>'//END inner
                 . '</div>'; //END outer

            $this->log($errno, $errstr);
        }

        /**
         * Registers the error handler
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         *                                AloFramework\Log\Log extends this interface.
         *
         * @return callable The return value of set_error_handler()
         */
        static function register(LoggerInterface $logger = null) {
            self::$errorsRegistered = true;

            return set_error_handler([new ErrorHandler($logger), 'handle'], ALO_HANDLERS_ERROR_LEVEL);
        }
    }
