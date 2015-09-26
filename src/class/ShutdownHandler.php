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
         */
        function handle() {
            if (ErrorHandler::isRegistered()) {
                $e = new Error(error_get_last());

                if (self::shouldBeReported($e['type'])) {
                    $r = ErrorHandler::getLastReportedError();
                    $h = ErrorHandler::getLastRegisteredHandler();

                    if (!$e->isEmpty() && $r && !$r->isEmpty() && $h && !$r->equals($e)) {
                        $h->handle(E_CORE_ERROR,
                                   self::ifnull($e['message'], '<<unknown fatal error>>'),
                                   self::ifnull($e['file'], '<<unknown file>>'),
                                   self::ifnull($e['line'], '<<unknown line>>'));
                    }
                }
            }
        }

        /**
         * Returns $var if it's set, $backup if it's not
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param mixed $var    Reference to $var
         * @param mixed $backup Plan B
         *
         * @return mixed
         */
        private static function ifnull(&$var, $backup) {
            return isset($var) ? $var : $backup;
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
            $handler          = new $class($logger);

            register_shutdown_function([$handler, 'handle']);

            return $handler;
        }
    }
