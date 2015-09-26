<?php

    namespace AloFramework\Handlers;

    use Psr\Log\LoggerInterface;

    /**
     * The shutdown handler. Allows fatal error reporting
     * @author Art <a.molcanovas@gmail.com>
     * @since  1.2
     */
    class ShutdownHandler extends AbstractHandler {

        /**
         * Whether the handler has been registered
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
         * The shutdown handler
         * @author Art <a.molcanovas@gmail.com>
         * @uses   ErrorHandler::isRegistered()
         * @uses   ErrorHandler::getLastReportedError()
         * @uses   ErrorHandler::getLastRegisteredHandler()
         * @uses   ErrorHandler::handle()
         */
        function handle() {
            if (ErrorHandler::isRegistered()) {
                $e = error_get_last();

                if (self::shouldBeReported(isset($e['type']) ? $e['type'] : null)) {
                    $r = ErrorHandler::getLastReportedError();
                    $h = ErrorHandler::getLastRegisteredHandler();

                    if ($e && $h && $r != $e) {
                        $h->handle(E_CORE_ERROR,
                                   isset($e['message']) ? $e['message'] : '<<unknown fatal error>>',
                                   isset($e['file']) ? $e['file'] : '<<unknown file>>',
                                   isset($e['line']) ? $e['line'] : '<<unknown line>>');
                    }
                }
            }
        }

        /**
         * Checks if an error should be reported
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param int $errcode The error code
         *
         * @return bool
         */
        private static function shouldBeReported($errcode) {
            return $errcode && ((int)ALO_HANDLERS_ERROR_LEVEL) & ((int)$errcode) ? true : false;
        }

        /**
         * Registers the shutdown handler
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log shutdowns. AloFramework\Log\Log extends
         *                                this interface.
         *
         * @return self
         */
        static function register(LoggerInterface $logger = null) {
            self::$registered = true;
            $class            = get_called_class();
            $handler          = new $class();

            register_shutdown_function([$handler, 'handle']);

            return $handler;
        }
    }
