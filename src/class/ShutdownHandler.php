<?php

    namespace AloFramework\Handlers;

    use AloFramework\Common\Alo;
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
         * @var self
         */
        private static $lastRegisteredHandler = null;

        /**
         * Checks whether the handler has been registered
         *
         * @author Art <a.molcanovas@gmail.com>
         * @return bool
         */
        static function isRegistered() {
            return self::$registered;
        }

        /**
         * Returns the last registered handler
         * @author Art <a.molcanovas@gmail.com>
         * @return self|null
         * @since  1.3
         */
        static function getLastRegisteredHandler() {
            return self::$lastRegisteredHandler;
        }

        /**
         * The shutdown handler
         *
         * @author Art <a.molcanovas@gmail.com>
         * @since  1.2.1 Should now report fatal errors if no errors had been raised beforehand.
         */
        function handle() {
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
         * Registers the shutdown handler
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log shutdowns.
         *
         * @return self
         */
        static function register(LoggerInterface $logger = null) {
            self::$registered = true;
            $class            = get_called_class();
            $handler          = new $class($logger);

            register_shutdown_function([$handler, 'handle']);
            self::$lastRegisteredHandler = &$handler;

            return $handler;
        }

        /**
         * Returns a string representation of the object
         * @author Art <a.molcanovas@gmail.com>
         * @return string
         */
        function __toString() {
            return parent::__toString() . self::EOL . 'Registered: Yes';
        }
    }
